@extends('layouts.tmac')

@section('title', 'Gestione : attivita')

@section('navbar')
    @include('nav')
@endsection

@section('actions')
    <div class="d-flex justify-content-end">
        <div class="btn-group" role="group">
            <button class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="bi bi-funnel me-2"></i>@lang('labels.filter') @lang('labels.activity')
            </button>
            <button id="activities_change_2" data-state="2" class="btn btn-outline-success border-end-0 pe-1"
                    style="display: none;">
                <i class="bi bi-pencil me-2"></i>@lang('labels.set') @lang('labels.billed')
            </button>
            <button id="activities_change_btn" class="btn btn-outline-success rounded-end border-start-0 px-1"
                    data-bs-toggle="dropdown" style="display: none;">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu">
                <li><h6 class="dropdown-header">@lang('labels.other_state')</h6></li>
                <li>
                    <button id="activities_change_1" class="dropdown-item" data-state="1">
                        @lang('labels.set') @lang('labels.not_billed')
                    </button>
                </li>
            </ul>
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
                @include('activity.filter.bill')
                @include('activity.filter.user')
            </div>

            <div> <!-- Riga per il submit della form dei filtri -->
                <div class="d-flex justify-content-center mt-3 pt-2 px-sm-2">
                    <div class="col col-md-4 col-lg-3">
                        <div class="d-flex">
                            <button class="btn btn-outline-secondary flex-grow-1"
                                    onclick="event.preventDefault(); save_filters()">
                                @lang('labels.filter')
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
@endsection

@section('main')

    @include('pagination.search_and_num_rows')


    <div class="row mt-2"> <!-- Master table -->
        <div class="table-responsive">
            <table class="table table-bordered border-secondary text-center text-dark align-middle">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">@lang('labels.billing_state')</th>
                    <th scope="col">@lang('labels.tech_tab')</th>
                    <th scope="col">@lang('labels.date')</th>
                    <th scope="col">@lang('labels.from')</th>
                    <th scope="col">@lang('labels.description')</th>
                    <th scope="col">@lang('labels.costumer')</th>
                    <th scope="col">@lang('labels.order')</th>
                    <th scope="col">@lang('labels.billable_duration')</th>
                    <th scope="col">@lang('labels.show')</th>

                </tr>
                </thead>
                <tbody id="master_tbody">

                @foreach($activities as $activity)

                    <tr id="activity_row_{{ $activity->id }}" data-bill="{{ $activity->fatturata }}">

                        <td id="select_{{ $activity->id }}">
                            <div class="d-flex justify-content-center">
                                <input id="check_select_{{ $activity->id }}" class="form-check" type="checkbox">
                            </div>
                        </td>
                        <td id="billing_state_{{ $activity->id }}">
                            {{ $activity->descrizione_stato_fatturazione }}
                        </td>
                        <td id="technician_{{ $activity->id }}">{{ $activity->nome }}</td>
                        <td id="date_{{ $activity->id }}" class="text-nowrap">{{ $activity->data }}</td>
                        <td id="startTime_{{ $activity->id }}">{{ substr($activity->ora_inizio, 0, 5) }}</td>
                        <td class="fw-bold" id="desc_{{ $activity->id }}"
                            style="min-width: 230px" data-bs-toggle="tooltip" data-bs-placement="right"
                            title="{{ $activity->descrizione_attivita }}">
                            {{ $activity->desc_attivita }}
                        </td>
                        <td id="costumer_{{ $activity->id }}">{{ $activity->nome_cliente }}</td>
                        <td id="order_{{ $activity->id }}">{{ $activity->descrizione_commessa }}</td>

                        <td id="billable_duration_{{ $activity->id }}">
                            <div class="d-flex justify-content-center">
                                {{ substr($activity->durata_fatturabile, 0, 5) }}
                            </div>
                        </td>

                        <!--Bottone visualizza-->
                        <td>
                            <a id="show_{{ $activity->id }}" class="btn pt-0"
                               href="{{ route('activity.show', ['activity' => $activity->id]) }}">
                                <i class="bi bi-eye text-dark"></i>
                            </a>
                        </td>

                    </tr>
                @endforeach

                </tbody>

                <script>
                    administrative_activity_script();
                </script>

            </table>
        </div>
    </div>

    @include('pagination.page_selector')

@endsection
