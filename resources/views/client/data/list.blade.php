@extends('layouts.client')
@section('body')

    
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-6 pull-right">
                                <a class='btn btn-primary btn-md card-title' href="{{ route('client.data.upload.add.edit') }}" >Add Client Data Upload</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="client-data-list" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Service Group</th>
                                        <th>Organization</th>
                                        <th>Uploaded At</th>
                                        <th>File</th>
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
            $('#client-data-list').DataTable( {
                "processing": true,
                "serverSide": true,
                "buttons": ["excel", "colvis"],
                "responsive": true,
                "ordering": false,
                "language": {
                    "searchPlaceholder": "Search Client Data Upload Name."
                },
                "ajax":{
                     "url": "{{ route('client.data.upload.server.list') }}",
                     "dataType": "JSON",
                     "type": "POST"
                },
                "columns": [
                    { "data": "serial_number"},
                    { "data": "service_group" },
                    { "data": "organization_name" },
                    { "data": "file_uploaded_date" },
                    { "data": "file_link" },
                    { "data": "options" }
                ]
            }).buttons().container().appendTo('#client-data-list_wrapper .col-md-6:eq(0)');
            
            @if(request()->session()->get('error'))
                toastr.error("<?=request()->session()->get('error')?>");
            @endif
            @if(request()->session()->get('message'))
                toastr.success("<?=request()->session()->get('message')?>");
            @endif
        });
    </script>
@endsection