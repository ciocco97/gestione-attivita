@if(!isset($label) || $label)
    <label class="form-label" for="{{ $switch_id }}">{{ $element_type }}</label>
@endif

<div class="form-check form-switch">
    <input
        class="form-check-input"
        type="checkbox"
        id="{{ $switch_id }}"
        name="{{ $switch_id }}"

           @if(isset($element) && $element)
           checked
        @endif

        @include('shared.input_required_disabled_style')
    >
</div>
