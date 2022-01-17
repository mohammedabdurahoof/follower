<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Admin;

use App\Models\Client;
use App\Models\ClientData;
use App\Models\ClientAward;
use App\Models\CustomerOrganization;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;
use Hash;

/**
 * Description of ClientClient
 *
 * @author Ritobroto
 */
class ClientService {
    public function getList(int $start, int $limit, $query=""): array {
        $return['totalData'] = Client::count();
        $return['totalFiltered'] = $return['totalData'];
        
        $qry = Client::with(['organization', 'services']);
        if($query != ""){ 
            $search = strtolower($query); 
            $qry->whereRaw("LOWER(name) LIKE '%{$search}%'");
            $qry->orWhereRaw("LOWER(mobile) LIKE '%{$search}%'");
            $qry->orWhereHas('organization', function($query) use ($search) {
                $query->whereRaw("LOWER(name) LIKE '%{$search}%'");
            });
            
            $return['totalFiltered'] = $qry->count();
        }
        $return['data'] = $qry->offset($start)->limit($limit)->orderBy('id','DESC')->get();
        return $return;
    }
    
    public function getDetailsById(int $id): Client {
        return Client::with(['services','organization'])->find($id);
    }
    
    public function upsertData(array $inputs): Client {
//        dd($inputs);
        if($inputs['id'] == ""){
            $client = new Client();
        }
        if($inputs['id'] > 0){
            $client = Client::where('id', $inputs['id'])->firstOrfail();
        }
        foreach($inputs as $k => $v){
            if($k == 'id' || $k == 'services' || $k == 'logo'){
                continue;
            }
            $client->$k = $v;
        }
        $password_input = date('dmY', strtotime($inputs['dob']));
        $client->password = Hash::make($password_input);
        $client->save();
        
        $this->updatedRelations($inputs['services'], $client);
        
        return $client;
    }
    
    public function delete(int $id): Client {
        $client = Client::findOrFail($id);
        $datas = ClientData::where('client_id', $id)->first();
        $awards = ClientAward::where('client_id', $id)->first();
        $customers = CustomerOrganization::where('client_id', $id)->pluck('customer_id')->toArray();
        if(isset($datas->id) && !is_null($datas->file_link) && Storage::exists($datas->file_link)){
            Storage::delete($datas->file_link); // if have a file delete it
        }
        if(isset($awards->id) && !is_null($awards->photo) && Storage::exists($awards->photo)){
            Storage::delete($awards->photo); // if have aan award image delete it
        }
        if(is_array($customers) && count($customers) > 0) {
            Customer::whereIn('id', $customers)->delete();
        }
        if(isset($client->id)){
            if(!is_null($client->logo) && Storage::exists($client->logo)){
                Storage::delete($client->logo); // if have an image delete it
            }
            if(!is_null($client->bottom_image) && Storage::exists($client->bottom_image)){
                Storage::delete($client->bottom_image); // if have an image delete it
            }
            Client::destroy($id);
        }
        return $client;
    }
    
    private function updatedRelations(array $service_ids, Client $client){
        return $client->services()->sync($service_ids);
    }
}
