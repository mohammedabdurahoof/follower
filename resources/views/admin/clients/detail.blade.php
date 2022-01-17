@extends('layouts.admin')
@section('body')

@php
    $id = $data['details']->id ?? '';
    $title = ($id != "") ? "Edit Client" : "Add Client";
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
                    <form role="form" id="client-add-edit" method="POST" action="{{ route('client.upsert') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">{{ $title }}</h3>
                            </div>
                            
                            <input type="hidden" class="form-control" id="client-id" name="client[id]" value="{{ $data['details']->id ?? old('client.id') }}">

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
                                                <label for="clientName">Client Name</label>
                                                <input type="text" class="form-control" id="clientName" placeholder="Client Name" name="client[name]" value="{{ $data['details']->name ?? old('client.name') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="organizationName">Organization Name</label>
                                                <select class="form-control " id="organizationName" name="client[organization_id]" required>
                                                    <option value=''>Select Organization</option>
                                                @foreach($data['organizations'] as $v)
                                                    <option value='{{ $v->id }}' {{ (isset($data['details']->organization_id) && ($data['details']->organization_id == $v->id)) ? 'selected' : (old('client.organization_id') == $v->id ? 'selected' : '') }}>{{ $v->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="serviceGroup">Service Groups</label>
                                                <select multiple="multiple" class="form-control " id="serviceGroup" name="client[services][]" required>
                                                    <option value=''>Select Services</option>
                                                @foreach($data['services'] as $v)
                                                    <option value='{{ $v->id }}' {{ (!empty($data['details']->services) && (in_array($v->id, $data['details']->services->pluck("id")->toArray()))) ? 'selected' : ((collect(old('client.services'))->contains($v->id)) ? 'selected' : '') }}>{{ $v->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control " id="status" name="client[status]" required>
                                                    <option value=''>Select Status</option>
                                                @foreach($data['status'] as $k => $v)
                                                    <option value='{{ $k }}' {{ (isset($data['details']->status) && ($data['details']->status == $k)) ? 'selected' : (old('client.status') == $k ? 'selected' : '') }}>{{ $v }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mobile">Mobile</label>
                                                <input type="text" class="form-control" id="mobile" placeholder="Your Mobile Number" name="client[mobile]" value="{{ $data['details']->mobile ?? old('client.mobile') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" id="email" placeholder="email" name="client[email]" value="{{ $data['details']->email ?? old('client.email') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="place">Place</label>
                                                <input type="text" class="form-control" id="place" placeholder="place" name="client[place]" value="{{ $data['details']->place ?? old('client.place') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cadre">Cadre</label>
                                                <input type="text" class="form-control" id="cadre" placeholder="cadre" name="client[cadre]" value="{{ $data['details']->cadre ?? old('client.cadre') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="product">Product</label>
                                                <input type="text" class="form-control" id="product" placeholder="product" name="client[product]" value="{{ $data['details']->product ?? old('client.product') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="dob">DOB</label>
                                                <input type="text" class="form-control" id="dob" placeholder="Client DOB" name="client[dob]" value="{{ $data['details']->dob ?? old('client.dob') }}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="dom">DOM</label>
                                                <input type="text" class="form-control" id="dom" placeholder="Client DOM" name="client[dom]" value="{{ $data['details']->dom ?? old('client.dom') }}"  data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="p_date">P.Date</label>
                                                <input type="text" class="form-control" id="p_date" placeholder="Client P.Date" name="client[p_date]" value="{{ $data['details']->p_date ?? old('client.p_date') }}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="r_date">R.Date</label>
                                                <input type="text" class="form-control" id="r_date" placeholder="Client R.Date" name="client[r_date]" value="{{ $data['details']->r_date ?? old('client.r_date') }}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @if(isset($data['details']->logo) && $data['details']->logo !== "")
                                        <div class='col-sm-4 col-xs-6 col-md-3 col-lg-2'>
                                            <img id="image-{{ $data['details']->id }}" src="{{ config('app.uploaded_assets').$data['details']->logo }}" width="100px">
                                        </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="logo">Logo</label>
                                                <input type="file" class="form-control" id="logo" placeholder="Browse Logo" name="client[logo]" {{ $id =="" || (isset($data['details']) && (is_null($data['details']->logo) || $data['details']->logo == "")) ? 'required' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <hr />
                                    </div>
                                    <div class="row">
                                        @if(isset($data['details']->bottom_image) && $data['details']->bottom_image !== "")
                                        <div class='col-sm-4 col-xs-6 col-md-3 col-lg-2'>
                                            <img id="image-{{ $data['details']->id }}" src="{{ config('app.uploaded_assets').$data['details']->bottom_image }}" width="100px">
                                        </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="bottom_image">Bottom Image</label>
                                                <input type="file" class="form-control" id="bottom_image" placeholder="Browse Bottom Image" name="client[bottom_image]" {{ $id =="" || (isset($data['details']) && (is_null($data['details']->bottom_image) || $data['details']->bottom_image == "")) ? 'required' : '' }}>
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
</div>

    
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
            $("#organizationName").select2();
            $("#serviceGroup").select2();
            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
            //Money Euro
            $('[data-mask]').inputmask();
            $("#client-add-edit").validate({
                rules:{
                    "client[name]": {
                        required: true
                    },
                    "client[organization_id]": {
                        required: true
                    },
                    "client[services]": {
                        required: true
                    },
                    "client[status]":{
                        required:true
                    }
                }
            });
        });
    </script>
@stop