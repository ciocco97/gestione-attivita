@extends('layouts.tmac')

@section('title', 'Gestione : attivita')

@section('navbar')
    @include('nav')
@endsection

@section('actions')
    <div class="d-flex justify-content-end">
        <div class="btn-group" role="group">
            @include('button.filter')

            @if($current_page == $pages['TECHNICIAN'])
                <a class="btn btn-outline-primary" href="{{ route('activity.create') }}">
                    <i class="bi bi-journal-plus me-2"></i>@lang('labels.add') @lang('labels.activity')
                </a>
            @endif

            @if($current_page == $pages['MANAGER'])
                @include('activity.button.activity_mass_change', ['value' => 4])
            @elseif($current_page == $pages['ADMINISTRATIVE'])
                @include('activity.button.activity_mass_change', ['value' => 2])
            @endif
            @include('activity.button.mass_change_extend')

            @include('activity.modal_confirmation')
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
                @if($current_page == $pages['ADMINISTRATIVE'])
                    @include('activity.filter.bill')
                @else
                    @include('activity.filter.state')
                @endif
                @if($current_page == $pages['MANAGER'] || $current_page == $pages['ADMINISTRATIVE'])
                    @include('activity.filter.user')
                @endif
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
            @if($current_page == $pages['ADMINISTRATIVE'])
                @include('activity.table.administrative')
            @else
                @include('activity.table.tech')
            @endif
        </div>
    </div>

    @include('pagination.page_selector')

@endsection
