<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3 sticky-top">
    <div class="container-fluid px-lg-5">
        <a class="order-lg-0 navbar-brand d-none d-sm-inline" href="{{ route('user.login') }}"><i
                class="bi bi-house-door me-2"></i>@lang('labels.main_title')</a>
        <a class="order-lg-0 navbar-brand d-sm-none" href="{{ route('user.login') }}"><i
                class="bi bi-house-door"></i></a>
        <div class="order-lg-2 dropdown">
            <span class="d-none d-lg-inline">
                @include('navbar.flag', ['lang_code' => 'it'])
                @include('navbar.flag', ['lang_code' => 'en'])
            </span>
            @if(isset($username))
                <button class="btn btm-sm dropdown-toggle text-light" id="dropdownUser" data-bs-toggle="dropdown">
                    <i class="bi bi-person-check me-1"></i>{{ $username }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="dropdownUser">
                    <li>
                        <a class="dropdown-item" href="{{ route('user.choose.password') }}">
                            <i class="bi bi-key me-2"></i>@lang('labels.change') password
                        </a>
                    </li>
                    @if(in_array($ROLES['ADMINISTRATOR'], $user_roles))
                        <li>
                            <a class="dropdown-item" href="{{ route('user.index') }}">
                                <i class="bi bi-award-fill me-2"></i>@lang('labels.admin')
                            </a>
                        </li>
                    @endif
                    <li>
                        <a class="dropdown-item" href="{{ route('user.logout') }}">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                    <li class="dropdown-divider d-lg-none"></li>
                    <li class="d-lg-none">
                        <div class="span ms-3">
                            @include('navbar.flag', ['lang_code' => 'it'])
                            @include('navbar.flag', ['lang_code' => 'en'])
                        </div>
                    </li>
                </ul>
            @endif
        </div>

        @if(isset($username))
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="order-lg-1 collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav">
                    @if(in_array($ROLES['COMMERCIAL'], $user_roles))
                        @include('navbar.navbar_link', ['navbar_link_id' => "commercial_nav_tab", 'navbar_link_route' => route('costumer.index'), 'navbar_link_text' => __('labels.commercial_tab')])
                    @endif

                    @if(in_array($ROLES['ADMINISTRATIVE'], $user_roles))
                        @include('navbar.navbar_link', ['navbar_link_id' => "administrative_nav_tab", 'navbar_link_route' => route('administrative.index'), 'navbar_link_text' => __('labels.administrative_tab')])
                    @endif

                    @if(in_array($ROLES['MANAGER'], $user_roles))
                        @include('navbar.navbar_link', ['navbar_link_id' => "manager_nav_tab", 'navbar_link_route' => route('manager.index'), 'navbar_link_text' => __('labels.manager_tab')])
                    @endif

                    @include('navbar.navbar_link', ['navbar_link_id' => "technician_nav_tab", 'navbar_link_route' => route('activity.index'), 'navbar_link_text' => __('labels.tech_tab')])

                </ul>

            </div>
        @endif

    </div>
</nav>
