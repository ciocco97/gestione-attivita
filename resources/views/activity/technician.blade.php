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
            @if($users == null)
                <a class="btn btn-outline-primary" href="{{ route('activity.create') }}">
                    <i class="bi bi-journal-plus me-2"></i>@lang('labels.add') @lang('labels.activity')
                </a>
            @endif
            @if($users != null) {{-- Manager --}}
            <button id="activities_change_4" data-state="4" class="btn btn-outline-success border-end-0 pe-1"
                    style="display: none;">
                <i class="bi bi-pencil me-2"></i>@lang('labels.approve')
            </button>
            @endif
            <button id="activities_change_btn" class="btn btn-outline-success rounded-end border-start-0 px-1"
                    data-bs-toggle="dropdown" style="display: none;">
                @if($users == null) {{-- Technician --}}
                @lang('labels.change') @lang('labels.state')
                @endif
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu">
                @if($users != null)
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

                @include('activity.filter.period')
                @include('activity.filter.date')
            </div>

            <div class="col-md-5 ps-lg-5"> <!-- Seconda coppia di filtri -->

                @include('activity.filter.costumer')
                @include('activity.filter.state')
                @if($users != null) {{-- Manager --}}
                @include('activity.filter.user')
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

@section('main')

    @include('pagination.search_and_num_rows')


    <div class="row mt-2"> <!-- Master table -->
        <div class="table-responsive">
            <table class="table table-bordered border-secondary text-center text-dark align-middle">
                <thead>
                <tr>
                    <th scope="col"></th>
                    @if ($users != null) {{-- Manager --}}
                    <th scope="col">@lang('labels.tech_tab')</th>
                    @endif
                    <th scope="col">@lang('labels.date')</th>
                    <th scope="col">@lang('labels.from')</th>
                    <th scope="col">@lang('labels.description')</th>
                    <th scope="col">@lang('labels.costumer')</th>
                    <th scope="col">@lang('labels.order')</th>
                    {{--        <th scope="col">@lang('labels.from')</th>--}}
                    {{--        <th scope="col">@lang('labels.to')</th>--}}
                    <th scope="col">@lang('labels.duration')</th>
                    @if ($users != null) {{-- Manager --}}
                    <th scope="col">@lang('labels.billable_duration')</th>
                    @endif
                    <th scope="col">@lang('labels.state')</th>
                    @if($users != null) {{-- Manager --}}
                    <th scope="col">@lang('labels.billing_state')</th>
                    @endif
                    <th scope="col">@lang('labels.report')</th>
                    <th scope="col">@lang('labels.show')</th>
                    <th scope="col">@lang('labels.edit')</th>
                    <th scope="col">@lang('labels.delete')</th>

                </tr>
                </thead>
                <tbody id="master_tbody">

                @foreach($activities as $activity)

                    <tr id="activity_row_{{ $activity->id }}" data-bill="{{ $activity->fatturata }}">
                        {{--                        <td name="id" style="display: none">{{ $activity->id }}</td>--}}
                        <td id="select_{{ $activity->id }}">
                            <div class="d-flex justify-content-center">
                                <input id="check_select_{{ $activity->id }}" class="form-check" type="checkbox">
                            </div>
                        </td>
                        @if($users != null) {{-- Manager --}}
                        <td id="technician_{{ $activity->id }}">{{ $activity->nome }}</td>
                        @endif
                        <td id="date_{{ $activity->id }}" class="text-nowrap">{{ $activity->data }}</td>
                        <td id="startTime_{{ $activity->id }}">{{ substr($activity->ora_inizio, 0, 5) }}</td>
                        <td class="fw-bold" id="desc_{{ $activity->id }}"
                            {{-- style="min-width: 230px" --}} data-bs-toggle="tooltip" data-bs-placement="right"
                            title="{{ $activity->descrizione_attivita }}">
                            {{ $activity->desc_attivita }}
                        </td>
                        <td id="costumer_{{ $activity->id }}">{{ $activity->nome_cliente }}</td>
                        <td id="order_{{ $activity->id }}">{{ $activity->descrizione_commessa }}</td>
                        {{--            <td id="endTime_{{ $activity->id }}">{{ substr($activity->ora_fine, 0, 5) }}</td>--}}
                        <td id="duration_{{ $activity->id }}">{{ substr($activity->durata, 0, 5) }}</td>
                        @if ($users != null) {{-- Manager --}}
                        <td id="billable_duration_{{ $activity->id }}">
                            <div class="d-flex justify-content-center">
                                <input id="billable_duration_input_{{ $activity->id }}" class="form-control" type="time"
                                       value="{{ $activity->durata_fatturabile }}"
                                       style="max-width: 100px">
                                <div id="wait_change_billable_duration_{{ $activity->id }}"
                                     class="spinner-border spinner-border-sm text-success"
                                     role="status"
                                     style="display: none;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </td>
                        @endif
                        <td id="state_{{ $activity->id }}"
                            data-state-id="{{ $activity->stato_attivita_id }}">{{ $activity->descrizione_stato_attivita }}</td>
                        @if($users != null) {{-- Manager --}}
                        <td id="billing_state_{{ $activity->id }}">
                            <div class="d-flex align-content-center">
                                <select class="form-select" id="billing_state_select_{{ $activity->id }}"
                                        style="width: auto;">
                                    @foreach($billing_states as $billing_state)
                                        @if($billing_state->id == $activity->stato_fatturazione_id)
                                            <option value="{{ $billing_state->id }}"
                                                    selected>{{ $billing_state->descrizione_stato_fatturazione }}</option>
                                        @else
                                            <option
                                                value="{{ $billing_state->id }}">{{ $billing_state->descrizione_stato_fatturazione }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div id="wait_change_billing_{{ $activity->id }}"
                                     class="spinner-border spinner-border-sm text-success"
                                     role="status"
                                     style="display: none;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </td>
                    @endif

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
                    </tr>
                @endforeach

                </tbody>

                <script>
                    @if($users != null)  {{-- Manager --}}
                    manager_script();
                    @else
                    technician_script();
                    @endif
                </script>

            </table>
        </div>
    </div>

    @include('pagination.page_selector')

@endsection
