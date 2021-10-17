<button type="button" class="btn" onclick='
    $("{{ $btn_target_id }}").val("");
@if(isset($change))
    $("{{ $change }}").change()
@endif
    '>
    <i class="bi bi-x-square"></i>
</button>
