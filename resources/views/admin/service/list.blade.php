@extends('layouts.admin')
@section('body')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Service Master</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-6 pull-right">
                                    <a class='btn btn-primary btn-md card-title' href="{{ route('service.add.edit') }}" >Add Service</a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="service-list" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Service Name</th>
                                            <th>Organization Name</th>
                                            <th>Status</th>
                                            <!--<th>Service Logo</th>-->
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
                
        

@endsection
@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#service-list').DataTable( {
                "processing": true,
                "serverSide": true,
                "buttons": ["excel", "colvis"],
                "responsive": true,
                "ordering": false,
                "language": {
                    "searchPlaceholder": "Search Service Name."
                },
                "ajax":{
                     "url": "{{ route('service.server.list') }}",
                     "dataType": "JSON",
                     "type": "POST"
                },
                "columns": [
                    { "data": "service_name" },
                    { "data": "organization_name" },
                    { "data": "service_status" },
                    { "data": "options" }
                ]
            }).buttons().container().appendTo('#service-list_wrapper .col-md-6:eq(0)');
            
            @if(request()->session()->get('error'))
                toastr.error("<?=request()->session()->get('error')?>");
            @endif
            @if(request()->session()->get('message'))
                toastr.success("<?=request()->session()->get('message')?>");
            @endif
        });
    </script>
@endsection