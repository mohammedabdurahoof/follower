<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\UploadDataSetting;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use \Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Utils\ApplicationUtils;

class CustomerImport implements ToCollection, SkipsEmptyRows, 
        WithHeadingRow, WithCalculatedFormulas, SkipsOnError, SkipsOnFailure, WithChunkReading {

    use Importable,
        SkipsErrors,
        SkipsFailures;

    private $client_data_upload, $table_name, $uploadSettings;
    private $columnExcept = [];

    public function __construct(object $client_data_upload, string $table_name) {
        $this->client_data_upload = $client_data_upload;
        $this->table_name = $table_name;
        $this->uploadSettings = UploadDataSetting::where('service_id', $this->client_data_upload->service_id)->first();
        $this->columnExcept = ApplicationUtils::getExceptColumns();
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows) {
        \Validator::make($rows->toArray(), [
             '*.name' => 'required', '*.mobile' => 'required', '*.dob' => 'required',
         ], ['*.name.required' => 'Name is required in excel', '*.mobile.required' => 'Mobile is required in excel row', 
             '*.dob.required' => 'DOB is required in excel'
             ])->validate();
        try {
            \DB::beginTransaction();
            $this->insertCustomer($rows);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            return dd("Error DB Inserting: " . $e->getMessage());
        }
        return;
    }
    
    

    public function chunkSize(): int {
        return 25;
    }

    private function insertCustomer(Collection $rows) {
        $insertArray = [];
        foreach ($rows as $row) {
            $dob = Date::excelToDateTimeObject($row['dob']);
            $customer = Customer::updateOrCreate(
                            ['mobile' => $row['mobile']],
                            [
                                'name' => $row['name'],
                                'mobile' => $row['mobile'],
                                'dob' => $dob->format('Y-m-d'),
                                'password' => Hash::make($dob->format('dmY')),
                            ]
            );
            $sync_org[$this->client_data_upload->organization_id] = ['client_id' => $this->client_data_upload->client_id];
            $sync_service[$this->client_data_upload->service_id] = ['client_id' => $this->client_data_upload->client_id];
            $customer->organizations()->wherePivot('client_id', $this->client_data_upload->client_id)->sync($sync_org);
            $customer->services()->wherePivot('client_id', $this->client_data_upload->client_id)->sync($sync_service);
            $insertArray[] = $this->getMultipleInsertArray($row, $customer->id, $this->client_data_upload->client_id);
        }
        // Inserting to related table at once
        \DB::table($this->table_name)->insert($insertArray);
        return;
    }

    private function getMultipleInsertArray(Collection $row, int $customer_id, int $client_id): array {
        // Need to modify date fields from upload_data_settings table during update
        foreach ($this->columnExcept as $val) {
            if(isset($row[$val])){
                unset($row[$val]);
            }
        }
        foreach($this->uploadSettings->columns as $k => $val){
            if(isset($row[$k]) && $val === 'date'){
                $date = Date::excelToDateTimeObject($row[$k]);
                $row[$k] = $date->format('Y-m-d');
            }
        }
        $date = date('Y-m-d H:i:s');
        $row['customer_id'] = $customer_id;
        $row['client_id'] = $client_id;
        $row['created_at'] = $date;
        $row['updated_at'] = $date;
        return $row->toArray();
    }

}
