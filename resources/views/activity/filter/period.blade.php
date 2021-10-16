<div class="row mb-1"> <!-- Riga selezione periodo -->
    <label class="col-md-4 col-form-label pt-0 pt-md-2" for="#period">@lang('labels.period')</label>
    <div class="col-md-8 col-lg-5">
        <div class="d-flex">
            <select value="" class="form-select" id="master_period_filter" name="period">
                <option value="" hidden>@lang('labels.select') @lang('labels.period')</option>
                <option value="1"
                    {{ $_SESSION['current_page']==$pages['TECHNICIAN'] || $_SESSION['current_page']==$pages['MANAGER'] ?'selected':'' }}>
                    @lang('labels.last_week')</option>
                <option value="2">@lang('labels.last_two_weeks')</option>
                <option
                    value="3" {{ $_SESSION['current_page']==$pages['ADMINISTRATIVE']?'selected':'' }}>@lang('labels.current_month')</option>
                <option value="4">@lang('labels.last_month')</option>
                <option value="5">@lang('labels.all')</option>
            </select>
            <button type="button" class="btn"
                    onclick='$("#master_period_filter").val(""); $("#master_period_filter").change()'>
                <i class="bi bi-x-square"></i>
            </button>
        </div>
    </div>
</div>
