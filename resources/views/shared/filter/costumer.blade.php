<div class="row mb-1"> <!-- Riga selezione cliente -->
    <label class="col-md-4 col-form-label ps-md-3 ps-lg-5" for="#master_costumer_filter">
        @lang('labels.costumer')
    </label>
    <div class="col-md-8">
        <div class="d-flex">
            <select class="form-select" id="master_costumer_filter" name="costumer">
                <option value="" selected hidden>
                    @lang('labels.select') @lang('labels.costumer')
                </option>
                @foreach($costumers as $costumer)
                    <option value="{{ $costumer->id }}">{{ $costumer->nome }}</option>
                @endforeach
            </select>
            @include('shared.button_reset', ['btn_target_id' => '#master_costumer_filter'])
        </div>
    </div>
</div>
