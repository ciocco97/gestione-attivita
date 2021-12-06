<div class="card border-secondary">
    <div class="card-body"><!-- rgba(0,36,255,0.15) -->
        <div class="d-flex justify-content-between">
            <h5 class="card-title">{{ $costumer_infos->nome }}</h5>
            <div class="d-flex d-inline">
                <td>
                    @include('shared.button_view', ['element_id' => $costumer_infos->id, 'element_type' => 'costumer'])
                </td>

                <td>
                    @include('shared.button_edit', ['element_id' => $costumer_infos->id, 'element_type' => 'costumer'])
                </td>
                @if($costumer_infos->num_attivita > 0)
                    @include('shared.button_delete', ['element_id' => $costumer_infos->id, 'element_type' => 'costumer', 'disabled' => true])
                @else
                    @include('shared.button_delete', ['element_id' => $costumer_infos->id, 'element_type' => 'costumer'])
                @endif
            </div>
        </div>

        <div>

            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="title_{{ $costumer_infos->id }}">
                    <button id="show_collapse_{{ $costumer_infos->id }}"
                            class="accordion-button collapsed"
                            type="button" data-costumer-id="{{ $costumer_infos->id }}"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse_{{ $costumer_infos->id }}">
                        @lang('labels.num_orders'): {{ count($costumer_infos->commesse) }}
                    </button>
                </h2>
                <div id="collapse_{{ $costumer_infos->id }}"
                     class="accordion-collapse collapse"
                     data-bs-parent="#costumers">
                    <div class="accordion-body">

                        <div class="row mt-2">
                            <div class="table-responsive">
                                @include('costumer.table.orders')
                                @include('shared.alert_no_element', ['element_list' => $costumer_infos->commesse])
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer text-muted">
                <p class="mb-0">
                    @lang('labels.activities'): {{ $costumer_infos->num_attivita }}
                    <span class="px-2"></span>
                    @lang('labels.accounted'): {{ $costumer_infos->num_attivita_contabilizzate }}
                </p>
            </div>

        </div>
    </div>
</div>
