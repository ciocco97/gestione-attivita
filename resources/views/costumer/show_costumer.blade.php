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

                <form name="costumer"

                      @if($method == $EDIT)
                      action="{{ route('costumer.update', ['id' => $costumer->id]) }}" method="post">
                    @elseif($method == $SHOW)
                        >
                    @elseif($method == $DELETE)
                        action="{{ route('costumer.destroy', ['id' => $costumer->id]) }}" method="delete">
                    @else
                        action="{{ route('costumer.store') }}" method="post">
                    @endif

                    @csrf

                    @if($method == $EDIT)
                        <h3>@lang('labels.edit') @lang('labels.costumer')</h3>
                    @elseif($method == $SHOW)
                        <h3>@lang('labels.show') @lang('labels.costumer')</h3>
                    @elseif($method == $DELETE)
                        <h3>@lang('labels.delete') @lang('labels.costumer')</h3>
                    @else
                        <h3>@lang('labels.add') @lang('labels.costumer')</h3>
                    @endif


                    <div class="row mb-md-2 mt-3"> <!-- Primi tre campi -->

                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="form-label" for="name">@lang('labels.name')</label>
                            @if($method != $ADD)
                                @if($method == $EDIT)
                                    <input class="form-control" type="text" id="name" name="name"
                                           value="{{ $costumer->nome }}" placeholder="@lang('labels.type') @lang('labels.name')">
                                @else
                                    <input class="form-control" type="text" id="name" name="name"
                                           value="{{ $costumer->nome }}" disabled>
                                @endif
                            @else
                                <input class="form-control" type="text" id="name" name="name" placeholder="@lang('labels.type') @lang('labels.name')">
                            @endif
                        </div>

                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="form-label" for="email">@lang('labels.email')</label>
                            @if($method != $ADD)
                                @if($method == $EDIT)
                                    <input class="form-control" type="email" id="email" name="email"
                                           value="{{ $costumer->email }}" placeholder="@lang('labels.type') @lang('labels.email')">
                                @else
                                    <input class="form-control" type="email" id="name" name="name"
                                           value="{{ $costumer->email }}" disabled>
                                @endif
                            @else
                                <input class="form-control" type="email" id="email" name="email" placeholder="@lang('labels.type') @lang('labels.email')">
                            @endif
                        </div>

                    </div> <!-- Fine primi 2 campi -->
                    <div class="row mb-md-2"> <!-- Campo rapportino -->
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="form-label" for="report_switch">@lang('labels.report')</label>
                            <div class="form-check form-switch">
                                @if($method != $ADD)
                                    @if($method == $EDIT)
                                        @if($costumer->rapportino_cliente)
                                            <input class="form-check-input" type="checkbox" id="report_switch"
                                                   name="report" checked>
                                        @else
                                            <input class="form-check-input" type="checkbox" id="report_switch"
                                                   name="report">
                                        @endif
                                    @else
                                        @if($costumer->rapportino_cliente)
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
                    </div>

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

