@extends('layouts.tmac')

@section('title', 'Gestione : cliente')

@section('navbar')
    @include('nav')
@endsection

@section('actions')

@endsection

@section('filters')

@endsection

@section('main')
    <div id="costumers">
        @foreach($costumers_nums_activities as $costumer_num_activity)
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{ $costumer_num_activity[0]->nome }}</h5>

                    <div class="" id="activities_{{ $costumer_num_activity[0]->id }}">

                        <div class="accordion-item mb-2">
                            <h2 class="accordion-header" id="title_{{ $costumer_num_activity[0]->id }}">
                                <button id="show_collapse_{{ $costumer_num_activity[0]->id }}"
                                        class="accordion-button collapsed"
                                        type="button" data-costumer-id="{{ $costumer_num_activity[0]->id }}"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse_{{ $costumer_num_activity[0]->id }}">
                                    @lang('labels.num_activities: '){{ count($costumer_num_activity[2]) }}
                                </button>
                            </h2>
                            <div id="collapse_{{ $costumer_num_activity[0]->id }}" class="accordion-collapse collapse"
                                 data-bs-parent="#costumers">
                                <div class="accordion-body">

                                    <div class="row mt-2">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-bordered border-secondary text-center text-dark align-middle">
                                                <thead>
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col">@lang('labels.tech_tab')</th>
                                                    <th scope="col">@lang('labels.date')</th>
                                                    <th scope="col">@lang('labels.description')</th>
                                                    <th scope="col">@lang('labels.costumer')</th>
                                                    <th scope="col">@lang('labels.billable_duration')</th>
                                                    <th scope="col">@lang('labels.billing_state')</th>
                                                    <th scope="col">@lang('labels.show')</th>

                                                </tr>
                                                </thead>
                                                <tbody id="master_tbody">


                                                @foreach($costumer_num_activity[2] as $activity)
                                                    <tr>
                                                        <td name="id" style="display: none">{{ $activity->id }}</td>
                                                        <!-- Serve a javascrit per il get dell'activity ID -->
                                                        <td id="select_{{ $activity->id }}">
                                                            <div class="d-flex justify-content-center">
                                                                <input class="form-check" type="checkbox">
                                                            </div>
                                                        </td>
                                                        <td id="technician_{{ $activity->id }}">{{ $activity->nome }}</td>
                                                        <td id="date_{{ $activity->id }}"
                                                            class="text-nowrap">{{ $activity->data }}</td>
                                                        <td class="fw-bold"
                                                            id="desc_{{ $activity->id }}" {{-- style="min-width: 230px" --}}>
                                                            {{ $activity->descrizione_attivita }}
                                                        </td>
                                                        <td id="costumer_{{ $activity->id }}">{{ $activity->nome_cliente }}</td>
                                                        <td id="billable_duration_{{ $activity->id }}">
                                                            <div class="d-flex justify-content-center">
                                                                <input id="billable_duration_input_{{ $activity->id }}"
                                                                       class="form-control" type="time"
                                                                       value="{{ $activity->durata_fatturabile }}"
                                                                       style="max-width: 100px">
                                                                <div
                                                                    id="wait_change_billable_duration_{{ $activity->id }}"
                                                                    class="spinner-border spinner-border-sm text-success"
                                                                    role="status"
                                                                    style="display: none;">
                                                                    <span class="visually-hidden">Loading...</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td id="billing_state_{{ $activity->id }}">
                                                            <div class="d-flex align-content-center">
                                                                <select class="form-select"
                                                                        id="billing_state_select_{{ $activity->id }}"
                                                                        style="width: auto;">
                                                                    @foreach($billing_states as $billing_state)
                                                                        @if($billing_state->id == $activity->stato_fatturazione_id)
                                                                            <option value="{{ $billing_state->id }}"
                                                                                    selected>{{ $billing_state->descrizione_stato_fatturazione }}</option>
                                                                        @else
                                                                            @if($billing_state->id != 4)
                                                                                <option
                                                                                    value="{{ $billing_state->id }}">{{ $billing_state->descrizione_stato_fatturazione }}</option>
                                                                            @endif
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
                                            </table>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer text-muted">
                            @lang('labels:to_bill'): {{ $costumer_num_activity[1] == null?0:$costumer_num_activity[1] }}
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
    </div>

    <script>
        administrative_script();
    </script>

@endsection
