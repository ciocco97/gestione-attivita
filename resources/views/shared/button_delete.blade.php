<a id="delete_{{ $element_id }}" class="btn pt-0
@if(isset($disabled) && $disabled)
    disabled
@endif
    "
   href="{{ route( $element_type . '.destroy.confirm', ['id' => $element_id]) }}">
    <i class="bi bi-trash
@if(isset($disabled) && $disabled)

@else
        text-danger
@endif
        "></i>
</a>
