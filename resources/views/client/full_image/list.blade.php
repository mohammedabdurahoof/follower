@extends('layouts.client')
@section('body')

    
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
<!--                            <div class="card-header">
                                <div class="col-6 pull-right">
                                    <a class='btn btn-primary btn-md card-title' href="{{ route('admin.master.add.edit') }}" >Add Master Data</a>
                                </div>
                            </div>-->
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="full-image-list" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Service Group</th>
                                            <th>Organization Name</th>
                                            <th>Display Type</th>
                                            <th>File Name</th>
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
            $('#full-image-list').DataTable( {
                "processing": true,
                "serverSide": true,
                "buttons": ["excel", "colvis"],
                "responsive": true,
                "ordering": false,
                "language": {
                    "searchPlaceholder": "Search Org Or Service Name."
                },
                "ajax":{
                     "url": "{{ route('client.fullimage.server.list') }}",
                     "dataType": "JSON",
                     "type": "POST"
                },
                "columns": [
                    { "data": "serial_number"},
                    { "data": "service_group" },
                    { "data": "organization_name" },
                    { "data": "display_type" },
                    { "data": "file_name" },
                    { "data": "options" }
                ]
            }).buttons().container().appendTo('#full-image-list_wrapper .col-md-6:eq(0)');
            
            @if(request()->session()->get('error'))
                toastr.error("<?=request()->session()->get('error')?>");
            @endif
            @if(request()->session()->get('message'))
                toastr.success("<?=request()->session()->get('message')?>");
            @endif
        });
    </script>
@endsection