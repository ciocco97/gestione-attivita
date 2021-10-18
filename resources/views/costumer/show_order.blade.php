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

                <form name="order"

                    @if($method == $EDIT)
                        action="{{ route('order.update', ['id' => $order->id]) }}" method="post">
                    @elseif($method == $SHOW)
                        >
                    @elseif($method == $DELETE)
                        action="{{ route('order.destroy', ['id' => $order->id]) }}" method="delete">
                    @else
                        action="{{ route('order.store') }}" method="post">
                    @endif

                    @csrf

                    @if($method == $EDIT)
                        <h3>@lang('labels.edit') @lang('labels.order')
                    @elseif($method == $SHOW)
                        <h3>@lang('labels.show') @lang('labels.order')
                    @elseif($method == $DELETE)
                        <h3>@lang('labels.delete') @lang('labels.order')
                    @else
                        <h3>@lang('labels.add') @lang('labels.order')
                    @endif
                        </h3>

                    <div class="row mb-md-2 mt-3"> <!-- Primi due campi -->

                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="form-label" for="description">@lang('labels.description')</label>
                            @if($method != $ADD)
                                @if($method == $EDIT)
                                    <input class="form-control" type="text" id="description" name="description"
                                           value="{{ $order->descrizione_commessa }}" placeholder="@lang('labels.type') @lang('labels.description')">
                                @else
                                    <input class="form-control" type="text" id="description" name="description"
                                           value="{{ $order->descrizione_commessa }}" disabled>
                                @endif
                            @else
                                <input class="form-control" type="text" id="description" name="description" placeholder="@lang('labels.type') @lang('labels.description')">
                            @endif
                        </div>

                        <div class="col-md-6 mb-2 mb-md-0"> <!-- Secondo campo -->
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

                    </div> <!-- Fine primi 2 campi -->

                    <div class="row mb-md-2 mt-3"> <!-- Ultimi 2 -->
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="form-label" for="state">@lang('labels.state')</label>
                            @if($method != $ADD)
                                @if($method == $EDIT)
                                    <select class="form-select" id="state" name="state" required>
                                        @foreach($states as $state)
                                            @if($state->id == $current_state->id)
                                                <option value="{{ $state->id }}"
                                                        selected>{{ $state->descrizione_stato_commessa }}</option>
                                            @else
                                                <option
                                                    value="{{ $state->id }}">{{ $state->descrizione_stato_commessa }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <select class="form-select" id="state" name="state" disabled>
                                        <option selected>{{ $current_state->descrizione_stato_commessa }}</option>
                                    </select>
                                @endif
                            @else
                                <select class="form-select" id="state" name="state" required>
                                    <option value="" disabled selected hidden>@lang('labels.select') @lang('labels.state')</option>
                                    @foreach($states as $state)
                                        <option
                                            value="{{ $state->id }}">{{ $state->descrizione_stato_commessa }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="form-label" for="report_switch">@lang('labels.report')</label>
                            <div class="form-check form-switch">
                                @if($method != $ADD)
                                    @if($method == $EDIT)
                                        @if($order->rapportino_commessa)
                                            <input class="form-check-input" type="checkbox" id="report_switch"
                                                   name="report" checked>
                                        @else
                                            <input class="form-check-input" type="checkbox" id="report_switch"
                                                   name="report">
                                        @endif
                                    @else
                                        @if($order->rapportino_commessa)
                                            <input class="form-check-input" type="checkbox" id="report_switch"
                                                   name="report" checked disabled>
                                        @else
                                            <input class="form-check-input" type="checkbox" id="report_switch"
                                                   name="report" disabled>
                                        @endif
                                    @endif
                                @else
                                    <input class="form-check-input" type="checkbox" id="report_switch"
                                           name="report" checked>
                                @endif
                            </div>
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
        @if($method == $ADD)

        @endif
    </script>

@endsection
