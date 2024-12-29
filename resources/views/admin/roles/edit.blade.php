@extends('layouts.adminLte')

@section('title', 'Edit Role')

@section('content')
<main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h2>Edit Role</h2>
                </div>
    
                <form id="editRoleForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="role_id" id="role_id" data-role-id="{{ $role->id }}">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <input type="text" class="form-control" id="description" name="description" value="{{ $role->description }}">
                    </div>
                    <!-- Add more fields as needed -->
                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<script>
$('#editRoleForm').validate({
    rules: {
            name: {
                required: true,
                maxlength:30
            },
            description: {
                required: true,
                maxlength:30
            },
        },
    errorElement: 'span',
    errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid').removeClass('is-valid');
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid').addClass('is-valid');
    },
    submitHandler: function(form) {
        var roleId = $('#role_id').data('role-id');
        $.ajax({
            url: '{{ route('role.update', ':id') }}'.replace(':id', roleId),
            type: 'PUT',
            data: $(form).serialize(),
            success: function(response) {
                console.log(response);
                window.location.href = "{{ route('admin.role') }}";
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }
});
</script>
@endsection
