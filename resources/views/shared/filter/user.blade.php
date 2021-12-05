<div class="row"> <!-- Riga selezione utente -->
    <label class="col-md-4 col-form-label ps-md-3 ps-lg-5" for="#master_user_filter">
        @lang('labels.tech_tab')
    </label>
    <div class="col-md-8">
        <div class="d-flex">
            @include('shared.select_general', ['label' => false, 'select_id' => 'master_user_filter', 'element_type' => __('labels.tech_tab'), 'element_list' => $users, 'element_descr_key' => 'nome', 'default_value' => $FILTER_TEAM['TEAM_MEMBER_NOT_SELECTED']])
            @include('shared.button_reset', ['btn_target_id' => '#master_user_filter', 'btn_reset_id' => $FILTER_TEAM['TEAM_MEMBER_NOT_SELECTED']])
        </div>
    </div>
</div>
