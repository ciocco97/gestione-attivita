<nav class="navbar navbar-expand-lg navbar-light mb-1 mb-md-0"> <!-- Intestazione -->
    <div class="container">
        <a class="order-lg-0 navbar-brand d-none d-sm-inline" href="{{ route('user.login') }}"><i class="bi bi-house-door me-2"></i>@lang('labels.main_title')</a>
        <a class="order-lg-0 navbar-brand d-sm-none" href="{{ route('user.login') }}"><i class="bi bi-house-door"></i></a>
        <div class="order-lg-2 dropdown">
            <span class="d-none d-lg-inline">
                <a class="inline text-decoration-none text-dark me-2" href="{{ route('lang.change', ['lang' => 'it']) }}">
                    <i class="bi bi-flag me-2"></i>it
                </a>
                <a class="inline text-decoration-none text-dark" href="{{ route('lang.change', ['lang' => 'en']) }}">
                    <i class="bi bi-flag-fill me-2"></i>en
                </a>
            </span>
            <button class="btn btm-sm dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown">
                <i class="bi bi-person-check me-1"></i>{{ $username }}
            </button>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                <li>
                    <a class="dropdown-item" href="{{ route('user.logout') }}">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </li>
                <li class="dropdown-divider d-lg-none"></li>
                <li class="d-lg-none">
                    <div class="span ms-3">
                        <a class="inline text-decoration-none text-dark me-2"
                           href="{{ route('lang.change', ['lang' => 'it']) }}">
                            <i class="bi bi-flag me-2"></i>it
                        </a>
                        <a class="inline text-decoration-none text-dark"
                           href="{{ route('lang.change', ['lang' => 'en']) }}">
                            <i class="bi bi-flag-fill me-2"></i>en
                        </a>

                    </div>
                </li>
            </ul>

        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="order-lg-1 collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a id="commercial" class="nav-link" href="">@lang('labels.commercial_tab')</a>
                </li>

                <li class="nav-item">
                    <a id="admin" class="nav-link" href="">@lang('labels.admin_tab')</a>
                </li>

                <li class="nav-item">
                    <a id="manager" class="nav-link" href="">@lang('labels.manager_tab')</a>
                </li>

                <li class="nav-item">
                    <a id="technician" class="nav-link" href="{{ route('activity.index') }}" active>@lang('labels.tech_tab')</a>
                </li>

            </ul>

        </div>

    </div>

</nav>
