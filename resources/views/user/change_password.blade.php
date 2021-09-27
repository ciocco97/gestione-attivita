@extends('layouts.master')

@section('title', 'Gestione attività : Cambio password')

@section('navbar')
    @include('nav')
@endsection

@section('body')
    <div class="container mt-3 mt-lg-5 mb-3"> <!-- Form di login -->
        <div class="row">
            <div class="col-md-2 col-lg-3"></div>

            <div class="col-md-8 col-lg-6 mt-4">
                <div class="row mb-3">
                    <h5>@lang('labels.change') password</h5>
                </div>

                <div class="card p-4 pb-1">
                    <form id="change_password_form" action="{{ route('user.change.password') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="#oldPassword" class="form-label">@lang('labels.type') @lang('labels.old_password')</label>
                            <input type="password" class="form-control" id="oldPassword" name="oldPassword"
                                   placeholder="@lang('labels.old_password')" required>
                        </div>
                        <div class="mb-3">
                            <label for="#newPassword" class="form-label">@lang('labels.type') @lang('labels.new_password')</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword"
                                   placeholder="@lang('labels.new_password')" required>
                            <div id="password_validity_alert" class="alert alert-danger mt-2" role="alert">
                                @lang('text.password_validity_alert')
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="#retype_password" class="form-label">@lang('labels.retype') @lang('labels.new_password')</label>
                            <input type="password" class="form-control" id="retype_password" name="retipePassword"
                                   placeholder="@lang('labels.new_password')" required>
                            <div id="retype_password_alert" class="alert alert-danger mt-2" role="alert">
                                @lang('text.retype_password_alert')
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mx-5 mt-5">
                            <a class="btn btn-secondary justify-content-start" href="{{ $previous_url }}">
                                <i class="bi bi-arrow-bar-left me-2"></i>@lang('labels.cancel')
                            </a>
                            <button id="change_password_button" type="submit" class="btn btn-warning">
                                <i class="bi bi-key me-2"></i>@lang('labels.change')
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="col-md-2 col-lg-3"></div>
        </div>
    </div>

    <script>
        change_password_script();
    </script>

@endsection
