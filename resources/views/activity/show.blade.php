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

                    <h3>
                        @if($method == $EDIT)
                            @lang('labels.edit') @lang('labels.activity')
                        @elseif($method == $SHOW)
                            @lang('labels.show') @lang('labels.activity')
                        @elseif($method == $DELETE)
                            @lang('labels.delete') @lang('labels.activity')
                        @else
                            @lang('labels.add') @lang('labels.activity')
                        @endif
                        - {{ $tech_name }}
                    </h3>

                    <div class="row mb-md-2 mt-3"> <!-- Primi due campi -->
                        <div class="col-md-6 mb-2 mb-md-0"> <!-- Primo campo -->
                            @include('shared.select_general', ['select_id' => 'costumer', 'element_type' => __('labels.costumer'), 'element_list' => $costumers, 'element_descr_key' => 'nome', 'current_element' => $method == $ADD ? null : $current_costumer])
                        </div>

                        <div class="col-md-6 mb-2 mb-md-0"> <!-- Secondo campo -->
                            @include('shared.select_general', ['select_id' => 'order', 'element_type' => __('labels.order'), 'element_list' => $orders, 'element_descr_key' => 'descrizione_commessa', 'current_element' => $method == $ADD ? null : $current_order])
                        </div>

                    </div> <!-- Fine primi 2 campi -->

                    <div class="row mb-md-2"> <!-- Secondi tre campi -->

                        <div class="col-md-6 mb-2"> <!-- Terzo campo -->
                            @include('shared.input_general', ['input_id' => 'date', 'element_type' => __('labels.date'), 'input_type' => 'date', 'element_descr_key' => 'data', 'element' => $method == $ADD ? null : $activity])
                        </div>

                        <div class="col-md-6 mb-2"> <!-- Quarto campo -->
                            @include('shared.input_general', ['input_id' => 'duration', 'element_type' => __('labels.duration'), 'input_type' => 'time', 'element_descr_key' => 'durata', 'element' => $method == $ADD ? null : $activity, 'compute_button' => true])
                        </div>

                        <div class="col-md-6 mb-2"> <!-- Quinto campo -->
                            @include('shared.input_general', ['input_id' => 'startTime', 'element_type' => __('labels.start_time'), 'input_type' => 'time', 'element_descr_key' => 'ora_inizio', 'element' => $method == $ADD ? null : $activity])
                        </div>

                        <div class="col-md-6 mb-2"> <!-- Sesto campo -->
                            @include('shared.input_general', ['input_id' => 'endTime', 'element_type' => __('labels.end_time'), 'input_type' => 'time', 'element_descr_key' => 'ora_fine', 'element' => $method == $ADD ? null : $activity, 'required' => false])
                        </div>

                    </div> <!-- Fine secondi tre campi -->

                    @if($CURRENT_PAGE != $PAGES['TECHNICIAN'])
                        <div class="row mb-md-2"> <!-- Fatturazione -->
                            <div class="col-md-6 mb-2 mb-md-0">
                                @include('shared.select_general', ['select_id' => 'billing_state', 'element_type' => __('labels.billing_state'), 'element_list' => $billing_states, 'element_descr_key' => 'descrizione_stato_fatturazione', 'current_element' => $method == $ADD ? null : $current_billing_state, 'required' => false])
                            </div>
                            <div class="col-md-6 mb-2 mb-md-0">
                                @include('shared.input_general', ['input_id' => 'billable_duration', 'element_type' => __('labels.duration') . ' ' . __('labels.billable_duration'), 'input_type' => 'time', 'element_descr_key' => 'durata_fatturabile', 'element' => $method == $ADD ? null : $activity])
                            </div>
                        </div>
                    @endif

                    <div class="row mb-md-2"> <!-- Terzo gruppo -->
                        <div class="col-md-12 mb-2 mb-md-0">
                            @include('shared.input_general', ['input_id' => 'location', 'element_type' => __('labels.location'), 'input_type' => 'text', 'element_descr_key' => 'luogo', 'element' => $method == $ADD ? null : $activity, 'required' => false])
                        </div>
                    </div>

                    <div class="row mb-md-2"> <!-- Quarto -->
                        <div class="col-md-12 mb-2 mb-md-0">
                            @include('shared.input_textarea', ['input_id' => 'description', 'element_type' => __('labels.description'), 'element_descr_key' => 'descrizione_attivita', 'element' => $method == $ADD ? null : $activity])

                        </div>
                    </div>

                    <div class="row mb-md-2"> <!-- Quinto -->
                        <div class="col-md-12 mb-2 mb-md-0">
                            @include('shared.input_general', ['input_id' => 'internalNotes', 'element_type' => __('labels.internal_notes'), 'input_type' => 'text', 'element_descr_key' => 'note_interne', 'element' => $method == $ADD ? null : $activity, 'required' => false])

                        </div>
                    </div>

                    <div class="row mb-md-2"> <!-- Ultimo -->
                        <div class="col-md-12 mb-2 mb-md-0">
                            @include('shared.select_general', ['select_id' => 'state', 'element_type' => __('labels.state'), 'element_list' => $states, 'element_descr_key' => 'descrizione_stato_attivita', 'current_element' => $method == $ADD ? null : $current_state])
                        </div>
                    </div> <!-- Fine ultimo -->

                    @include('shared.footer_add_edit.buttons', ['icon_code' => 'bi-journal-plus'])

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
