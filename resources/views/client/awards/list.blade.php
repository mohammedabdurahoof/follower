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
                                <a class='btn btn-primary btn-md card-title' href="{{ route('award.add.edit') }}" >Add Awards</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="awards-list" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Awards Name</th>
                                        <th>Award From</th>
                                        <th>Received Date</th>
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
            $('#awards-list').DataTable( {
                "processing": true,
                "serverSide": true,
                "buttons": ["excel", "colvis"],
                "responsive": true,
                "ordering": false,
                "language": {
                    "searchPlaceholder": "Search Awards Name."
                },
                "ajax":{
                     "url": "{{ route('award.server.list') }}",
                     "dataType": "JSON",
                     "type": "POST"
                },
                "columns": [
                    { "data": "award_name" },
                    { "data": "award_from" },
                    { "data": "received_date" },
                    { "data": "options" }
                ]
            }).buttons().container().appendTo('#awards-list_wrapper .col-md-6:eq(0)');
            
            @if(request()->session()->get('error'))
                toastr.error("<?=request()->session()->get('error')?>");
            @endif
            @if(request()->session()->get('message'))
                toastr.success("<?=request()->session()->get('message')?>");
            @endif
        });
    </script>
@endsection