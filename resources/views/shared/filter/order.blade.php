<div class="row mb-1"> <!-- Riga selezione commessa -->
    <label class="col-md-4 col-form-label ps-md-3 ps-lg-5" for="#master_order_filter">
        @lang('labels.order')
    </label>
    <div class="col-md-8">
        <div class="d-flex">
            @include('shared.select_general', ['label' => false, 'select_id' => 'master_order_filter', 'element_type' => __('labels.order'), 'element_list' => $orders, 'element_descr_key' => 'descrizione_commessa'])
            @include('shared.button_reset', ['btn_target_id' => '#master_order_filter'])
        </div>
    </div>
</div>