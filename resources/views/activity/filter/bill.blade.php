<div class="row mb-1"> <!-- Riga selezione stato fatturazione -->
    <label class="col-md-4 col-form-label ps-md-3 ps-lg-5" for="#master_billing_state_filter">
        @lang('labels.billing_state')
    </label>
    <div class="col-md-8">
        <div class="d-flex">
            <select class="form-select" id="master_billing_state_filter" name="billing-state">
                <option value="" selected
                        hidden>@lang('labels.select') @lang('labels.billing_state')</option>
                <option value="{{ $FILTER_ACCOUNTED['ACCOUNTED'] }}">@lang('labels.accounted')</option>
                <option value="{{ $FILTER_ACCOUNTED['NOT_ACCOUNTED'] }}">@lang('labels.not_accounted')</option>
                @foreach($billing_states as $state)
                    <option
                        value="{{ $state->id }}">{{ $state->descrizione_stato_fatturazione }}</option>
                @endforeach
            </select>
            @include('button.reset', ['btn_target_id' => '#master_billing_state_filter'])
        </div>
    </div>
</div>
