@if($activity->rapportino_cliente && $activity->rapportino_commessa)
    <button id="send_report_{{ $activity->id }}" class="btn pt-0"
            data-report-sent="{{ $activity->rapportino_attivita }}">
        @if($activity->rapportino_attivita)
            <i class="bi bi-send-check-fill text-success"></i>
        @else
            <i class="bi bi-send-fill text-primary"
            @if($activity->stato_attivita_id != $ACTIVITY_STATES['CANCELLED'])
               data-report-to-send="1"
            @endif
            ></i>
        @endif
    </button>
    <div id="wait_change_send_report_{{ $activity->id }}" class="spinner-border spinner-border-sm text-success"
         role="status"
         style="display: none;">
        <span class="visually-hidden">Loading...</span>
    </div>
@else
    <a id="send_report_{{ $activity->id }}" class="btn pt-0 disabled">
        @if($activity->rapportino_attivita)
            <i class="bi bi-send-check-fill"></i>
        @else
            <i class="bi bi-send-slash-fill"></i>
        @endif
    </a>
@endif
