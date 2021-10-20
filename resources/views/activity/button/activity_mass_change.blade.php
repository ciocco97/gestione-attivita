<button id="activities_change_main" data-state="{{ $value }}" class="btn btn-outline-success border-end-0 pe-1"
        style="display: none;">
    @if($current_page == $pages['MANAGER'])
        <i class="bi bi-pencil me-2"></i>@lang('labels.approve')
    @elseif($current_page == $pages['ADMINISTRATIVE'])
        <i class="bi bi-pencil me-2"></i>@lang('labels.set') @lang('labels.accounted')
    @endif
</button>
