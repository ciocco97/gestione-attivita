@extends('layouts.tma')

@section('title', 'Gestione : attivita')

@section('navbar')
    @include('navbar.nav')
@endsection

@section('actions')
    <div class="d-flex justify-content-end">
        <div class="btn-group" role="group">
            @include('shared.button_filter')

            @if($current_page == $PAGES['TECHNICIAN'])
                <a class="btn btn-outline-primary" href="{{ route('activity.create') }}">
                    <i class="bi bi-journal-plus me-2"></i>@lang('labels.add') @lang('labels.activity')
                </a>
            @endif

            @if($current_page == $PAGES['MANAGER'])
                @include('shared.button_activity_mass_change', ['value' => $ACTIVITY_STATES['APPROVED']])
            @elseif($current_page == $PAGES['ADMINISTRATIVE'])
                @include('shared.button_activity_mass_change', ['value' => $ACTIVITY_ACCOUNTED_STATES['ACCOUNTED']])
            @endif
            @include('shared.button_activity_mass_change_extend')

            {{--@include('activity.modal_confirmation')--}}
        </div>
    </div>
@endsection

@section('filters')
    @csrf
    <div class="row">
        <div class="col-md-7"> <!-- Prima coppia/terna di filtri -->
            @include('shared.filter.period')
            @include('shared.filter.date')
        </div>

        <div class="col-md-5 ps-lg-5"> <!-- Seconda coppia di filtri -->
            @include('shared.filter.costumer')
            @if($current_page == $PAGES['ADMINISTRATIVE'])
                @include('shared.filter.bill_or_accounted_state')
            @else
                @include('shared.filter.state')
            @endif
            @if($current_page == $PAGES['MANAGER'] || $current_page == $PAGES['ADMINISTRATIVE'])
                @include('shared.filter.user')
            @endif
        </div>

        <div> <!-- Riga per il submit della form dei filtri -->
            @include('shared.filter.submit')
        </div>

    </div>
@endsection

@section('main')

    @include('shared.pagination.search_and_num_rows')


    <div class="row mt-2"> <!-- Master table -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered border-secondary text-center text-dark align-middle">
                @if($current_page == $PAGES['ADMINISTRATIVE'])
                    @include('activity.table.administrative')
                @else
                    @include('activity.table.tech')
                @endif
            </table>
            @include('shared.alert_no_element', ['element_list' => $activities])
        </div>
    </div>

    @include('shared.pagination.page_selector')

@endsection
