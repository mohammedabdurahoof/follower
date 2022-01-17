@extends('layouts.admin')
@section('body')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Client Master</h1>
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
                                    <a class='btn btn-primary btn-md card-title' href="{{ route('client.add.edit') }}" >Add Client Data</a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="client-list" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>DOB</th>
                                            <th>Organization Name</th>
                                            <th>DOM</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Place</th>
                                            <th>Cadre</th>
                                            <th>Product</th>
                                            <th>P.Date</th>
                                            <th>R.Date</th>
                                            <th>Status</th>
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
            $('#client-list').DataTable( {
                "processing": true,
                "serverSide": true,
                "buttons": ["excel", "colvis"],
                "responsive": true,
                "ordering": false,
                "language": {
                    "searchPlaceholder": "Search Client Name Or Mobile Or Organization."
                },
                "ajax":{
                     "url": "{{ route('client.server.list') }}",
                     "dataType": "JSON",
                     "type": "POST"
                },
                "columns": [
                    { "data": "name"},
                    { "data": "dob" },
                    { "data": "org" },
                    { "data": "dom" },
                    { "data": "mobile" },
                    { "data": "email" },
                    { "data": "place" },
                    { "data": "cadre" },
                    { "data": "product" },
                    { "data": "pdate" },
                    { "data": "rdate" },
                    { "data": "status" },
                    { "data": "options" }
                ]
            }).buttons().container().appendTo('#client-list_wrapper .col-md-6:eq(0)');
            
            $('.dataTables_filter input[type="search"]').css(
                {'width':'350px','display':'inline-block'}
            );
            
            @if(request()->session()->get('error'))
                toastr.error("<?=request()->session()->get('error')?>");
            @endif
            @if(request()->session()->get('message'))
                toastr.success("<?=request()->session()->get('message')?>");
            @endif
        });
    </script>
@endsection