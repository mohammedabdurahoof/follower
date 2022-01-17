@extends('layouts.client')
@section('body')

@php
    $id = $data['details']->id ?? '';
    $file_type = $data['details']->file_type ?? '';
    $title = ($id != "") ? "Edit Data Upload" : "Add Data Upload";
@endphp
    
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form role="form" id="client-data-add-edit" method="POST" action="{{ route('client.data.upload.upsert') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>

                        <input type="hidden" class="form-control" id="client-data-id" name="data[id]" value="{{ $data['details']->id ?? old('data.id') }}">

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
                                            <label for="organizationName">Organization Name</label>
                                            <select class="form-control " id="organizationName" name="data[organization_id]" required>
                                                <option value=''>Select Organization</option>
                                            @foreach($data['organizations'] as $v)
                                                <option value='{{ $v->id }}' {{ (isset($data['details']->organization_id) && ($data['details']->organization_id == $v->id)) ? 'selected' : (old('data.organization_id') == $v->id ? 'selected' : '') }}>{{ $v->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service-group">Service Group Name</label>
                                            <select class="form-control " id="service-group" name="data[service_id]" required>
                                                <option value=''>Select Service Group</option>
                                            @foreach($data['services'] as $v)
                                                <option value='{{ $v->id }}' {{ (isset($data['details']->service_id) && ($data['details']->service_id == $v->id)) ? 'selected' : (old('data.service_id') == $v->id ? 'selected' : '') }}>{{ $v->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 {{ ($id != "") ? 'no-pointer' : '' }}">
                                        <div class="form-group">
                                            @foreach($data['file_type'] as $k => $v)
                                            <div class="form-check">
                                                <input onchange="checkType(this.value)" class="form-check-input" type="radio" name="data[file_type]" value="{{ $k }}" {{ (isset($data['details']->file_type) && $data['details']->file_type == $k) ? 'checked' : '' }} required>
                                                <label class="form-check-label">{!! $v !!}-{{ $k }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @if(isset($data['details']->file_view))
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label >File View</label>
                                            {!! $data['details']->file_view !!}
                                        </div>
                                    </div>
                                    @endif

                                    <div id="upload-file-input" class="col-md-6">
                                        @if($id == "" || ($file_type == 'xls'))
                                        <div class="form-group">
                                            <label for="file_link">File</label>
                                            <input type="file" class="form-control" id="file_link" placeholder="Browse File" name="data[file_link]" required>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @if(isset($data['details']->customer_notification))
                                <div class="row">
                                    <div id="notification-div" class="col-md-12" >
                                        <div class="form-group">
                                            <label for="notification">Notification</label>
                                            <textarea type="text" class="form-control" id="notification" placeholder="Notification" rows="4", cols="20" name="data[notification]">
                                                {{ $data['details']->customer_notification->notification ?? old('data.notification') }}
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div id="notification-input" class="row">

                                </div>
                                <div class="row">
                                    <div class="col-md-6 offset-md-3 text-center">
                                        @if($id == '')
                                        <button type="submit" class="btn btn-success btn-block" onclick="noPointer(this)">Save</button>
                                        @else
                                        <button type="submit" class="btn btn-primary btn-block" onclick="noPointer(this)">Update</button>
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

    
@stop

@section('css')
    @parent
    <link rel="stylesheet" href="{{ config('app.asset_url') }}/vendor/adminlte/plugins/select2/css/select2.min.css">
    
@stop

@section('scripts')
    @parent
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.js"></script>
    <script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/select2/js/select2.min.js"></script>
    <script type="text/javascript">
        
        function checkType(elem) {
            var file_input = document.getElementById("upload-file-input");
            if(elem === 'txt') {
                file_input.style.display = "none";
                let html =  
                        '<div id="notification-div" class="col-md-12" >'
                            +'<div class="form-group">'
                                +'<label for="notification">Notification</label>'
                                +'<textarea type="text" class="form-control" id="notification" placeholder="Notification" rows="4", cols="20" name="data[notification]">'
                                +'</textarea>'
                            +'</div>'
                        +'</div>';
                return document.getElementById("notification-input").insertAdjacentHTML("beforeend", html);
            }
            if(elem === 'xls') {
                let added_div = document.getElementById("notification-div");
                if(added_div !== null) {
                    added_div.remove();
                }
                file_input.style.display = "block";
                return file_input.innerHTML = 
                        '<div class="form-group">'
                            +'<label for="file_link">Browse File</label>'
                            +'<input type="file" class="form-control" id="file_link" placeholder="Browse File" name="data[file_link]" required >'
                        +'</div>';
            }
            return null;
        }
        
        function noPointer(elem){
            return elem.classList.add("no-pointer");
        }
        
        $(document).ready(function(){
            $("#organizationName").select2();
            $("#service-group").select2();
            $("#client-data-add-edit").validate({
                rules:{
                    "data[service_id]": {
                        required: true
                    },
                    "data[organization_id]": {
                        required: true
                    },
                    "data[file_type]":{
                        required:true
                    }
                }
            });
        });
    </script>
@stop