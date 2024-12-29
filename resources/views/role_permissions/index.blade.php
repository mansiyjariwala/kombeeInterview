@extends('layouts.adminLte')

@section('title', 'Role Permission')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h2>Manage Role Permissions</h2>
            </div>

            <!-- Success and Error Messages -->
            <div id="successMessage" class="alert alert-success d-none"></div>
            <div id="errorMessages" class="alert alert-danger d-none"></div>

            <!-- Role Permission Form -->
            <form id="rolePermissionForm" class="card shadow p-4">
                @csrf
                <div id="rolesContainer">
                    @foreach ($roles as $role)
                        <div class="card mb-4">
                            <div class="card-header bg-basic text-black">
                                <h4 class="mb-0">{{ $role->name }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" 
                                                       id="role{{ $role->id }}-permission{{ $permission->id }}" 
                                                       name="roles[{{ $role->id }}][]" 
                                                       value="{{ $permission->id }}" 
                                                       @if($role->permissions->contains($permission->id)) checked @endif>
                                                <label class="form-check-label" 
                                                       for="role{{ $role->id }}-permission{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg mt-3">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#rolePermissionForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('assign.permission') }}',
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#successMessage').text(response.message).removeClass('d-none').fadeOut(5000);
                    $('#errorMessages').addClass('d-none');
                },
                error: function(response) {
                    $('#errorMessages').empty().removeClass('d-none');
                    $('#successMessage').addClass('d-none');
                    var errors = response.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#errorMessages').append('<p>' + value[0] + '</p>');
                    });
                }
            });
        });
    });
</script>
@endsection
