@extends('layouts.skeleton')

@section('app')
    <section class="vh-100 bg-primary">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong rounded-4">
                        <div class="card-body p-5 text-center">
                            <form action="{{ route('login') }}" method="POST">
                                @csrf

                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-hexagon-fill text-primary mb-4" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"/>
                                </svg>

                                <h3 class="mb-5">Employee Data Master</h3>

                                <hr class="my-4">

                                <div class="form-outline mb-4">
                                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Input Your Email" />
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-outline mb-4">
                                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="current-password" autofocus placeholder="Enter Your Password" />
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <hr class="my-4">

                                <button class="btn btn-primary btn-lg w-100" type="submit">
                                    <i class="bi-box-arrow-right"></i>
                                    Log In
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
