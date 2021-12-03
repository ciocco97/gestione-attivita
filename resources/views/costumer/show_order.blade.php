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
                            @include('shared.input_general', ['input_id' => 'description', 'element_type' => __('labels.description'), 'input_type' => 'text', 'element_descr_key' => 'descrizione_commessa', 'element' => $method == $ADD ? null : $order, 'placeholder' => __('labels.type') . ' ' . __('labels.description')])
                        </div>

                        <div class="col-md-6 mb-2 mb-md-0"> <!-- Secondo campo -->
                            @include('shared.select_general', ['select_id' => 'costumer', 'element_type' => __('labels.costumer'), 'element_list' => $costumers, 'element_descr_key' => 'nome', 'current_element' => $method == $ADD ? null : $current_costumer])
                        </div>

                    </div> <!-- Fine primi 2 campi -->

                    <div class="row mb-md-2 mt-3"> <!-- Ultimi 2 -->
                        <div class="col-md-6 mb-2 mb-md-0">
                            @include('shared.select_general', ['select_id' => 'state', 'element_type' => __('labels.state'), 'element_list' => $states, 'element_descr_key' => 'descrizione_stato_commessa', 'current_element' => $method == $ADD ? null : $current_state])
                        </div>

                        <div class="col-md-6 mb-2 mb-md-0">
                            @include('shared.input_switch', ['element_id' => 'report_switch', 'element_type' => __('labels.report'), 'element' => $method == $ADD ? null : $order->rapportino_commessa, 'required' => false])
                        </div>
                    </div> <!-- Fine ultimo -->

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
