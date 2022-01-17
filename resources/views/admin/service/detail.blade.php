@extends('layouts.admin')
@section('body')

@php
$id = $data['service_detail']->id ?? '';
$title = ($id != "") ? "Edit Service" : "Add Service";
@endphp
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $title }}</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form role="form" id="service-add-edit" method="POST" action="{{ route('service.upsert') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">{{ $title }}</h3>
                            </div>

                            <input type="hidden" class="form-control" id="service-id" name="service[id]" value="{{ $data['service_detail']->id ?? old('service.id') }}">

                            <div class="card-body">
                                @if ($errors->any())
                                <div class='row'>
                                    <div class="col-md-6 offset-md-3 alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="serviceName">Service Name</label>
                                            <input type="text" class="form-control" id="serviceName" placeholder="Service Name" name="service[name]" value="{{ $data['service_detail']->name ?? old('service.name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control " id="status" name="service[status]" required>
                                                <option value=''>Select Status</option>
                                                @foreach($data['status'] as $k => $v)
                                                <option value='{{ $k }}' {{ (isset($data['service_detail']->status) && ($data['service_detail']->status == $k)) ? 'selected' : (old('service.status') == $k ? 'selected' : '') }}>{{ $v }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="organizationName">Organization Name</label>
                                            <select class="form-control " id="organizationName" name="service[organization_id]" required>
                                                <option value=''>Select Organization</option>
                                                @foreach($data['organizations'] as $v)
                                                <option value='{{ $v->id }}' {{ (isset($data['service_detail']->organization_id) && ($data['service_detail']->organization_id == $v->id)) ? 'selected' : (old('service.organization_id') == $v->id ? 'selected' : '') }}>{{ $v->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(isset($data['user_display']))
                        @foreach($data['user_display'] as $key => $value)
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">{{ $value['name'] }} Images</h3>
                            </div>
                            <div class="card-body">
                                <input type="hidden" class="form-control" id="admin-master-display-type" name="admin_master[{{ $key }}][user_display_type]" value="{{ $key }}">
                                <input type="hidden" class="form-control" id="admin-master-file-type" name="admin_master[{{ $key }}][file_type]" value="{{ __('images') }}">
                                <div class='row'>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="file_name">File Name</label>
                                            <input type="text" class="form-control" id="file_name_{{ $key }}" placeholder="{{ $value['name'] }} File Name" name="admin_master[{{ $key }}][file_name]">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="browse-images">Browse Images(Multiple Can Be Selected)</label>
                                            <input type="file" multiple class="form-control" id="browse-{{ $key }}" placeholder="Browse Images" name="admin_master[{{ $key }}][{{ $value['slug'] }}][]" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif

                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Fields</h3>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8 offset-md-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" name="service[table_exists]" type="checkbox" id="customCheckbox" value="yes">
                                                <label for="customCheckbox" class="custom-control-label">{{ __('Select This If Client Will Upload Data Using This Service Group') }} <br/> {{ __('For Deleting Updating Or Adding DataBase/Column') }} <br/> {{ __('(Don\'t tick if client will not upload data for this service group unnecessarily)')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div  class="col-md-6 dynamic-fields">
                                        <div class='row'>
                                            <div class='col-md-8'>
                                                <div class='form-group'>
                                                    <label for='name' >{{ __('Name') }}</label>
                                                    <input type='text' class='form-control' id='name' name='service[fields][name][name]' value="{{ __('Name') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 dynamic-fields">
                                        <div class='row'>
                                            <div class='col-md-8'>
                                                <div class='form-group'>
                                                    <label for='mobile' >{{ __('Mobile') }}</label>
                                                    <input type='text' class='form-control' id='mobile' name='service[fields][mobile][name]' value="{{ __('Mobile') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 dynamic-fields">
                                        <div class='row'>
                                            <div class='col-md-8'>
                                                <div class='form-group'>
                                                    <label for='dob' >{{ __('DOB') }}</label>
                                                    <input type='text' class='form-control' id='dob' value="{{ __('DOB') }}" name='service[fields][dob][name]'  required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($id === "")
                                    <div class="col-md-6 dynamic-fields">
                                        <div class='row'>
                                            <div class='col-md-8'>
                                                <div class='form-group'>
                                                    <label for='filed-name-4' >{{ __('New Field Name(Unique Field)') }}</label>
                                                    <input type='text' class='form-control' placeholder="Enter Unique Field Name" id='filed-name-4' name='service[fields][4][name]' required>
                                                    <input class='custom-control-input' name='service[fields][4][data_type]' type='hidden' id='hidden-4' value='unique'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div id="field-div" class="row">
                                    @if(isset($data['service_detail'], $data['service_detail']->fields, $data['service_detail']->upload_data_settings))
                                        @php 
                                        $columnsDataTypes = $data['service_detail']->upload_data_settings->columns;
                                        @endphp
                                    @foreach($data['service_detail']->fields as $idx => $field)
                                        @if(in_array($idx, $data['exceptcolumns']))
                                            @php continue; @endphp
                                        @endif
                                        
                                        @php $date=''; @endphp
                                        @if(isset($columnsDataTypes[$idx]) && $columnsDataTypes[$idx] !== 'unique')
                                            @php $date='checked'; @endphp
                                        @endif
                                        
                                        <div id="{{ $idx }}" class="col-md-6 dynamic-fields">
                                            <div class='row'>
                                                <div class='col-md-8'>
                                                    <div class='form-group'>
                                                        <label for='{{ $idx }}' >{{ ucwords($field) }}{{ (isset($columnsDataTypes[$idx]) && $columnsDataTypes[$idx] == 'unique') ? ' (Unique)': '' }}</label>
                                                        <input type='text' class='form-control' id='{{ $idx }}' name='service[fields][{{ $idx }}][name]' value="{{ ucwords($field) }}">
                                                        @if(isset($columnsDataTypes[$idx]) && $columnsDataTypes[$idx] == 'unique')
                                                        <input class='custom-control-input' name='service[fields][{{ $idx }}][data_type]' type='hidden' id='hidden-{{$idx}}' value='{{$columnsDataTypes[$idx]}}'>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if(!isset($columnsDataTypes[$idx]) || isset($columnsDataTypes[$idx]) && $columnsDataTypes[$idx] !== 'unique')
                                                <div class='col-md-2'>
                                                    <div class='form-group'>
                                                        <div class='custom-control custom-checkbox field-delete-button'>
                                                            <input class="custom-control-input field-delete-button" {{ $date }} name='service[fields][{{ $idx }}][data_type]' type='checkbox' id='customCheckbox-date-{{ $idx }}' value='date'>
                                                            <label for='customCheckbox-date-{{ $idx }}' class='custom-control-label'>Is Date Column?</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if(!isset($columnsDataTypes[$idx]) || isset($columnsDataTypes[$idx]) && $columnsDataTypes[$idx] !== 'unique')
                                                <div class='col-md-2'>
                                                    <div class='form-group'>
                                                        <button type='button' class='btn btn-md btn-default text-danger field-delete-button' title='Delete {{ ucwords($field) }}' onclick="deleteSavedFields('{{ $idx }}')" ><i class='fa fa-lg fa-fw fa-trash'></i></button>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-6 offset-md-3 text-center" >
                                        <button style="margin: 5px;" id="add-more-fields" class="btn btn-primary btn-md" type="button" onclick="addmorefield()">Add Field</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 offset-md-3 text-center">
                                        @if($id == '')
                                        <button type="submit" class="btn btn-success btn-block">Save</button>
                                        @else
                                        <button type="submit" class="btn btn-primary btn-block">Update</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>   
                </div>
            </div>
        </div>
    </section>
</div>


@stop

@section('css')
@parent
<link rel="stylesheet" href="{{ config('app.asset_url') }}/vendor/adminlte/plugins/select2/css/select2.min.css">
<style>
    .field-delete-button {
        margin-top: 32px;
    }
</style>
@stop

@section('scripts')
@parent
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.js"></script>
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript">
    function addmorefield(elm){
        let countfields = checkFields();
        if (parseInt(countfields) < 30){
            let next_node = parseInt(countfields) + parseInt(1);
            let main_div = document.getElementById("field-div");
            let html =
                    "<div id='fields-" + next_node + "' class='col-md-6 dynamic-fields'>"
                        + "<div class='row'>"
                            + "<div class='col-md-8'>"
                                + "<div class='form-group'>"
                                    + "<label for='filed-name-" + next_node + "' >New Field Name</label>"
                                    + "<input type='text' class='form-control' id='filed-name-" + next_node + "' placeholder='New Field Name' name='service[fields][" + next_node + "][name]'>"
                                + "</div>"
                            + "</div>"
                            + "<div class='col-md-2'>"
                                + "<div class='form-group'>"
                                    + "<div class='custom-control custom-checkbox field-delete-button'>"
                                        + "<input class='custom-control-input field-delete-button' name='service[fields][" + next_node + "][data_type]' type='checkbox' id='customCheckbox-date-"+ next_node +"' value='date'>"
                                        + "<label for='customCheckbox-date-"+ next_node +"' class='custom-control-label'>Date Column?</label>"
                                    + "</div>"
                                + "</div>"
                            + "</div>"
                            + "<div class='col-md-2'>"
                                + "<div class='form-group'>"
                                    + "<button type='button' class='btn btn-md btn-default text-danger field-delete-button' title='Delete' onclick='deletefield(" + next_node + ")' ><i class='fa fa-lg fa-fw fa-trash'></i></button>"
                                + "</div>"
                            + "</div>"
                        + "</div>"
                    + "</div>";
            return main_div.insertAdjacentHTML("beforeend", html);
        } else{
            return toastr.error("You Can Add Only 30 Fields");
        }
    }
    
    function checkFields(){
        return document.getElementsByClassName('dynamic-fields').length;
    }

    function deletefield(fieldIndex){
        var previous = document.getElementById("add-more-fields");
        previous.setAttribute("data-id", fieldIndex);
        var elem = document.getElementById("fields-" + fieldIndex);
        return elem.remove();
    }
    function deleteSavedFields(idx) {
        var elem = document.getElementById(idx);
        return elem.remove();
    }                                     
    $(document).ready(function(){
        $("#organizationName").select2();
        
        $("#service-add-edit").validate({
        rules:{
            "service[name]": {
                required: true
            },
            "service[organization_id]": {
                required: true
            },
            "service[status]":{
                required:true
            },
            "service[fields][name]": {
                required: true
            },
            "service[fields][mobile]": {
                required: true
            },
            "service[fields][dob]": {
                required: true
            }
        }
    });
    @if(request()->session()->get('error'))
        toastr.error("<?=request()->session()->get('error')?>");
    @endif
    @if(request()->session()->get('message'))
        toastr.success("<?=request()->session()->get('message')?>");
    @endif
    });
</script>
@stop