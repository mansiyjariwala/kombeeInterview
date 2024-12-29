
@extends('layouts.adminLte')

@section('title', 'Customers')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
          <div class="mb-3 d-flex justify-content-between align-items-center">
                <h2>Customer List</h2>
                <a href="#" class="btn btn-primary">
                    Create New Customer
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
            url: '{{ route('customer.data') }}',
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

});
</script>
@endsection
