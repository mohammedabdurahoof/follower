@extends('layouts.client')
@section('body')

@php
    $id = $data['details']->id ?? '';
    $title = ($id != "") ? "Edit Customer" : "Add Customer";
@endphp

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form role="form" id="customer-add-edit" method="POST" action="{{ route('customer.upsert') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">{{ $title }}</h3>
                            </div>
                            
                            <input type="hidden" class="form-control" id="customer-id" name="customer[id]" value="{{ $data['details']->id ?? old('customer.id') }}">

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
                                                <label for="customerName">Customer Name</label>
                                                <input type="text" class="form-control" id="customerName" placeholder="Customer Name" name="customer[name]" value="{{ $data['details']->name ?? old('customer.name') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mobile">Mobile</label>
                                                <input type="text" class="form-control" id="mobile" placeholder="Customer Mobile" name="customer[mobile]" value="{{ $data['details']->mobile ?? old('customer.mobile') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="organization">Organization</label>
                                                <select multiple="multiple" class="form-control " id="organization" name="customer[organizations][]" required>
                                                    <option value=''>Select Organization</option>
                                                @foreach($data['organizations'] as $v)
                                                    <option value='{{ $v->id }}' {{ (!empty($data['details']->organizations) && (in_array($v->id, $data['details']->organizations->pluck("id")->toArray()))) ? 'selected' : ((collect(old('customer.organizations'))->contains($v->id)) ? 'selected' : '') }}>{{ $v->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div><div class="col-md-6">
                                            <div class="form-group">
                                                <label for="serviceGroup">Service Groups</label>
                                                <select multiple="multiple" class="form-control " id="serviceGroup" name="customer[services][]" required>
                                                    <option value=''>Select Services</option>
                                                @foreach($data['services'] as $v)
                                                    <option value='{{ $v->id }}' {{ (!empty($data['details']->services) && (in_array($v->id, $data['details']->services->pluck("id")->toArray()))) ? 'selected' : ((collect(old('customer.services'))->contains($v->id)) ? 'selected' : '') }}>{{ $v->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="dob">DOB</label>
                                                <input type="text" class="form-control" id="dob" placeholder="Customer dob" name="customer[dob]" value="{{ $data['details']->dob ?? old('customer.dob') }}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask required>
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
            $("#organization").select2();
            $("#serviceGroup").select2();
            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
            //Money Euro
            $('[data-mask]').inputmask();
            $("#customer-add-edit").validate({
                rules:{
                    "customer[name]": {
                        required: true
                    },
                    "customer[dob]": {
                        required: true
                    },
                    "customer[mobile]":{
                        required:true
                    }
                }
            });
        });
    </script>
@stop