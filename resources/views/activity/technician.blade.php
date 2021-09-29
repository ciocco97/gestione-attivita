@extends('layouts.tmac')

@section('title', 'Gestione : tecnico')

@section('navbar')
    @include('nav')
@endsection

@section('actions')
    <div class="d-flex justify-content-end">
        <div class="btn-group" role="group">
            <button class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="bi bi-funnel me-2"></i>@lang('labels.filter') @lang('labels.activity')
            </button>
            @if($team == null)
            <a class="btn btn-outline-primary" href="{{ route('activity.create') }}">
                <i class="bi bi-journal-plus me-2"></i>@lang('labels.add') @lang('labels.activity')
            </a>
            @endif
            @if($team != null) {{-- Manager --}}
            <button id="activities_change_4" data-state="4" class="btn btn-outline-success border-end-0 pe-1"
                    style="display: none;">
                <i class="bi bi-pencil me-2"></i>@lang('labels.approve')
            </button>
            @endif
            <button id="activities_change_btn" class="btn btn-outline-success rounded-end border-start-0 px-1"
                    data-bs-toggle="dropdown" style="display: none;">
                @if($team == null) {{-- Technician --}}
                    @lang('labels.change') @lang('labels.state')
                @endif
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu">
                @if($team != null)
                <li><h6 class="dropdown-header">@lang('labels.other_states')</h6></li>
                @else
                    <li><h6 class="dropdown-header">@lang('labels.states')</h6></li>
                @endif
                @foreach($states as $state)
                    @if($state->id != 4)
                        <li>
                            <button id="activities_change_{{ $state->id }}" class="dropdown-item"
                                    data-state="{{ $state->id }}">
                                @lang('labels.set') {{ $state->descrizione_stato_attivita }}
                            </button>
                        </li>
                    @endif
                @endforeach
            </ul>
            @include('activity.modal_confirmation')
        </div>
    </div>
    {{--    @endif--}}
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
                                <option value="1" selected>@lang('labels.last_week')</option>
                                <option value="2">@lang('labels.last_two_weeks')</option>
                                <option value="3">@lang('labels.current_month')</option>
                                <option value="4">@lang('labels.last_month')</option>
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
                <div class="row mb-1"> <!-- Riga selezione stato -->
                    <label class="col-md-4 col-form-label ps-md-3 ps-lg-5" for="#master_state_filter">
                        @lang('labels.state')
                    </label>
                    <div class="col-md-8">
                        <div class="d-flex">
                            <select class="form-select" id="master_state_filter" name="state">
                                <option value="" selected hidden>@lang('labels.select') @lang('labels.state')</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->descrizione_stato_attivita }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn" onclick='$("#master_state_filter").val("");'>
                                <i class="bi bi-x-square"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @if($team != null) {{-- Manager --}}
                <div class="row"> <!-- Riga selezione utente -->
                    <label class="col-md-4 col-form-label ps-md-3 ps-lg-5" for="#master_user_filter">
                        @lang('labels.tech_tab')
                    </label>
                    <div class="col-md-8">
                        <div class="d-flex">
                            <select class="form-select" id="master_user_filter" name="user">
                                <option value=-2 selected
                                        hidden>@lang('labels.select') @lang('labels.tech_tab')</option>
                                @foreach($team as $user)
                                    <option value="{{ $user->id }}">{{ $user->nome }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn" onclick='$("#master_user_filter").val(-2);'>
                                <i class="bi bi-x-square"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
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

@section('table_head')
    <thead>
    <tr>
        <th scope="col"></th>
        @if ($team != null) {{-- Manager --}}
        <th scope="col">@lang('labels.tech_tab')</th>
        @endif
        <th scope="col">@lang('labels.description')</th>
        <th scope="col">@lang('labels.date')</th>
        <th scope="col">@lang('labels.costumer')</th>
        <th scope="col">@lang('labels.order')</th>
        {{--        <th scope="col">@lang('labels.from')</th>--}}
        {{--        <th scope="col">@lang('labels.to')</th>--}}
        <th scope="col">@lang('labels.duration')</th>
        <th scope="col">@lang('labels.state')</th>
        <th scope="col">@lang('labels.report')</th>
        <th scope="col">@lang('labels.show')</th>
        <th scope="col">@lang('labels.edit')</th>
        <th scope="col">@lang('labels.delete')</th>

    </tr>
    </thead>
@endsection


@section('table_body')
    <tbody id="master_tbody">

    @foreach($activities as $activity)

        <tr>
            <td name="id" style="display: none">{{ $activity->id }}</td>
            <!-- Serve a javascrit per il get dell'activity ID -->
            <td id="select_{{ $activity->id }}">
                <div class="d-flex justify-content-center">
                    <input class="form-check" type="checkbox">
                </div>
            </td>
            @if($team != null) {{-- Manager --}}
            <td id="technician_{{ $activity->id }}">{{ $activity->nome }}</td>
            @endif
            <td class="fw-bold" id="desc_{{ $activity->id }}"
                style="min-width: 230px">{{ $activity->descrizione_attivita }}</td>
            <td id="date_{{ $activity->id }}" class="text-nowrap">{{ $activity->data }}</td>
            <td id="costumer_{{ $activity->id }}">{{ $activity->nome_cliente }}</td>
            <td id="order_{{ $activity->id }}">{{ $activity->descrizione_commessa }}</td>
            {{--            <td id="startTime_{{ $activity->id }}">{{ substr($activity->ora_inizio, 0, 5) }}</td>--}}
            {{--            <td id="endTime_{{ $activity->id }}">{{ substr($activity->ora_fine, 0, 5) }}</td>--}}
            <td id="duration_{{ $activity->id }}">{{ substr($activity->durata, 0, 5) }}</td>
            <td id="state_{{ $activity->id }}">{{ $activity->descrizione_stato_attivita }}</td>

            <!-- Bottone rapportino -->
            <td>
                @if($activity->rapportino_cliente && $activity->rapportino_commessa)
                    <button id="report_{{ $activity->id }}" class="btn pt-0">
                        @if($activity->rapportino_attivita)
                            <i class="bi bi-clipboard-check text-success"></i>
                        @else
                            <i class="bi bi-clipboard text-primary"></i>
                        @endif
                    </button>
                    <div id="wait_{{ $activity->id }}" class="spinner-border spinner-border-sm text-success"
                         role="status"
                         style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                @else
                    <a id="report_{{ $activity->id }}" class="btn pt-0 disabled">
                        <i class="bi bi-clipboard-x text-danger"></i>
                    </a>
                @endif
            </td>

            <!--Bottone visualizza-->
            <td>
                <a id="show_{{ $activity->id }}" class="btn pt-0"
                   href="{{ route('activity.show', ['activity' => $activity->id]) }}">
                    <i class="bi bi-eye text-dark"></i>
                </a>
            </td>

            <!--Bottone modifica-->
            <td>
                <a id="edit_{{ $activity->id }}" class="btn pt-0"
                   href="{{ route('activity.edit', ['activity' => $activity->id]) }}">
                    <i class="bi bi-pencil text-warning"></i>
                </a>
            </td>

            <!--Bottone elimina-->
            <td>
                <a id="delete_{{ $activity->id }}" class="btn pt-0"
                   href="{{ route('activity.destroy.confirm', ['id' => $activity->id]) }}">
                    <i class="bi bi-trash text-danger"></i>
                </a>
            </td>
            @endforeach
        </tr>

    </tbody>

    <script>
        @if($team != null)  {{-- Manager --}}
        manager_script();
        @else
        technician_script();
        @endif
    </script>


@endsection
