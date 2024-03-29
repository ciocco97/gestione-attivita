<div class="row mb-1"> <!-- Riga selezione periodo -->
    <label class="col-md-4 col-form-label pt-0 pt-md-2" for="#period">@lang('labels.period')</label>
    <div class="col-md-8 col-lg-5">
        <div class="d-flex">
            <select value="" class="form-select" id="master_period_filter" name="period">
                <option value="" hidden>@lang('labels.select') @lang('labels.period')</option>
                <option value="{{ $FILTER_PERIOD['CURRENT_WEEK'] }}">
                    @lang('labels.last_week')</option>
                <option value="{{ $FILTER_PERIOD['CURRENT_TWO_WEEKS'] }}">@lang('labels.last_two_weeks')</option>
                <option
                    value="{{ $FILTER_PERIOD['CURRENT_MONTH'] }}" selected>@lang('labels.current_month')</option>
                <option value="{{ $FILTER_PERIOD['LAST_MONTH'] }}">@lang('labels.last_month')</option>
                <option value="{{ $FILTER_PERIOD['ALL'] }}">@lang('labels.all')</option>
            </select>
            @include('shared.button_reset', ['btn_target_id' => '#master_period_filter', 'change' => '#master_period_filter'])
        </div>
    </div>
</div>
