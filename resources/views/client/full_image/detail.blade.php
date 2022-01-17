@extends('layouts.client')
@section('body')

@php
    $id = $data['details']->id ?? '';
    $cardTitle = isset($data['details']->organization, $data['details']->service) ? "Org - ".$data['details']->organization->name.' -- '.$data['details']->service->name : "";
@endphp
    
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ $cardTitle }}</h3>
                        </div>


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

                            @if(isset($data['details']->admin_master_image) && ! $data['details']->admin_master_image->isEmpty())
                            <div class="row">
                                @foreach($data['details']->admin_master_image as $image)
                                    <div id="image-div-{{ $image->id }}" class='col-sm-4 col-xs-6 col-md-3 col-lg-3' style='padding: 5px;'>
                                        <a href="https://api.whatsapp.com/send?text={{ urlencode(route('client.fullimage.view', [auth()->id(), $image->id])) }}" target="_blank">
                                            <img id="image-{{ $image->id }}" src="{{ config('app.uploaded_assets').$image->image_link }}" width="50px">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ __("Bottom Image") }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <img id="{{ auth()->user()->name }}" src="{{ config('app.uploaded_assets').auth()->user()->bottom_image }}">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    
@stop

@section('css')
    @parent
    
@stop

@section('scripts')
    @parent
    
@stop