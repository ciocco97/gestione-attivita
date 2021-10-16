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
            <button id="activities_change_1" data-state="1" class="btn btn-outline-success border-end-0 pe-1"
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
                    <button id="activities_change_0" class="dropdown-item" data-state="0">
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

                <div class="row mb-1"> <!-- Riga selezione periodo -->
                    <label class="col-md-4 col-form-label pt-0 pt-md-2" for="#period">@lang('labels.period')</label>
                    <div class="col-md-8 col-lg-5">
                        <div class="d-flex">
                            <select value="" class="form-select" id="master_period_filter" name="period">
                                <option value="" hidden>@lang('labels.select') @lang('labels.period')</option>
                                <option value="1">@lang('labels.last_week')</option>
                                <option value="2">@lang('labels.last_two_weeks')</option>
                                <option value="3">@lang('labels.current_month')</option>
                                <option value="4" selected>@lang('labels.last_month')</option>
                                <option value="5">@lang('labels.all')</option>
                            </select>
                            <button type="button" class="btn"
                                    onclick='$("#master_period_filter").val(""); $("#master_period_filter").change()'>
                                <i class="bi bi-x-square"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row mb-1"> <!-- Riga inserimento data -->
                    <label class="col-md-4 col-form-label pt-0 pt-md-2"
                           for="#master_date_filter">@lang('labels.date')</label>
                    <div class="col-md-8">
                        <div class="d-flex">
                            <input type="date" class="form-control" id="master_date_filter" name="date">
                            <button type="button" class="btn"
                                    onclick='$("#master_date_filter").val(""); $("#master_date_filter").change()'>
                                <i class="bi bi-x-square"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 ps-lg-5"> <!-- Seconda coppia di filtri -->

                <div class="row mb-1"> <!-- Riga selezione cliente -->
                    <label class="col-md-4 col-form-label ps-md-3 ps-lg-5" for="#master_costumer_filter">
                        @lang('labels.costumer')
                    </label>
                    <div class="col-md-8">
                        <div class="d-flex">
                            <select class="form-select" id="master_costumer_filter" name="costumer">
                                <option value="" selected hidden>
                                    @lang('labels.select') @lang('labels.costumer')
                                </option>
                                @foreach($costumers as $costumer)
                                    <option value="{{ $costumer->id }}">{{ $costumer->nome }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn" onclick='$("#master_costumer_filter").val("");'>
                                <i class="bi bi-x-square"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row mb-1"> <!-- Riga selezione stato fatturazione -->
                    <label class="col-md-4 col-form-label ps-md-3 ps-lg-5" for="#master_billing_state_filter">
                        @lang('labels.billing_state')
                    </label>
                    <div class="col-md-8">
                        <div class="d-flex">
                            <select class="form-select" id="master_billing_state_filter" name="billing-state">
                                <option value="" selected
                                        hidden>@lang('labels.select') @lang('labels.billing_state')</option>
                                @foreach($billing_states as $state)
                                    <option
                                        value="{{ $state->id }}">{{ $state->descrizione_stato_fatturazione }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn" onclick='$("#master_billing_state_filter").val("");'>
                                <i class="bi bi-x-square"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row"> <!-- Riga selezione utente -->
                    <label class="col-md-4 col-form-label ps-md-3 ps-lg-5" for="#master_user_filter">
                        @lang('labels.tech_tab')
                    </label>
                    <div class="col-md-8">
                        <div class="d-flex">
                            <select class="form-select" id="master_user_filter" name="user">
                                <option value="" selected
                                        hidden>@lang('labels.select') @lang('labels.user')</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nome }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn" onclick='$("#master_user_filter").val(-2);'>
                                <i class="bi bi-x-square"></i>
                            </button>
                        </div>
                    </div>
                </div>
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
                    <th scope="col">@lang('labels.tech_tab')</th>
                    <th scope="col">@lang('labels.date')</th>
                    <th scope="col">@lang('labels.from')</th>
                    <th scope="col">@lang('labels.description')</th>
                    <th scope="col">@lang('labels.costumer')</th>
                    <th scope="col">@lang('labels.order')</th>
                    <th scope="col">@lang('labels.billable_duration')</th>
                    <th scope="col">@lang('labels.billing_state')</th>
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
                                {{ substr($activity->ora_inizio, 0, 5) }}
                            </div>
                        </td>

                        <td id="billing_state_{{ $activity->id }}">
                            {{ $activity->descrizione_stato_fatturazione }}
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
