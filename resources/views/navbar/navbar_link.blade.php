<li id="{{ $navbar_link_id }}_lg" class="nav-item rounded-pill text-center me-0 me-md-1 d-none d-lg-inline">
    <a class="nav-link btn-outline-secondary border-secondary border-1" href="{{ $navbar_link_route }}"
       @if(isset($hide) && $hide)
       style="display: none;"
        @endif
    >
        {{ $navbar_link_text }}
    </a>
</li>

<li id="{{ $navbar_link_id }}_sm" class="nav-item rounded-pill mx-0 mx-md-1 d-inline d-lg-none">
    <a class="nav-link" href="{{ $navbar_link_route }}"
       @if(isset($hide) && $hide)
       style="display: none;"
        @endif
    >
        {{ $navbar_link_text }}
    </a>
</li>

