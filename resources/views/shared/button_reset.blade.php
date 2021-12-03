<button type="button" class="btn" onclick='
@if(isset($btn_reset_id))
    $("{{ $btn_target_id }}").val("{{ $btn_reset_id }}");
@else
    $("{{ $btn_target_id }}").val("");
@endif
@if(isset($change))
    $("{{ $change }}").change()
@endif
    '>
    <i class="bi bi-x-square"></i>
</button>
