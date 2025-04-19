@extends('front.layouts.app')

@section('main')

<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Register</h1>
                    <form action="{{ route('account.processRegistration') }}" method="post" name="registrationForm" id="registrationForm">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="mb-2">Name*</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Name">
                            @error('name')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div> 
                        <div class="mb-3">
                            <label for="email" class="mb-2">Email*</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Enter Email">
                            @error('email')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div> 
                        <div class="mb-3">
                            <label for="password" class="mb-2">Password*</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter Password">
                            @error('password')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div> 
                        <div class="mb-3">
                            <label for="confirm_password" class="mb-2">Confirm Password*</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Please confirm Password">
                            @error('password_confirmation')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div> 
                        <button class="btn btn-primary mt-2">Register</button>
                    </form>                    
                </div>
                <div class="mt-4 text-center">
                    <p>Have an account? <a  href="{{ route('account.login') }}">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')
{{-- <script>
    $(document).ready(function(){
        $("#registrationForm").submit(function(e){
            e.preventDefault();

            $.ajax({
                url: '{{ route("account.processRegistration") }}',
                type: 'POST',
                data: $('#registrationForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;

                    // Clear previous error messages
                    $('input').removeClass('is-invalid');
                    $('p.invalid-feedback').html('');

                    $.each(errors, function(field, messages) {
                        var inputField = $("#" + field);
                        inputField.addClass('is-invalid');
                        inputField.siblings('p.invalid-feedback').html(messages[0]); // Show the first error message
                    });
                }
            });
        });
    });
</script> --}}
@endsection