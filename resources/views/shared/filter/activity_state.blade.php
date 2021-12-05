<div class="row mb-1"> <!-- Riga selezione stato -->
    <label class="col-md-4 col-form-label ps-md-3 ps-lg-5" for="#master_state_filter">
        @lang('labels.state')
    </label>
    <div class="col-md-8">
        <div class="d-flex">
            @include('shared.select_general', ['label' => false, 'select_id' => 'master_state_filter', 'element_type' => __('labels.state'), 'element_list' => $states, 'element_descr_key' => 'descrizione_stato_attivita'])
            @include('shared.button_reset', ['btn_target_id' => '#master_state_filter'])
        </div>
    </div>
</div>
