@extends('layouts.admin')
@section('body')

@php
    $id = $data['organization_detail']->id ?? '';
    $title = ($id != "") ? "Edit Organization" : "Add Organization";
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                    
                        <form role="form" id="organization-add-edit" method="POST" action="{{ route('org.upsert') }}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" class="form-control" id="organization-id" name="organization[id]" value="{{ $data['organization_detail']->id ?? old('organization.id') }}">

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
                                                <input type="text" class="form-control" id="organizationName" placeholder="Organization Name" name="organization[name]" value="{{ $data['organization_detail']->name ?? old('organization.name') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control " id="status" name="organization[status]" required>
                                                    <option value=''>Select Status</option>
                                                @foreach($data['organization_status'] as $k => $v)
                                                    <option value='{{ $k }}' {{ (isset($data['organization_detail']->status) && ($data['organization_detail']->status == $k)) ? 'selected' : (old('organization.status') == $k ? 'selected' : '') }}>{{ $v }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @if(isset($data['organization_detail']->logo) && $data['organization_detail']->logo !== "")
                                        <div class='col-sm-4 col-xs-6 col-md-3 col-lg-2'>
                                            <img id="image-{{ $data['organization_detail']->id }}" src="{{ config('app.uploaded_assets').$data['organization_detail']->logo }}" width="100px">
                                        </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="logo">Logo</label>
                                                <input type="file" class="form-control" id="logo" placeholder="Browse Logo" name="organization[logo]" {{ (isset($data['organization_detail']) && (is_null($data['organization_detail']->logo) || $data['organization_detail']->logo == "")) ? 'required' : '' }}>
                                            </div>
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
                            </form>
                        </div>
                    </div>
                </div>

                    
            </div>
    </section>
</div>

    
@stop

@section('css')
    @parent
@stop

@section('scripts')
    @parent
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.js"></script>
    <script type="text/javascript">
        
        $(document).ready(function(){          
            $("#organization-add-edit").validate({
                rules:{
                    "organization[name]": {
                        required: true,
                    },
                    "organization[status]":{
                        required:true
                    }
                }
            });
        });
    </script>
@stop