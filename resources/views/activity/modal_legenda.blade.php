<div class="modal fade" id="modal_legenda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" id="modal_legenda_title">
                    @lang('labels.legend_title')
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modal_legenda_text">
                <p>
                    <svg class="bd-placeholder-img rounded me-2" width="20" height="20"
                         xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <rect width="100%" height="100%" fill="#0d6efd"></rect>
                    </svg>
                    @lang('text.complete_legenda')
                </p>
                <hr class="dropdown-divider">
                <p>
                    <svg class="bd-placeholder-img rounded me-2" width="20" height="20"
                         xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <rect width="100%" height="100%" fill="#6c757d"></rect>
                    </svg>
                    @lang('text.cancelled_legenda')
                </p>
                <p>
                    @lang('text.cancelled_legenda_description')
                </p>
                <hr class="dropdown-divider">
                <p>
                    <svg class="bd-placeholder-img rounded me-2" width="20" height="20"
                         xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <rect width="100%" height="100%" fill="#198754"></rect>
                    </svg>
                    @lang('text.approved_legenda')
                </p>
                <p>
                    @lang('text.approved_legenda_description')
                </p>
                <hr class="dropdown-divider">
                <p>
                    <svg class="bd-placeholder-img rounded me-2" width="20" height="20"
                         xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <rect width="100%" height="100%" fill="#ffe1e1"></rect>
                    </svg>
                    @lang('text.open_legenda')
                </p>
                <hr class="dropdown-divider">
                <p>
                    <svg class="bd-placeholder-img rounded me-2" width="20" height="20"
                         xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <rect width="100%" height="100%" fill="#fff8a9"></rect>
                    </svg>
                    @lang('text.to_send_report_legenda')
                </p>
                <hr class="dropdown-divider">
                <p>
                    <svg class="bd-placeholder-img rounded me-2" width="20" height="20"
                         xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <rect width="100%" height="100%" fill="#c7edc9"></rect>
                    </svg>
                    @lang('text.accounted_legenda')
                </p>
                <p>
                    @lang('text.accounted_legenda_description')
                </p>
                <hr class="dropdown-divider">
                <p>
                    <i class="bi bi-send-fill text-primary"></i>
                    @lang('text.send_report_legenda')
                </p>
                <p>
                    <i class="bi bi-send-check-fill text-success"></i>
                    @lang('text.sent_report_legenda')
                </p>
                <p>
                    <i class="bi bi-send-slash-fill text-secondary"></i>
                    @lang('text.forbidden_report_legenda')
                </p>
                <p>
                    <i class="bi bi-send-fill text-secondary"></i>
                    @lang('text.send_forbidden_report_legenda')
                </p>
                <p>
                    <i class="bi bi-send-check-fill text-secondary"></i>
                    @lang('text.sent_forbidden_report_legenda')
                </p>
                <hr class="dropdown-divider">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('labels.close')</button>
            </div>
        </div>
    </div>
</div>
