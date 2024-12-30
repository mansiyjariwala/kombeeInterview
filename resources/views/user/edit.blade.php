@extends('layouts.adminLte')

@section('title', 'Edit User')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h2>Edit User</h2>
                </div>
                <form id="editUserForm">
                <input type="hidden" name="user_id" id="user_id" data-user-id="{{ $user->id }}">
                    <div class="mb-3 form-group">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" value="{{ $user->firstname }}" required>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" value="{{ $user->lastname }}" required>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" id="contact_number" class="form-control" value="{{ $user->contact_number }}" required>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="postcode" class="form-label">Postcode</label>
                        <input type="text" name="postcode" id="postcode" class="form-control" value="{{ $user->postcode}}" required>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="state" class="form-label">State</label>
                        <select name="state" id="state" class="form-select form-control" required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" {{ $state->id == $user->state_id ? 'selected' : '' }}>{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="city" class="form-label">City</label>
                        <select name="city" id="city" class="form-select form-control" required>
                            <option value="">Select City</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ $city->id == $user->city_id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 form-group">
                        <label class="form-label">Gender</label>
                        <div>
                            <label><input type="radio" name="gender" value="Male" required {{$user->gender == 'Male' ? 'checked' : ''}}> Male</label>
                            <label><input type="radio" name="gender" value="Female" required {{$user->gender == 'Female' ? 'checked' : ''}}> Female</label>
                        </div>
                    </div>
                    <div class="mb-3 form-group">
                        <label class="form-label">Hobbies</label>
                        <div>
                            <label><input type="checkbox" name="hobbies[]" value="reading" {{ in_array('reading', json_decode($user->hobbies, true)) ? 'checked' : '' }}> Reading</label>
                            <label><input type="checkbox" name="hobbies[]" value="traveling" {{ in_array('traveling', json_decode($user->hobbies, true)) ? 'checked' : '' }}> Traveling</label>
                            <label><input type="checkbox" name="hobbies[]" value="sports" {{ in_array('sports', json_decode($user->hobbies, true)) ? 'checked' : '' }}> Sports</label>
                        </div>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="roles" class="form-label">Roles</label>
                        <select name="roles[]" id="roles" class="form-select form-control" multiple required>
                            <option value="" disabled>Select Role</option>
                            @foreach ($roles as $role)                
                                <option value="{{ $role->id }}" 
                                    {{ $user->roles->pluck('id')->contains($role->id) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group row">
                        <label for="files" class="col-md-4 col-form-label text-md-right">Files</label>
                        <div class="col-md-6">
                            <input id="files" type="file" class="form-control" name="files[]" multiple>
                        </div>
                    </div>

                    @if($user->files)
                    <div class="row" style="align-items: center;">   
                        @foreach($user->files as $file)
                            <div class="col-md-6" >
                                <img src="{{ asset('files/' . $file->file_name) }}" alt="{{ $file->file_name }}" width="100">
                            </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mb-3 form-group">
                        <button type="submit" class="btn btn-success w-100">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<script>
$('#editUserForm').validate({
    rules: 
        {
            firstname: { required: true, minlength:3, maxlength:10},
            lastname: { required: true , minlength:3, maxlength:10},
            email: { required: true, email: true },
            contact_number: { 
                required: true,
                digits: true,
                minlength: 10, 
                maxlength: 12
            },
            postcode: {
                required: true, 
                digits: true ,
                minlength: 5, 
                maxlength: 10
            },
            state: { required: true },
            city: { required: true },
            // password: { required: true, minlength: 8 },
            // password_confirmation: { required: true, equalTo: '#password' },
            'hobbies[]': { required: true, minlength: 1 },
            'roles[]': { required: true, minlength: 1 },
            // 'files[]': {
            //     required: true,
            // },
        },
        messages: {
            firstname: { required: "Please enter your first name." },
            lastname: { required: "Please enter your last name." },
            email: { required: "Please enter your email address.", email: "Please enter a valid email address." },
            contact_number: { 
                required: "Please enter your contact number.", 
                digits: "Please enter only digits.", 
                minlength: "Contact number must be at least 10 digits long.", 
                maxlength: "Contact number cannot exceed 12 digits." 
            },
            postcode: { 
                required: "Please enter your postcode.", 
                digits: "Please enter only digits.",
                minlength: "Postcode must be at least 5 digits long.", 
                maxlength: "Postcode cannot exceed 10 digits." 
            },
            state: { required: "Please select your state." },
            city: { required: "Please select your city." },
            // password: { required: "Please provide a password.", minlength: "Your password must be at least 8 characters long." },
            // password_confirmation: { required: "Please confirm your password.", equalTo: "Passwords do not match." },
            'hobbies[]': { required: "Please select at least one hobby."},
            'roles[]': { required: "Please select at least one role."},
            // 'files[]': {
            //     required: "Please upload at least one file.",
            // },
            gender: {
                required: "Please select your gender."
            }
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
        var userId = $('#user_id').data('user-id');
        console.log({userId});
        $.ajax({
            url: '{{ route('user.update', ':id') }}'.replace(':id', userId),
            type: 'PUT',
            data: $(form).serialize(),
            success: function(response) {
                // Handle success response
                console.log(response);
                window.location.href = "{{ route('dashboard')}}";
                // Optionally, redirect or update DataTable
            },
            error: function(xhr) {
                // Handle error response
                console.error(xhr.responseText);
            }
        });
    }
});

$(document).ready(function() {
    $('#roles').select2({
        placeholder: "Select roles",
        allowClear: true
    });
    $('#state').change(function() {
        var state_id = $(this).val();
        if(state_id) {
            $.ajax({
                url: '{{ route("cities.state", ":state_id") }}'.replace(':state_id', state_id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#city').empty().append('<option value="">Select City</option>');
                    $.each(data, function(index, city) {
                        $('#city').append('<option value="'+city.id+'">'+city.name+'</option>');
                    });
                    $('#city').prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching cities:', error);
                }
            });
        } else {
            $('#city').empty().prop('disabled', true);
        }
    });
});
</script>
@endsection
