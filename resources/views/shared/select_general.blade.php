@if(!isset($label) || $label)
    <label class="form-label" for="{{ $select_id }}">{{ $element_type }}</label>
@endif

<select class="form-select" id="{{ $select_id }}" name="{{ $select_id }}"
    @include('shared.input_required_disabled_style')
>
    @if(isset($element_type))
        <option
            @if(isset($default_value))
            value="{{$default_value}}"
            @else
            value="" disabled
            @endif
            selected hidden>@lang('labels.select') {{ $element_type }}
        </option>
    @endif
    @if($element_list == null)
        <option value="{{ $current_element->id }}"
                selected>{{ $current_element->$element_descr_key }}</option>
    @else
        @foreach($element_list as $element)
            @if(isset($current_element) && ( (is_int($current_element) && $element->id == $current_element) || (!is_int($current_element) && $element->id == $current_element->id) ) )
                <option value="{{ $element->id }}"
                        selected>{{ $element->$element_descr_key }}</option>
            @else
                <option value="{{ $element->id }}">{{ $element->$element_descr_key }}</option>
            @endif
        @endforeach
    @endif

</select>
