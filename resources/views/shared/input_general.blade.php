@if(!isset($label) || $label)
    <label class="form-label" for="{{ $input_id }}">{{ $element_type }}</label>
@endif
@if(isset($compute_button) && $compute_button && isset($method) && $method != $SHOW && $method != $DELETE)
    <div class="d-flex">
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

            @if(isset($data))
            @foreach($data as $name => $val)
            data-{{ $name }}="{{ $val }}"
            @endforeach
            @endif

            @include('shared.input_required_disabled_style')

        >
        @if(isset($compute_button) && $compute_button && isset($method) && $method != $SHOW && $method != $DELETE)
            @include('shared.button_compute')
    </div>
@endif
