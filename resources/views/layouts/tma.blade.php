@extends('layouts.master')

@section('title')
    @yield('title')
@endsection

@section('body')
    <div class="container-fluid px-lg-5"> <!-- Corpo della pagina -->
        <div class="row mt-0 mt-md-1 mt-lg-0"> <!-- Azioni -->
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
                        <form id="master_filter_form" class="mb-0" action="{{ route('activity.filter') }}"
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
