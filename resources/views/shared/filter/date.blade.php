@php
    if ($input_order == 1) {
        $input_date_label = "from_date";
    } else {
        $input_date_label = "to_date";
    }
@endphp

<div class="row mb-1"> <!-- Riga inserimento data -->
    <label class="col-md-4 col-form-label pt-0 pt-md-2"
           for="#master_date_filter">@lang('labels.'.$input_date_label)</label>
    <div class="col-md-8">
        <div class="d-flex">
            @include('shared.input_general', ['label' => false, 'input_id' => 'master_date_filter'.$input_order, 'input_type' => 'date'])
            @include('shared.button_reset', ['btn_target_id' => '#master_date_filter'.$input_order, 'change' => '#master_date_filter'.$input_order])
        </div>
    </div>
</div>
