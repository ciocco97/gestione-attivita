@extends('layouts.master')

@section('title')
    @yield('title')
@endsection

@section('body')
    <div class="container-fluid px-lg-5"> <!-- Corpo della pagina -->
        {{--        <div class="row mt-0 mt-md-1 mt-lg-0"> <!-- Azioni -->--}}
        @if($current_page == $PAGES['TECHNICIAN'] || $current_page == $PAGES['MANAGER'])
        <div class="d-flex justify-content-between mt-0 mt-md-1 mt-lg-0">
             @include('activity.modal_legenda')
             <i id="call_legenda" class="bi bi-info-circle-fill text-secondary mt-2" style="font-size:20px; cursor: pointer"></i>
            @else
        <div class="row mt-0 mt-md-1 mt-lg-0">
            @endif
            <div class="d-flex justify-content-end">
                <div class="btn-group" role="group">
                    @yield('actions')
                </div>
            </div>
        </div>

        <div class="collapse" id="filterCollapse"> <!-- Filtri -->
            <div class="card mt-2">
                <div class="card-body mb-0">
                    <div class="container-fluid">
                        <form id="master_filter_form" class="mb-0"
                              @if($current_page == $PAGES['COMMERCIAL'])
                              action="{{ route('costumer.filter') }}"
                              @else
                              action="{{ route('activity.filter') }}"
                              @endif
                              method="post">
                            @yield('filters')
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @yield('main')

    </div>

@endsection
