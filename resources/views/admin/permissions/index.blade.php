
@extends('layouts.adminLte')

@section('title', 'Permission')

@section('content')

      <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h2>Permission List</h2>
                    <a href="{{route('permission.create')}}" class="btn btn-primary">
                        Create New Permission
                    </a>
                </div>
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </main>
@endsection

@section('script')
<script>
$(document).ready(function() {
    var token = `{{ $token }}`
    
    // var token = localStorage.getItem('token');
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            headers: {
                'Authorization': 'Bearer ' + token
            },
            type: 'GET',
            url: "{{ route('permission.data') }}",
            dataType: 'json',
        },
        columns: [
              { data: 'id', name: 'id' },
              { data: 'name', name: 'name' },
              { data: 'description', name: 'description' },
              { data: 'action', name: 'action',orderable: false},
        ],
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf'
        ]
    });

    $(document).on('click','.delete-permission', function() {
        var permissionId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    url: "{{url('api/admin/permission/destroy/')}}" + '/' + permissionId,
                    success: function (data) {
                        table.ajax.reload();
                    }         
                });
            }
        });
    });

});
</script>
@endsection


