@extends('layouts.admin')
@section('body')

@php
    $id = $data['details']->id ?? '';
    $file_type = $data['details']->file_type ?? '';
    $title = ($id != "") ? "Edit Data Upload" : "Add Data Upload";
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
                    <form role="form" id="admin-master-add-edit" method="POST" action="{{ route('admin.master.upsert') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">{{ $title }}</h3>
                            </div>
                            
                            <input type="hidden" class="form-control" id="admin-master-id" name="data[id]" value="{{ $data['details']->id ?? old('admin-master.id') }}">

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
                                        <div id="user-display-div" class="col-md-6" >
                                            <div class="form-group">
                                                <label for="user_display_type">User Display Type</label>
                                                <select class="form-control " id="user_display_type" name="data[user_display_type]">
                                                    <option value=''>Select User Display</option>
                                                @foreach($data['user_display_details'] as $k => $v)
                                                    <option value='{{ $k }}' {{ (isset($data['details']->user_display_type) && ($data['details']->user_display_type == $k)) ? 'selected' : (old('data.user_display_type') == $k ? 'selected' : '') }}>{{ $v }}</option>
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
                                    </div>
                                    
                                    @if(isset($data['details']->admin_master_image) && ! $data['details']->admin_master_image->isEmpty())
                                    <div class="row">
                                        @foreach($data['details']->admin_master_image as $image)
                                            <div id="image-div-{{ $image->id }}" class='col-sm-4 col-xs-6 col-md-3 col-lg-3' style='padding: 5px;'>
                                                <img id="image-{{ $image->id }}" data-model-id='{{ $image->id }}' src="{{ config('app.uploaded_assets').$image->image_link }}" onclick="deleteImageById(this)" width="100px">
                                            </div>
                                        @endforeach
                                    </div>
                                    @endif
                                    
                                    <div class='row'>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="file_name">File Name</label>
                                                <input type="text" class="form-control" id="file_name" placeholder="File Name" name="data[file_name]" value="{{ $data['details']->file_name ?? old('data.file_name') }}" required>
                                            </div>
                                        </div>
                                        <div id="upload-file-input" class="col-md-6">
                                            @if($id == "" || ($file_type == 'pdf'))
                                            <div class="form-group">
                                                <label for="file_link">File</label>
                                                <input type="file" class="form-control" id="file_link" placeholder="Browse File" name="data[file_link]" required>
                                            </div>
                                            @endif
                                            
                                            @if($id !== "" && ($file_type == 'images'))
                                            <div class="form-group">
                                                <label for="browse-images">Browse Images(Multiple Can Be Selected)</label>
                                                <input type="file" multiple class="form-control" id="browse-images" placeholder="Browse Images" name="data[images][]" required >
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if(isset($data['details']->notification))
                                    <div class="row">
                                        <div id="notification-div" class="col-md-12" >
                                            <div class="form-group">
                                                <label for="notification">Notification</label>
                                                <textarea type="text" class="form-control" id="notification" placeholder="Notification" rows="4", cols="20" name="data[notification]">
                                                    {{ $data['details']->notification->notification ?? old('data.notification') }}
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
    
@stop

@section('scripts')
    @parent
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.js"></script>
    <script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/select2/js/select2.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function deleteImageById(elem) {
            var result = confirm("Do You Want to Delete "+elem.getAttribute('id'));
            if(result){
                let imageid = parseInt(elem.getAttribute('data-model-id'));
                let imageDiv = document.getElementById("image-div-"+imageid);
                $.ajax("{{ route('ajax.master.delete.image') }}", {
                    type: 'POST',  // http method
                    data: {image_id: imageid},
                    success: function (data, status, xhr) {
                        imageDiv.remove();
                        return toastr.success(data);
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        console.log(textStatus);
                        return toastr.error(errorMessage);
                    }
                });
            }
            return;
        }
        
        function checkType(elem) {
            document.getElementById("user_display_type").required = false;
            document.getElementById("user-display-div").style.display = "none";
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
            if(elem === 'pdf') {
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
            if(elem === 'images') {
                document.getElementById("user-display-div").style.display = "block";
                document.getElementById("user_display_type").required = true;
                let added_div = document.getElementById("notification-div");
                if(added_div !== null) {
                    added_div.remove();
                }
                file_input.style.display = "block";
                return file_input.innerHTML = 
                        '<div class="form-group">'
                            +'<label for="browse-images">Browse Images(Multiple Can Be Selected)</label>'
                            +'<input type="file" multiple class="form-control" id="browse-images" placeholder="Browse Images" name="data[images][]" required>'
                        '</div>';
            }
            return null;
        }
        
        $(document).ready(function(){
            $("#organizationName").select2();
            $("#service-group").select2();
            $("#admin-master-add-edit").validate({
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