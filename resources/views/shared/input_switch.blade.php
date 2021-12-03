@if(isset($label))
    @if($label)
        <label class="form-label" for="{{ $element_id }}">{{ $element_type }}</label>
    @endif
@else
    <label class="form-label" for="{{ $element_id }}">{{ $element_type }}</label>
@endif
<div class="form-check form-switch">
    <input
        class="form-check-input"
        type="checkbox"
        id="{{ $element_id }}"
        name="{{ $element_id }}"

           @if(isset($element) && $element)
           checked
        @endif

        @include('shared.input_required_disabled_style')
    >
</div>
