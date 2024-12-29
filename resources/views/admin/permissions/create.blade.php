@extends('layouts.adminLte')

@section('title', 'Create Permission')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h2>Add New Permission</h2>
                </div>
                <form id="createPermissionForm">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <input type="text" class="form-control" id="description" name="description">
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('script')
<script>
$(document).ready(function() {
    var token = `{{ $token }}`

    $('#createPermissionForm').validate({
        rules: {
            name: {
                required: true,
                maxlength:30
            },
            description: {
                required: true,
                maxlength:40
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
            event.preventDefault();
            $.ajax({
                url: "{{ route('permission.store') }}",
                type: 'POST',
                data: $(form).serialize(),
                success: function(response) {
                    window.location.href = "{{ route('admin.permission') }}";
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }
    });
});
</script>
@endsection
