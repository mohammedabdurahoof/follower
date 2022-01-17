<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Client\FullimageService;
use App\Traits\FullImage;
use App\Utils\ApplicationUtils;

class ImagesController extends Controller {

    use FullImage;

    private $service;

    public function __construct() {
        parent::__construct();
        $this->service = new FullimageService();
    }

    public function mergedImage(int $clientId, int $imageId) {
        $top_image = $this->service->getTopImageById($imageId);
        $bottom_image = $this->service->getBottomImageById($clientId);
        if (!isset($top_image, $bottom_image)) {
            return redirect()->back()->with('error', 'Top image Or Bottom Image Is not present!!');
        }
        return $this->mergeImage($top_image, $bottom_image);
    }

    public function getDataById(int $id) {
        if ($id <= 0) {
            return redirect()->back()->with('error', 'Data Not Available In Server');
        }
        $this->data['details'] = $this->service->getDetailsById($id);

        return view('client.full_image.detail', ['data' => $this->data]);
    }

    public function list() {
        return view('client.full_image.list');
    }

    public function serverList(Request $request): string {
        $result = [];

        $admin_master = $this->service->getList($request->input('start'), $request->input('length'), $request->input('search.value'));

        if (!empty($admin_master)) {
            $result = $this->getPaginationData($admin_master['data']);
        }
        $json_data = ApplicationUtils::getPaginationReturnData($request, $admin_master['totalData'], $admin_master['totalFiltered'], $result);
        return json_encode($json_data);
    }

    private function getPaginationData(object $admin_masters): array {
        $result = [];
        (int) $i = 0;
        foreach ($admin_masters as $admin_master) {
            $view = route('client.fullimage.detail', [$admin_master->id]);

            $nestedData['serial_number'] = ++$i;
            $nestedData['service_group'] = isset($admin_master->service) ? $admin_master->service->name : "No Service found";
            $nestedData['organization_name'] = isset($admin_master->organization) ? $admin_master->organization->name : "No Organization found";
            $nestedData['display_type'] = "Full Page";
            $nestedData['file_name'] = isset($admin_master->file_name) ? $admin_master->file_name : "No Name Added";
            $nestedData['options'] = "<a class='btn btn-xs btn-default text-primary mx-1 shadow' title='View' href='{$view}' ><i class='fa fa-lg fa-fw fa-eye'></i></a>";
            $result[] = $nestedData;
        }
        return $result;
    }

}
