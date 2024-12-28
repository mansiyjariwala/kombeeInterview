
@extends('layouts.adminLte')

@section('title', 'Dashboard')

@section('content')
    <div class="app-wrapper">
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <!-- <img
                  src="../../dist/assets/img/user2-160x160.jpg"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                /> -->
                <span class="d-none d-md-inline">{{$userData->firstname}} {{$userData->lastname}}</span>
                @foreach ($userData->roles as $roles)
                  <span class="d-none d-md-inline">{{$roles->name}}</span>
                @endforeach
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <li class="user-footer">
                  <a href="{{route('logout')}}" class="btn btn-default">Sign out</a>
                </li>
                <!--end::Menu Footer-->
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>

          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
            <span class="brand-text fw-light">AdminLTE 4</span>
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="menu"
              data-accordion="false"
            >
              <li class="nav-item">
                <a href="#" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
              </li>
              
            </ul>
          </nav>
        </div>
      </aside>
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <table class="table table-bordered data-table" id="users-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <!-- <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Contact Number</th>
                  <th>State</th>
                  <th>City</th> -->
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </main>
      <footer class="app-footer">
        
      </footer>
    </div>
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
            // headers: {
            //     'Authorization': 'Bearer ' + token
            // },
            type: 'GET',
            url: '{{ route('users.data') }}',
            dataType: 'json',
        },
        columns: [
            { data: 'id', name: 'id' },
              // { data: 'firstname', name: 'firstname' },
              // { data: 'lastname', name: 'lastname' },
              // { data: 'email', name: 'email' },
              // { data: 'contact_number', name: 'contact_number' },
              // { data: 'state', name: 'state' },
              // { data: 'city', name: 'city' },
            // { data: 'action', name: 'action' },
        ],
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf'
        ]
    });

});
</script>
@endsection


