<div class="modal fade" id="modal_confirmation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" id="modal_confirmation_title"
                    data-titles="   @lang('labels.complete') @lang('labels.activities')-
                                    @lang('labels.open') @lang('labels.activities')-
                                    @lang('labels.cancel') @lang('labels.activities')-
                                    @lang('labels.approve') @lang('labels.activities')">

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modal_confirmation_text">
                    @lang('text.modal_confirmation_first')
                    <span id="modal_confirmation_num_activities"></span>
                    @lang('labels.activities')?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('labels.cancel')</button>
                <button id="modal_confirmation_confirm" type="button"
                        class="btn btn-primary">@lang('labels.confirm')</button>
            </div>
        </div>
    </div>
</div>
