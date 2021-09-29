@extends('layouts.master')

@section('title', 'Gestione attività : Reset password')

@section('navbar')
    <nav class="navbar navbar-light"> <!-- Intestazione -->
        <div class="container">
            <a class="navbar-brand" href="">@lang('labels.main_title')</a>
            <span>
                <a class="inline text-decoration-none text-dark me-2"
                   href="{{ route('lang.change', ['lang' => 'it']) }}">
                    <i class="bi bi-flag me-2"></i>it
                </a>
                <a class="inline text-decoration-none text-dark" href="{{ route('lang.change', ['lang' => 'en']) }}">
                    <i class="bi bi-flag-fill me-2"></i>en
                </a>

            </span>
        </div>
    </nav>
@endsection

@section('body')
    <div class="container mt-3 mt-lg-5 mb-3"> <!-- Form inserisci email -->
        <div class="row">
            <div class="col-md-2 col-lg-3"></div>

            <div class="col-md-8 col-lg-6 mt-4">
                <div class="row mb-3">
                    <h5>Reset password</h5>
                </div>

                <div class="card p-4 pb-1">
                    <form id="change_password_form" action="{{ route('user.reset.password') }}" method="post">
                        @csrf
                        <ul>
                            <div class="mb-3">
                                <label for="#email" class="form-label">
                                    @lang('labels.type') @lang('labels.yours') @lang('labels.email')
                                </label>
                                @if(!isset($email))
                                    <input type="email" class="form-control" id="email" name="email"
                                           placeholder="@lang('labels.email')" required>
                                @else
                                    <input type="email" class="form-control border-success" id="email" name="email"
                                           value="{{ $email }}" required readonly>
                                @endif
                            </div>

                            @if(isset($email))
                                <li class="list-group-item text-center mb-3 pb-0">
                                    <p class="text-muted">@lang('text.token_description')</p>
                                </li>
                                <div class="mb-3">
                                    <label for="#token" class="form-label">
                                        @lang('labels.insert') token</label>
                                    @if(!isset($token))
                                        <input type="password" class="form-control" id="token" name="token"
                                               placeholder="Token" required>
                                    @else
                                        <input type="password" class="form-control border-success" id="token"
                                               name="token"
                                               value="{{ $token }}" required readonly>
                                </div>
                                @if(!isset($success))
                                    <div class="mb-3">
                                        <label for="#newPassword"
                                               class="form-label">@lang('labels.type') @lang('labels.new_password')</label>
                                        <input type="password" class="form-control" id="newPassword"
                                               name="newPassword"
                                               placeholder="@lang('labels.new_password')" required>
                                        <div id="password_validity_alert" class="alert alert-danger mt-2"
                                             role="alert" style="display: none;">
                                            @lang('text.password_validity_alert')
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="#retype_password"
                                               class="form-label">@lang('labels.retype') @lang('labels.new_password')</label>
                                        <input type="password" class="form-control" id="retype_password"
                                               name="retipePassword"
                                               placeholder="@lang('labels.new_password')" required>
                                        <div id="retype_password_alert" class="alert alert-danger mt-2"
                                             role="alert" style="display: none;">
                                            @lang('text.retype_password_alert')
                                        </div>
                                    </div>

                                    <script>change_password_script()</script>

                                @else
                                    @if($success)
                                        <div class="alert alert-success d-flex align-items-center" role="alert">
                                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                                 aria-label="Success:">
                                                <use xlink:href="#check-circle-fill"/>
                                            </svg>
                                            <div>
                                                @lang('text.password_success')
                                            </div>
                                        </div>
                                    @else

                                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                                 aria-label="Danger:">
                                                <use xlink:href="#exclamation-triangle-fill"/>
                                            </svg>
                                            <div>
                                                @lang('text.token_alert')
                                            </div>
                                        </div>
                                    @endif

                                @endif
                            @endif

                            @endif

                            @if(!isset($success))
                            <div class="d-flex justify-content-between mx-5 mt-4">
                                <a class="btn btn-secondary justify-content-start" href="{{ route('user.login') }}">
                                    <i class="bi bi-arrow-bar-left me-2"></i>@lang('labels.cancel')
                                </a>
                                @if(!isset($token))
                                    <button id="change_password_button" type="submit" class="btn btn-primary">
                                        <i class="bi bi-key me-2"></i>@lang('labels.go_on')
                                    </button>
                                @else
                                    <button id="change_password_button" type="submit" class="btn btn-warning">
                                        <i class="bi bi-key me-2"></i>@lang('labels.go_on')
                                    </button>
                                @endif
                            </div>
                            @else
                                <div class="d-flex justify-content-start mt-3 pt-3 border-top">
                                    <a class="btn btn-secondary ms-2" href="{{ route('user.login') }}">
                                        <i class="bi bi-box-arrow-left me-2"></i>
                                        @lang('labels.back_to_login')
                                    </a>
                                </div>

                            @endif

                        </ul>
                    </form>
                </div>
            </div>

            <div class="col-md-2 col-lg-3"></div>
        </div>
    </div>

@endsection
