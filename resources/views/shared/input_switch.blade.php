@if(!isset($label) || $label && (!isset($in_line_label) || !$in_line_label))
    <label class="form-label" for="{{ $switch_id }}">{{ $element_type }}</label>
    <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top"
       title="@lang('text.report_comment')">
    </i>
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
    @if(!isset($label) || $label && isset($in_line_label) && $in_line_label)
        <label class="form-label" for="{{ $switch_id }}">{{ $element_type }}</label>
    @endif
</div>

