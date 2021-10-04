@extends('layouts.master')

@section('title', 'dipende, da mettere a posto')

@section('navbar')
    @include('nav')
@endsection

@section('body')
    <div class="container mt-lg-4"> <!-- Corpo della pagina -->
        <div class="row">
            <div class="col-md-1 col-lg-2 col-xl-1"></div>

            <div class="col-md-10 col-lg-8 col-xl-10">

                <form name="activity"

                    @if($method == $EDIT)
                        action="{{ route('activity.update', ['id' => $activity->id]) }}" method="post">
                    @elseif($method == $SHOW)
                        >
                    @elseif($method == $DELETE)
                        action="{{ route('activity.destroy', ['id' => $activity->id]) }}" method="delete">
                    @else
                        action="{{ route('activity.store') }}" method="post">
                    @endif

                    @csrf

                    @if($method == $EDIT)
                        <h3>@lang('labels.edit') @lang('labels.activity')
                    @elseif($method == $SHOW)
                        <h3>@lang('labels.show') @lang('labels.activity')
                    @elseif($method == $DELETE)
                        <h3>@lang('labels.delete') @lang('labels.activity')
                    @else
                        <h3>@lang('labels.add') @lang('labels.activity')
                    @endif
                         - {{ $tech_name }}</h3>

                    <div class="row mb-md-2 mt-3"> <!-- Primi due campi -->

                        <div class="col-md-6 mb-2 mb-md-0"> <!-- Primo campo -->
                            <label class="form-label" for="costumer">@lang('labels.costumer')</label>
                            @if($method != $ADD)
                                @if($method == $EDIT)
                                    <select class="form-select" id="costumer" name="costumer" required>
                                        @foreach($costumers as $costumer)
                                            @if($costumer->id == $current_costumer->id)
                                                <option value="{{ $costumer->id }}"
                                                        selected>{{ $costumer->nome }}</option>
                                            @else
                                                <option value="{{ $costumer->id }}">{{ $costumer->nome }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <select class="form-select" id="costumer" name="costumer" disabled>
                                        <option selected>{{ $current_costumer->nome }}</option>
                                    </select>
                                @endif
                            @else
                                <select class="form-select" id="costumer" name="costumer" required>
                                    <option value="" disabled selected hidden>@lang('labels.select') @lang('labels.costumer')</option>
                                    @foreach($costumers as $costumer)
                                        <option value="{{ $costumer->id }}">{{ $costumer->nome }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="col-md-6 mb-2 mb-md-0"> <!-- Secondo campo -->
                            <label class="form-label" for="order">@lang('labels.order')</label>
                            @if($method != $ADD)
                                @if($method == $EDIT)
                                    <select class="form-select" id="order" name="order" required>
                                        @foreach($orders as $order)
                                            @if($order->id == $current_order->id)
                                                <option value="{{ $order->id }}"
                                                        selected>{{ $order->descrizione_commessa }}</option>
                                            @else
                                                <option
                                                    value="{{ $order->id }}">{{ $order->descrizione_commessa }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <select class="form-select" id="order" name="order" disabled>
                                        <option selected>{{ $current_order->descrizione_commessa }}</option>
                                    </select>
                                @endif
                            @else
                                <select class="form-select" id="order" name="order" required>
                                    <option value="" disabled selected hidden>@lang('labels.select') @lang('labels.order')</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}">{{ $order->descrizione_commessa }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                    </div> <!-- Fine primi 2 campi -->

                    <div class="row mb-md-2"> <!-- Secondi tre campi -->

                        <div class="col-md-6 mb-2"> <!-- Terzo campo -->
                            <label class="form-label" for="date">@lang('labels.date')</label>
                            @if($method != $ADD)
                                @if($method == $EDIT)
                                    <input class="form-control" type="date" id="date" name="date"
                                           value="{{ $activity->data }}" required>
                                @else
                                    <input class="form-control" type="date" id="date" name="date"
                                           value="{{ $activity->data }}" disabled>
                                @endif
                            @else
                                <input class="form-control" type="date" id="date"
                                       name="date" required>
                            @endif
                        </div>

                        <div class="col-md-6 mb-2"> <!-- Quarto campo -->
                            <label class="form-label" for="duration">@lang('labels.duration')</label>
                            @if($method != $ADD)
                                @if($method == $EDIT)
                                    <input class="form-control" type="time" id="duration" name="duration"
                                           value="{{ $activity->durata }}">
                                @else
                                    <input class="form-control" type="time" id="duration" name="duration"
                                           value="{{ $activity->durata }}" disabled>
                                @endif
                            @else
                                <input class="form-control" type="time" id="duration" name="duration">
                            @endif
                        </div>

                        <div class="col-md-6 mb-2"> <!-- Quinto campo -->
                            <label class="form-label" for="startTime">@lang('labels.start_time')</label>
                            @if($method != $ADD)
                                @if($method == $EDIT)
                                    <input class="form-control" type="time" id="startTime" name="startTime"
                                           value="{{ $activity->ora_inizio }}">
                                @else
                                    <input class="form-control" type="time" id="startTime" name="startTime"
                                           value="{{ $activity->ora_inizio }}" disabled>
                                @endif
                            @else
                                <input class="form-control" type="time" id="startTime" name="startTime">
                            @endif
                        </div>

                        <div class="col-md-6 mb-2"> <!-- Sesto campo -->
                            <label class="form-label" for="endTime">@lang('labels.end_time')</label>
                            @if($method != $ADD)
                                @if($method == $EDIT)
                                    <input class="form-control" type="time" id="endTime" name="endTime"
                                           value="{{ $activity->ora_fine }}">
                                @else
                                    <input class="form-control" type="time" id="endTime" name="endTime"
                                           value="{{ $activity->ora_fine }}" disabled>
                                @endif
                            @else
                                <input class="form-control" type="time" id="endTime" name="endTime">
                            @endif
                        </div>

                    </div> <!-- Fine secondi tre campi -->

                    @if($manager)

                        <div class="row mb-md-2"> <!-- Fatturazione -->
                            <div class="col-md-6 mb-2 mb-md-0">
                                <label class="form-label" for="billing_state">@lang('labels.billing_state')</label>
                                @if($method == $EDIT)
                                    <select class="form-select" id="billing_state" name="billing_state" required>
                                        @foreach($billing_states as $billing_state)
                                            @if($billing_state->id == $current_billing_state->id)
                                                <option value="{{ $billing_state->id }}"
                                                        selected>{{ $billing_state->descrizione_stato_fatturazione }}</option>
                                            @else
                                                <option value="{{ $billing_state->id }}">{{ $billing_state->descrizione_stato_fatturazione }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @else
                                        <select class="form-select" id="billing_state" name="billing_state" disabled>
                                            <option selected>{{ $current_billing_state->descrizione_stato_fatturazione }}</option>
                                        </select>
                                @endif
                            </div>
                        </div>

                    @endif

                    <div class="row mb-md-2"> <!-- Terzo gruppo -->
                        <div class="col-md-12 mb-2 mb-md-0">
                            <label class="form-label" for="location">@lang('labels.location')</label>
                            @if($method != $ADD)
                                @if($method == $EDIT)
                                    <input class="form-control" type="text" id="location" name="location"
                                           value="{{ $activity->luogo }}">
                                @else
                                    <input class="form-control" type="text" id="location" name="location"
                                           value="{{ $activity->luogo }}" disabled>
                                @endif
                            @else
                                <input class="form-control" type="text" id="location" name="location">
                            @endif
                        </div>
                    </div>

                    <div class="row mb-md-2"> <!-- Quarto -->
                        <div class="col-md-12 mb-2 mb-md-0">
                            <label class="form-label" for="description">@lang('labels.description')</label>
                            @if($method != $ADD)
                                @if($method == $EDIT)
                                    <textarea class="form-control" id="description"
                                              name="description" required>{{ $activity->descrizione_attivita }}</textarea>
                                @else
                                    <textarea class="form-control" id="description" name="description"
                                              disabled>{{ $activity->descrizione_attivita }}</textarea>
                                @endif
                            @else
                                <textarea class="form-control" id="description" name="description" required></textarea>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-md-2"> <!-- Quinto -->
                        <div class="col-md-12 mb-2 mb-md-0">
                            <label class="form-label" for="internalNotes">@lang('labels.internal_notes')</label>
                            @if($method != $ADD)
                                @if($method == $EDIT)
                                    <input class="form-control" type="text" id="internalNotes" name="internalNotes"
                                           value="{{ $activity->note_interne }}">
                                @else
                                    <input class="form-control" type="text" id="internalNotes" name="internalNotes"
                                           value="{{ $activity->note_interne }}" disabled>
                                @endif
                            @else
                                <input class="form-control" type="text" id="internalNotes" name="internalNotes">
                            @endif

                        </div>
                    </div>

                    <div class="row mb-md-2"> <!-- Ultimo -->
                        <div class="col-md-12 mb-2 mb-md-0">
                            <label class="form-label" for="state">@lang('labels.state')</label>
                            @if($method != $ADD)
                                @if($method == $EDIT)
                                    <select class="form-select" id="state" name="state" required>
                                        @foreach($states as $state)
                                            @if($state->id == $current_state->id)
                                                <option value="{{ $state->id }}"
                                                        selected>{{ $state->descrizione_stato_attivita }}</option>
                                            @else
                                                <option
                                                    value="{{ $state->id }}">{{ $state->descrizione_stato_attivita }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <select class="form-select" id="state" name="state" disabled>
                                        <option selected>{{ $current_state->descrizione_stato_attivita }}</option>
                                    </select>
                                @endif
                            @else
                                <select class="form-select" id="state" name="state" required>
                                    <option value="" disabled selected hidden>@lang('labels.select') @lang('labels.state')</option>
                                    @foreach($states as $state)
                                        <option
                                            value="{{ $state->id }}">{{ $state->descrizione_stato_attivita }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div> <!-- Fine ultimo -->

                    <div class="row pt-3 pt-lg-4 mb-3"> <!-- Bottoni ai piedi della form -->
                        <div class="col-lg-6"></div>
                        @if($method == $EDIT)
                            <div class="col-sm-6 col-lg-3 mb-2 mb-md-0">
                                <a class="btn btn-secondary w-100" href="{{ $previous_url }}">
                                    <i class="bi bi-x-square me-2"></i>@lang('labels.cancel')</a>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <button class="btn btn-primary w-100" type="submit">
                                    <i class="bi bi-pencil me-2"></i>@lang('labels.edit')</button>
                            </div>
                        @elseif($method == $SHOW)
                            <div class="col-sm-6 col-lg-3 mb-2 mb-md-0"></div>
                            <div class="col-sm-6 col-lg-3">
                                <a class="btn btn-secondary w-100" href="{{ $previous_url }}">
                                    <i class="bi bi-arrow-bar-left me-2"></i>@lang('labels.back')</a>
                            </div>

                        @elseif($method == $DELETE)
                            <div class="col-sm-6 col-lg-3 mb-2 mb-md-0">
                                <a class="btn btn-secondary w-100" href="{{ $previous_url }}">
                                    <i class="bi bi-arrow-bar-left me-2"></i>@lang('labels.cancel')</a>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <button class="btn btn-danger w-100" type="submit">
                                    <i class="bi bi-trash me-2"></i>@lang('labels.delete')</button>
                            </div>

                        @else
                            <div class="col-sm-6 col-lg-3 mb-2 mb-md-0">
                                <a class="btn btn-danger w-100" href="{{ $previous_url }}">
                                    <i class="bi bi-arrow-bar-left me-2"></i>@lang('labels.cancel')</a>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <button class="btn btn-primary w-100" type="submit">
                                    <i class="bi bi-journal-plus me-2"></i>@lang('labels.add')</button>
                            </div>

                        @endif

                    </div>

                </form>
            </div>

            <div class="col-md-1 col-lg-2 col-xl-1"></div>
        </div>
    </div>

    <script>

        show_activity_script();

        @if($method == $ADD)
            $(document).ready(function () {
                var now = moment();
                $("#date").val(now.format("YYYY-MM-DD"));
                $("#startTime").val(moment("8:00", "H:mm").format("HH:mm"));
                $("#duration").val(moment("0:00", "H:mm").format("HH:mm"))
            });
        @endif
    </script>

@endsection
