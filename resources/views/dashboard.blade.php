
@extends('layouts.adminLte')

@section('title', 'Dashboard')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
          <div class="mb-3 d-flex justify-content-between align-items-center">
                <h2>User List</h2>                
                <a href="{{route('user.create')}}" class="btn btn-primary">
                    Create New User
                </a>
            </div>
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Contact Number</th>
                  <th>State</th>
                  <th>City</th>
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
            url: "{{ route('users.data') }}",
            dataType: 'json',
        },
        columns: [
              { data: 'id', name: 'id' },
              { data: 'firstname', name: 'firstname' },
              { data: 'lastname', name: 'lastname' },
              { data: 'email', name: 'email' },
              { data: 'contact_number', name: 'contact_number',orderable: false },
              { data: 'state', name: 'state',orderable: true },
              { data: 'city', name: 'city', orderable: true },
              { data: 'action', name: 'action',orderable: false},
        ],
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf'
        ]
    });

    $(document).on('click','.delete-user', function() {
        var userId = $(this).data('id');
        console.log({userId});
        
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
                    url: "{{url('api/destroy')}}" + '/' + userId,
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


