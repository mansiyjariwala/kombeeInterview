@extends('layouts.app')

@section('title', 'Login')

@section('styles')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('js/login.js') }}"></script>
@endsection

@section('content')
    <section class="container">
        <div class="login-container">
            <div class="circle circle-one"></div>
            <div class="form-container">
                <img src="{{asset('images/illustration.png')}}" alt="illustration" class="illustration" />
                <h1 class="opacity">LOGIN</h1>
                <form id="loginForm"> 
                    @csrf
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="USERNAME" />
                    <input id="password" type="password" class="form-control" name="password" autocomplete="new-password" placeholder="PASSWORD" />
                    <button class="opacity">SUBMIT</button>
                </form>
                <div class="register-forget opacity">
                    <a href="{{route('register')}}">REGISTER</a>
                </div>
            </div>
            <div class="circle circle-two"></div>
        </div>
        <div class="theme-btn-container"></div>
    </section>

    <script>
        $(document).ready(function() {
            $('#loginForm').validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 8 // Example validation rule
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email address",
                        email: "Please enter a valid email address"
                    },
                    password: {
                        required: "Please enter your password"
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
            });

            $('#loginForm').submit(function(e) {
                e.preventDefault();
                if ($(this).valid()) {
                    var formData = new FormData($('#loginForm')[0]);
                    $.ajax({
                            url: "{{ route('login.user') }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                console.log('Success Response:', response);

                                // Show a success alert
                                alert(response.message);
                                var userRole = response.user
                                console.log({userRole});
                                
                                // Store token and user in localStorage
                                // localStorage.setItem('token', JSON.stringify(response.token));
                                // localStorage.setItem('user', JSON.stringify(response.user));
                                // localStorage.setItem('userData', JSON.stringify(response.userData));

                                // // Redirect to dashboard
                                // const test = localStorage.getItem('token')
                                // console.log({test});
                                
                                window.location.href = userRole == 'Admin' ? "{{route('admin.dashboard')}}" : "{{route('dashboard')}}";
                            },
                            error: function(response) {
                                console.log("response",response);
                                
                                alert(response?.responseJSON?.message);
                            }
                        });

                    }
            });
        });
    </script>
@endsection