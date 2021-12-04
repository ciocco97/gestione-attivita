@if(!isset($label) || $label)
    <label class="form-label" for="{{ $input_id }}">{{ $element_type }}</label>
@endif

<input
    class="form-control"
    type="{{ $input_type }}"
    id="{{ $input_id }}"
    name="{{ $input_id }}"

    @if(isset($placeholder))
    placeholder="{{ $placeholder }}"
    @endif

    @if(isset($element))
    value="{{ $element->$element_descr_key }}"
    @endif

    @include('shared.input_required_disabled_style')
>
