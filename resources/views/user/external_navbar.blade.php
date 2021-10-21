<nav class="navbar navbar-light"> <!-- Intestazione -->
    <div class="container">
        <a class="navbar-brand" href="{{ route('user.login') }}">@lang('labels.main_title')</a>
        <span>
                <a class="inline text-decoration-none text-dark me-2" href="{{ route('lang.change', ['lang' => 'it']) }}">
                    <i class="bi bi-flag me-2"></i>it
                </a>
                <a class="inline text-decoration-none text-dark" href="{{ route('lang.change', ['lang' => 'en']) }}">
                    <i class="bi bi-flag-fill me-2"></i>en
                </a>

            </span>
    </div>
</nav>
