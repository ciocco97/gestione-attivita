@extends('layouts.tma')

@section('title', 'Gestione : utente')

@section('navbar')
    @include('navbar.nav')
@endsection

@section('actions')
    <div class="d-flex justify-content-end">
        <div class="btn-group" role="group">
            <a class="btn btn-outline-success" href="{{ route('user.create') }}">
                <i class="bi bi-file-earmark-person-fill me-2"></i>@lang('labels.add') @lang('labels.tech')
            </a>
        </div>
    </div>
@endsection

@section('filters')

@endsection

@section('main')
    <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-3 mt-1">
        <div class="col">
            @include('user.card.user_registry', ['user' => $admin_user, 'hide_team_and_roles' => true])
        </div>
    </div>
    <div id="accordion_parent" class="row row-cols-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-3 mt-1">
        @foreach($users as $num => $user)
            <div class="col">
                @include('user.card.user_registry')
            </div>
        @endforeach
    </div>

    <script>
        administrator_script()
    </script>

@endsection
