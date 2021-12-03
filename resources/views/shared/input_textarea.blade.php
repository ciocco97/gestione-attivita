<label class="form-label" for="{{ $input_id }}">{{ $element_type }}</label>
<textarea
    class="form-control"
    id="{{ $input_id }}"
    name="{{ $input_id }}"

    @include('shared.input_required_disabled_style')
>{{ isset($element) ? $element->$element_descr_key : '' }}</textarea>
