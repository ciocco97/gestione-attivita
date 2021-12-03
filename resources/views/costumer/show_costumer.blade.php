@extends('layouts.master')

@section('title', 'dipende, da mettere a posto')

@section('navbar')
    @include('navbar.nav')
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
                            @include('shared.input_general', ['input_id' => 'name', 'element_type' => __('labels.name'), 'input_type' => 'text', 'element_descr_key' => 'nome', 'element' => $method == $ADD ? null : $costumer, 'placeholder' => __('labels.type') . ' ' . __('labels.name')])
                        </div>

                        <div class="col-md-6 mb-2 mb-md-0">
                            @include('shared.input_general', ['input_id' => 'email', 'element_type' => __('labels.email'), 'input_type' => 'email', 'element_descr_key' => 'email', 'element' => $method == $ADD ? null : $costumer, 'placeholder' => __('labels.type') . ' ' . __('labels.email')])
                        </div>

                    </div> <!-- Fine primi 2 campi -->
                    <div class="row mb-md-2"> <!-- Campo rapportino -->
                        <div class="col-md-6 mb-2 mb-md-0">
                            @include('shared.input_switch', ['element_id' => 'report_switch', 'element_type' => __('labels.report'), 'element' => $method == $ADD ? null : $costumer->rapportino_cliente, 'required' => false])
                        </div>
                    </div>

                    @include('shared.footer_add_edit.buttons')

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

