

<div class="container-fluid mt-2 px-1"> <!-- Ricerca e paginazione in master_table -->
    <div class="row"> <!-- Ricerca -->
        <div class="col col-8 px-0 col-md-7 col-lg-6">
            <div class="d-flex">
                <div class="p-2 d-none d-md-inline">
                    <label class="col-form-label">@lang('labels.search')</label>
                </div>
                <div class="p-2 pe-0 flex-grow-1">
                    <input id="master_search" class="form-control" type="text"
                           placeholder="@lang('labels.search_placeholder')">
                </div>
            </div>
        </div>

        <div class="col d-none d-lg-inline col-lg-3"></div> <!-- Spazio -->

        <div class="col col-4 pe-0 col-md-5 col-lg-3 pe-lg-0"> <!-- Paginazione -->
            <div class="d-flex">
                <div class="p-2 d-none d-md-inline">
                    <label class="col-form-label">@lang('labels.num_rows')</label>
                </div>
                <div class="p-2 flex-grow-1">
                    <select class="form-select" id="master_num_rows" name="num_rows">
                        <option>5</option>
                        <option>10</option>
                        <option selected>15</option>
                        <option>20</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

</div> <!-- Fine ricerca e paginazione in master table -->
