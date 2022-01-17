@extends('layouts.client')
@section('body')

    
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ __("Customer List") }}</h3>
                            <div class="col-6 pull-right">
                                <!--<a class='btn btn-primary btn-md card-title' href="{{ route('customer.add.edit') }}" >Add Customer</a>-->
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="client-data-list" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                        <th>Mobile</th>
                                        <th>DOB</th>
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
                     "url": "{{ route('customer.server.list') }}",
                     "dataType": "JSON",
                     "type": "POST"
                },
                "columns": [
                    { "data": "serial_number"},
                    { "data": "name" },
                    { "data": "mobile" },
                    { "data": "dob" },
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