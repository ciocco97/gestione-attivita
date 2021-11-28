@extends('layouts.tma')

@section('title', 'Gestione : utente')

@section('navbar')
    @include('navbar.nav')
@endsection

@section('actions')
    <div class="d-flex justify-content-end">
        <div class="btn-group" role="group">
            <a class="btn btn-outline-success" href="{{ route('user.create') }}">
                <i class="bi bi-emoji-smile me-2"></i>@lang('labels.add') @lang('labels.tech')
            </a>
        </div>
    </div>
@endsection

@section('filters')

@endsection

@section('main')
    <div id="users" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 mt-1">
        @foreach($users as $num => $user)
            <div class="col">
                @include('user.card.user_registry')
            </div>
        @endforeach
    </div>

    <script>

    </script>

@endsection
