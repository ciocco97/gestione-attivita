@if(isset($element_list) && count($element_list) == 0)
    {{--        <i class="bi bi-info-circle-fill me-3" style="font-size: 2rem;"></i>--}}
    <div class="alert alert-primary border-primary mx-1 mx-md-2 mx-lg-3 mx-xl-5 mx-xxl-5" role="alert">
        <h4 class="alert-heading"><i class="bi bi-info-circle-fill me-3"></i>@lang('labels.info')</h4>
        <p>@lang('text.no_element_in_table_info')<i class="bi bi-emoji-laughing ms-2"></i></p>
        @if(!isset($hint) || $hint)
            <hr>
            <p class="mb-0">@lang('text.no_element_in_table_hing')</p>
        @endif
    </div>
@endif
