@extends('layouts.adminLte')

@section('title', 'Create Supplier')

@section('content')
<main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h2>Add New Role</h2>
                </div>
                <form id="createForm">
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
                        <button type="submit" class="btn btn-success w-100">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#roles').select2({
            placeholder: "Select roles",
            allowClear: true
        });

        // Handle state-city dependency
        $('#state').change(function() {
            var state_id = $(this).val();
            if(state_id) {
                $.ajax({
                    url: '{{ route("admin.cities.state", ":state_id") }}'.replace(':state_id', state_id),
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
        $('#createForm').validate({
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

        
        $('#createForm').on('submit', function(e) {
            e.preventDefault();
            const test = $('#files')[0].files
            const formData = new FormData();
            
            formData.append('firstname', $('#firstname').val());
            formData.append('lastname', $('#lastname').val());
            formData.append('email', $('#email').val());
            formData.append('contact_number', $('#contact_number').val());
            formData.append('postcode', $('#postcode').val());
            formData.append('state', $('#state').val());
            formData.append('city', $('#city').val());
            formData.append('password', $('#password').val());
            formData.append('password_confirmation', $('#password-confirm').val());
            formData.append('gender', $('input[name="gender"]:checked').val());

            $('input[name="hobbies[]"]:checked').each(function() {
                formData.append('hobbies[]', $(this).val());
            });

            const roles = $('#roles').val();
            roles.forEach(function(role) {
                formData.append('roles[]', role);
            });

            const files = $('#files')[0].files;
            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }

            if ($(this).valid()) {
                $.ajax({
                    method: "POST",
                    url: "{{ route('supplier.store') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log({ response });
                        alert("You have successfully created supplier");
                    },
                    error: function(xhr) {
                        console.log("Error:", xhr.responseJSON.message);
                        alert('Registration failed. ' + xhr.responseJSON.message);
                    }
                });
            }
        });
    });
</script>
@endsection
