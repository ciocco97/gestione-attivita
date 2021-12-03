@if(isset($required))
    {{ $required ? 'required' : '' }}
@elseif(isset($method) && ($method == $ADD || $method == $EDIT))
    required
@endif

@if(isset($disabled))
    {{ $disabled ? 'disabled' : '' }}
@elseif(isset($method) && ($method == $SHOW || $method == $DELETE))
    disabled
@endif
@if(isset($style))
    style="{{ $style }}"
@endif
