<div class="row mb-1"> <!-- Riga inserimento data -->
    <label class="col-md-4 col-form-label pt-0 pt-md-2"
           for="#master_date_filter">@lang('labels.date')</label>
    <div class="col-md-8">
        <div class="d-flex">
            <input type="date" class="form-control" id="master_date_filter" name="date">
            @include('button.reset', ['btn_target_id' => '#master_date_filter', 'change' => '#master_date_filter'])
        </div>
    </div>
</div>
