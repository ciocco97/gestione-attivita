@extends('layouts.tmac')

@section('title', 'Gestione : attivita')

@section('navbar')
    @include('nav')
@endsection

@section('actions')
    <div class="d-flex justify-content-end">
        <div class="btn-group" role="group">
            @include('button.filter')
            @include('activity.button.activity_mass_change', ['value' => 2])
            @include('activity.button.mass_change_extend')
        </div>
    </div>
@endsection

@section('filters')
    <form id="master_filter_form" class="mb-0" action="{{ route('activity.filter') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-7"> <!-- Prima coppia/terna di filtri -->
                @include('activity.filter.period')
                @include('activity.filter.date')
            </div>

            <div class="col-md-5 ps-lg-5"> <!-- Seconda coppia di filtri -->
                @include('activity.filter.costumer')
                @include('activity.filter.bill')
                @include('activity.filter.user')
            </div>

            <div> <!-- Riga per il submit della form dei filtri -->
                @include('activity.filter.submit')
            </div>

        </div>
    </form>
@endsection

@section('main')

    @include('pagination.search_and_num_rows')


    <div class="row mt-2"> <!-- Master table -->
        <div class="table-responsive">

            @include('activity.table.tech')
        </div>
    </div>

    @include('pagination.page_selector')

@endsection
