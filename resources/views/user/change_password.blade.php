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
                    <form action="{{ route('user.authentication') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="#oldPassword" class="form-label">@lang('labels.old') password</label>
                            <input type="password" class="form-control" id="oldPassword" name="oldPassword"
                                   placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="#newPassword" class="form-label">@lang('labels.new') password</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword"
                                   placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="#repeatPassword" class="form-label">@lang('labels.repeat') password</label>
                            <input type="password" class="form-control" id="repeatassword" name="repeatPassword"
                                   placeholder="Password" required>
                        </div>
                        <div class="d-flex justify-content-between mx-5 mt-5">
                            <a class="btn btn-secondary justify-content-start" href="{{ $previous_url }}">
                                <i class="bi bi-arrow-bar-left me-2"></i>@lang('labels.cancel')
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-key me-2"></i>@lang('labels.change')
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="col-md-2 col-lg-3"></div>
        </div>
    </div>

@endsection
