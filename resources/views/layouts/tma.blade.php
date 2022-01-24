@extends('layouts.master')

@section('title')
    @yield('title')
@endsection

@section('body')
    <div class="container-fluid px-lg-5"> <!-- Corpo della pagina -->
        <div class="d-flex justify-content-between mt-0 mt-md-1 mt-lg-0"> <!-- Azioni -->
            <i id="prova_legenda" class="bi bi-info-circle mt-2"
               style="font-size:24px;">
            </i>
            @include('activity.modal_legenda')
            <script>
                $("#prova_legenda").on("click", function () {
                    $("#modal_legenda").modal("toggle")
                })
            </script>
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
