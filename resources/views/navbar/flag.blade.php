<a class="inline text-decoration-none text-light me-2"
   href="{{ route('lang.change', ['lang' => $lang_code]) }}">
    @if($LANG == $lang_code)
        <i class="bi bi-flag-fill me-2"></i>{{ $lang_code }}
    @else
        <i class="bi bi-flag me-2"></i>{{ $lang_code }}
    @endif
</a>
