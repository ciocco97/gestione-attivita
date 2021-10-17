<div class="row"> <!-- Riga selezione utente -->
    <label class="col-md-4 col-form-label ps-md-3 ps-lg-5" for="#master_user_filter">
        @lang('labels.tech_tab')
    </label>
    <div class="col-md-8">
        <div class="d-flex">
            <select class="form-select" id="master_user_filter" name="user">
                <option value="" selected
                        hidden>@lang('labels.select') @lang('labels.tech_tab')</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->nome }}</option>
                @endforeach
            </select>
            @include('button.reset', ['btn_target_id' => '#master_user_filter'])
        </div>
    </div>
</div>