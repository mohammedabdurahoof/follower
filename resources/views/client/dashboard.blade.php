@extends('layouts.client')
@section('body')

@php
    $id = $data['details']->client_organization_detail->id ?? '';
    $title = "My Profile";
@endphp


<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form role="form" id="dashboard-add-edit" method="POST" action="{{ route('client.details.upsert') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>

                        <input type="hidden" class="form-control" id="client-id" name="client_details[client_id]" value="{{ $data['details']->id ?? old('client_details.client_id') }}">
                        <input type="hidden" class="form-control" id="client-details-id" name="client_details[id]" value="{{ $data['details']->client_organization_detail->id ?? old('client_details.id') }}">

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
                                <div class="row" style="pointer-events: none; background-color: grey;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="organizationName">Organization Name</label>
                                            <input type="text" class="form-control" id="organizationName" placeholder="Organization Name" value="{{ $data['details']->organization->name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="serviceGroup">Service Groups</label>
                                            <select multiple="multiple" class="form-control " id="serviceGroup">
                                                <option value=''>Select Services</option>
                                            @foreach($data['services'] as $v)
                                                <option style="color: blue;" value='{{ $v->id }}' {{ (!empty($data['details']->services) && (in_array($v->id, $data['details']->services->pluck("id")->toArray()))) ? 'selected' : '' }}>{{ $v->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea type="text" class="form-control" id="address" placeholder="Address" rows="4", cols="20" name="client_details[address]" required>
                                                {{ $data['details']->client_organization_detail->address ?? old('client_details.address') }}
                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="about">About Us</label>
                                            <textarea type="text" class="form-control" id="about" placeholder="About Us" rows="4", cols="20" name="client_details[about]" required>
                                                {{ $data['details']->client_organization_detail->about ?? old('client_details.about') }}
                                            </textarea>
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
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
            color: #007bff;
        }
    </style>
@stop

@section('scripts')
    @parent
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.js"></script>
    <script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/select2/js/select2.min.js"></script>
    <script type="text/javascript">
        
        $(document).ready(function(){
            $("#serviceGroup").select2();
            $("#dashboard-add-edit").validate({
                rules:{
                    "client_details[about]": {
                        required: true
                    },
                    "client_details[address]": {
                        required: true
                    }
                }
            });
        });
    </script>
@stop