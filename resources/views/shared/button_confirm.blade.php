<button id="reset_button_{{ $element_id }}" type="button" class="btn text-secondary border-secondary ms-2" disabled>
    <i class="bi bi-arrow-clockwise"></i>
</button>
<button id="confirm_button_{{ $element_id }}" type="button" class="btn text-success border-success ms-2" disabled>
    <div id="wait_change_user_email_{{ $element_id }}" class="spinner-border spinner-border-sm text-success" role="status" style="display: none;">
        <span class="visually-hidden">Loading...</span>
    </div>
    <i id="icon_change_user_email_{{ $element_id }}" class="bi bi-check-circle"></i>
</button>
