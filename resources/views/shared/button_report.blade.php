@if($activity->rapportino_cliente && $activity->rapportino_commessa)
    <button id="send_report_{{ $activity->id }}" class="btn pt-0">
        @if($activity->rapportino_attivita)
            <i class="bi bi-clipboard-check text-success"></i>
        @else
            <i class="bi bi-clipboard text-primary"></i>
        @endif
    </button>
    <div id="wait_change_send_report_{{ $activity->id }}" class="spinner-border spinner-border-sm text-success"
         role="status"
         style="display: none;">
        <span class="visually-hidden">Loading...</span>
    </div>
@else
    <a id="send_report_{{ $activity->id }}" class="btn pt-0 disabled">
        <i class="bi bi-clipboard-x text-danger"></i>
    </a>
@endif