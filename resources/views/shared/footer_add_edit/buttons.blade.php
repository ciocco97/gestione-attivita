<div class="row pt-3 pt-lg-4 mb-3"> <!-- Bottoni ai piedi della form -->
    <div class="col-lg-6"></div>
    @if($method == $EDIT)
    <div class="col-sm-6 col-lg-3 mb-2 mb-md-0">
        <a class="btn btn-secondary w-100"
           href="{{ $previous_url }}">
            <i class="bi bi-x-square me-2"></i>@lang('labels.cancel')
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <button class="btn btn-primary w-100" type="submit">
            <i class="bi bi-pencil me-2"></i>@lang('labels.edit')
        </button>
    </div>
    @elseif($method == $SHOW)
    <div class="col-sm-6 col-lg-3 mb-2 mb-md-0"></div>
    <div class="col-sm-6 col-lg-3">
        <a class="btn btn-secondary w-100"
           href="{{ $previous_url }}">
            <i class="bi bi-arrow-bar-left me-2"></i>@lang('labels.back')
        </a>
    </div>

    @elseif($method == $DELETE)
    <div class="col-sm-6 col-lg-3 mb-2 mb-md-0">
        <a class="btn btn-secondary w-100"
           href="{{ $previous_url }}">
            <i class="bi bi-arrow-bar-left me-2"></i>@lang('labels.cancel')
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <button class="btn btn-danger w-100" type="submit">
            <i class="bi bi-trash me-2"></i>@lang('labels.delete')
        </button>
    </div>

    @else
    <div class="col-sm-6 col-lg-3 mb-2 mb-md-0">
        <a class="btn btn-danger w-100" href="{{ $previous_url }}">
            <i class="bi bi-arrow-bar-left me-2"></i>@lang('labels.cancel')
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <button class="btn btn-primary w-100" type="submit">
            <i class="bi bi-journal-plus me-2"></i>@lang('labels.add')
        </button>
    </div>

    @endif

</div>
