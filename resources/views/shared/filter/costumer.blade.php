<div class="row mb-1"> <!-- Riga selezione cliente -->
    <label class="col-md-4 col-form-label ps-md-3 ps-lg-5" for="#master_costumer_filter">
        @lang('labels.costumer')
    </label>
    <div class="col-md-8">
        <div class="d-flex">
            @include('shared.select_general', ['label' => false, 'select_id' => 'master_costumer_filter', 'element_type' => __('labels.costumer'), 'element_list' => $costumers, 'element_descr_key' => 'nome'])
            @include('shared.button_reset', ['btn_target_id' => '#master_costumer_filter'])
        </div>
    </div>
</div>
