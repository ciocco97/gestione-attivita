<button id="activities_change_btn" class="btn btn-outline-success rounded-end border-start-0 px-1"
        data-bs-toggle="dropdown" style="display: none;">
    @if($current_page == $pages['TECHNICIAN']) {{-- Technician --}}
    @lang('labels.change') @lang('labels.state')
    @endif
    <i class="bi bi-three-dots-vertical"></i>
</button>
<ul class="dropdown-menu">
    @if($current_page == $pages['MANAGER'])
        <li><h6 class="dropdown-header">@lang('labels.other_states')</h6></li>
    @elseif($current_page == $pages['TECHNICIAN'])
        <li><h6 class="dropdown-header">@lang('labels.states')</h6></li>
    @endif
    @if($current_page == $pages['ADMINISTRATIVE'])
        <li><h6 class="dropdown-header">@lang('labels.other_state')</h6></li>
        <li>
            <button id="activities_change_1" class="dropdown-item" data-state="1">
                @lang('labels.set') @lang('labels.not') @lang('labels.accounted')
            </button>
        </li>
    @else
        @foreach($states as $state)
            @if($state->id != 4)
                <li>
                    <button id="activities_change_{{ $state->id }}" class="dropdown-item"
                            data-state="{{ $state->id }}">
                        @lang('labels.set') {{ $state->descrizione_stato_attivita }}
                    </button>
                </li>
            @endif
        @endforeach
    @endif
</ul>
