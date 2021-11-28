@extends('layouts.master')

@section('title', 'Gestione attività : Autenticazione')

@section('navbar')
    @include('navbar.nav')
@endsection

@section('body')
    <div class="container mt-3 mt-lg-5 mb-3"> <!-- Form di login -->
        <div class="row">
            <div class="col-md-2 col-lg-3"></div>

            <div class="col-md-8 col-lg-6 mt-4">
                <div class="row mb-3">
                    <h5>Login</h5>
                </div>

                <div class="card p-4 pb-1">
                    <form id="login_form" action="{{ route('user.authentication') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="#inputEmail" class="form-label">@lang('labels.email')</label>
                            <input type="email" class="form-control" id="inputEmail" name="email"
                                   placeholder="@lang('labels.insert') email" value="{{ $email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="#inputPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="inputPassword" name="password"
                                   placeholder="Password" required>
                        </div>
                        <div id="login_error" class="alert alert-danger text-center" style="display: none;">
                            @lang('text.credential_check_error')
                        </div>
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe"
                                   @if( $email != '' )
                                   checked
                                @endif
                            >
                            <label class="form-check-label" for="rememberMe">@lang('labels.remember')</label>
                        </div>

                        <button id="login_button" type="submit" class="btn btn-primary">
                            <div id="wait_check_credentials"
                                 class="spinner-border spinner-border-sm text-success"
                                 role="status"
                                 style="display: none;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <i id="symbol_check_credentials" class="bi bi-box-arrow-in-right me-2"></i>Login
                        </button>


                        <div class="d-flex justify-content-center mt-3">
                            <div class="flex-fill border-bottom mx-5 mb-2"></div>
                            <div class="text-secondary">@lang('labels.or')</div>
                            <div class="flex-fill border-bottom mx-5 mb-2"></div>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <div>
                                <a class="text-decoration-none link-primary" href="{{ route('user.reset.password') }}">
                                    @lang('labels.forgot_password')
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-2 col-lg-3"></div>
        </div>
    </div>

    <script>
        login_script()
    </script>

@endsection
