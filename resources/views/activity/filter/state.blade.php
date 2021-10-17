<div class="row mb-1"> <!-- Riga selezione stato -->
    <label class="col-md-4 col-form-label ps-md-3 ps-lg-5" for="#master_state_filter">
        @lang('labels.state')
    </label>
    <div class="col-md-8">
        <div class="d-flex">
            <select class="form-select" id="master_state_filter" name="state">
                <option value="" selected hidden>@lang('labels.select') @lang('labels.state')</option>
                @foreach($states as $state)
                    <option value="{{ $state->id }}">{{ $state->descrizione_stato_attivita }}</option>
                @endforeach
            </select>
            @include('button.reset', ['btn_target_id' => '#master_state_filter'])
        </div>
    </div>
</div>
