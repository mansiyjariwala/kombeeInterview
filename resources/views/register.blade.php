@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-black text-center">
                    <h4>Register</h4>
                </div>
                <div class="card-body">
                    <form id="registerForm">
                        <div class="mb-3 form-group">
                            <label for="firstname" class="form-label">First Name</label>
                            <input type="text" name="firstname" id="firstname" class="form-control" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" name="lastname" id="lastname" class="form-control" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number" class="form-control" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="postcode" class="form-label">Postcode</label>
                            <input type="text" name="postcode" id="postcode" class="form-control" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="state" class="form-label">State</label>
                            <select name="state" id="state" class="form-select form-control" required>
                                <option value="">Select State</option>
                                @foreach ($states as $state)                
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="city" class="form-label">City</label>
                            <select name="city" id="city" class="form-select form-control" required>
                                <option value="">Select City</option>
                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="password-confirm" class="form-label">Password Confirmation</label>
                            <input type="password" name="password_confirmation" id="password-confirm" class="form-control" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Gender</label>
                            <div>
                                <label><input type="radio" name="gender" value="Male" required> Male</label>
                                <label><input type="radio" name="gender" value="Female" required> Female</label>
                            </div>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Hobbies</label>
                            <div>
                                <label><input type="checkbox" name="hobbies[]" value="reading"> Reading</label>
                                <label><input type="checkbox" name="hobbies[]" value="traveling"> Traveling</label>
                                <label><input type="checkbox" name="hobbies[]" value="sports"> Sports</label>
                            </div>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="roles" class="form-label">Roles</label>
                            <select name="roles[]" id="roles" class="form-select form-control" multiple required>
                                <option value="" disabled>Select Role</option>
                                @foreach ($roles as $role)                
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group row">
                            <label for="files" class="col-md-4 col-form-label text-md-right">Files</label>
                            <div class="col-md-6">
                                <input id="files" type="file" class="form-control" name="files[]" multiple>
                            </div>
                        </div>

                        <div class="mb-3 form-group">
                            <button type="submit" class="btn btn-success w-100">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#roles').select2({
            placeholder: "Select roles", // Optional placeholder
            allowClear: true            // Adds a clear button
        });

        // Handle state-city dependency
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


        // Validate the form
        $('#registerForm').validate({
            rules: {
                firstname: { required: true, minlength:3, maxlength:10},
                lastname: { required: true , minlength:3, maxlength:10},
                email: { required: true, email: true },
                contact_number: { 
                    required: true,
                    digits: true,
                    minlength: 5, 
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
                password: { required: true, minlength: 8 },
                password_confirmation: { required: true, equalTo: '#password' },
                'hobbies[]': { required: true, minlength: 1 },
                'roles[]': { required: true, minlength: 1 },
                'files[]': {
                    required: true,
                },
            },
            messages: {
                firstname: { required: "Please enter your first name." },
                lastname: { required: "Please enter your last name." },
                email: { required: "Please enter your email address.", email: "Please enter a valid email address." },
                contact_number: { 
                    required: "Please enter your contact number.", 
                    digits: "Please enter only digits.", 
                    minlength: "Contact number must be at least 5 digits long.", 
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
                password: { required: "Please provide a password.", minlength: "Your password must be at least 8 characters long." },
                password_confirmation: { required: "Please confirm your password.", equalTo: "Passwords do not match." },
                'hobbies[]': { required: "Please select at least one hobby."},
                'roles[]': { required: "Please select at least one role."},
                'files[]': {
                    required: "Please upload at least one file.",
                },
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
            }
        });

        // Submit the form via AJAX
        $('#registerForm').on('submit', function(e) {
            e.preventDefault();
            const test = $('#files')[0].files
            console.log({test});
            

            if ($(this).valid()) {
                $.ajax({
                    method: "POST",
                    url: "{{ route('create.user') }}",
                    data: {
                        firstname: $('#firstname').val(),
                        lastname: $('#lastname').val(),
                        email: $('#email').val(),
                        contact_number: $('#contact_number').val(),
                        postcode: $('#postcode').val(),
                        state: $('#state').val(),
                        city: $('#city').val(),
                        password: $('#password').val(),
                        password_confirmation: $('#password-confirm').val(),
                        gender: $('input[name="gender"]:checked').val(),
                        hobbies: $('input[name="hobbies[]"]:checked').map(function() { return $(this).val(); }).get(),
                        roles: $('#roles').val()
                        // files: $('#files')[0].files
                    },
                    success: function(response) {
                        console.log({response});
                        
                        alert("You are registered successfully! , You can check your mail");
                        // window.location.href = '/login';
                    },
                    error: function(xhr) {
                        console.log("in error",xhr.responseJSON.message);
                        alert('Registration failed. ' + xhr.responseJSON.message);
                    }
                });
            }
        });
    });
</script>

@endsection