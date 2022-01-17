@extends('layouts.client')
@section('body')

@php
    $id = $data['details']->id ?? '';
    $title = ($id != "") ? "Edit Awards" : "Add Awards";
@endphp

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form role="form" id="award-add-edit" method="POST" action="{{ route('award.upsert') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">{{ $title }}</h3>
                            </div>
                            
                            <input type="hidden" class="form-control" id="award-id" name="award[id]" value="{{ $data['details']->id ?? old('award.id') }}">
                            <input type="hidden" class="form-control" id="award-client-id" name="award[client_id]" value="{{ $data['details']->client_id ?? auth()->id() }}">

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
                                                <label for="awardName">Award Name</label>
                                                <input type="text" class="form-control" id="awardName" placeholder="Award Name" name="award[name]" value="{{ $data['details']->name ?? old('award.name') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="awardFrom">Award From</label>
                                                <input type="text" class="form-control" id="awardFrom" placeholder="Award From" name="award[received_from]" value="{{ $data['details']->received_from ?? old('award.received_from') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="achieved_date">Achieved Date</label>
                                                <input type="text" class="form-control" id="achieved_date" placeholder="Award Received Date" name="award[achieved_date]" value="{{ $data['details']->achieved_date ?? old('award.achieved_date') }}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @if(isset($data['details']->photo) && $data['details']->photo !== "")
                                        <div class='col-sm-4 col-xs-6 col-md-3 col-lg-2'>
                                            <img id="image-{{ $data['details']->id }}" src="{{ config('app.uploaded_assets').$data['details']->photo }}" width="100px">
                                        </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="photo">Award Photo</label>
                                                <input type="file" class="form-control" id="photo" placeholder="Browse Image" name="award[photo]" >
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
    
@stop

@section('scripts')
    @parent
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.js"></script>
    <script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/select2/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
            //Money Euro
            $('[data-mask]').inputmask();
            $("#award-add-edit").validate({
                rules:{
                    "award[name]": {
                        required: true
                    },
                    "award[received_from]": {
                        required: true
                    },
                    "award[achieved_date]":{
                        required:true
                    }
                }
            });
        });
    </script>
@stop