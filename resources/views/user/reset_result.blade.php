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
    <div class="container mt-3 mt-lg-5 mb-3">
        <div class="row">
            <div class="col-md-2 col-lg-3"></div>

            <div class="col-md-8 col-lg-6 mt-4">
                <div class="row mb-3">
                    <h5></h5>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Well done!</h4>
                            <p>Aww yeah, you successfully read this important alert message. This example text is going
                                to
                                run a bit longer so that you can see how spacing within an alert works with this kind of
                                content.</p>
                            <hr>
                            <p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice
                                and
                                tidy.</p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center pb-3">
                        <a class="btn btn-secondary" href="{{ route('user.login') }}">Torna alla home</a>
                    </div>
                </div>
            </div>

            <div class="col-md-2 col-lg-3"></div>
        </div>
    </div>

@endsection
