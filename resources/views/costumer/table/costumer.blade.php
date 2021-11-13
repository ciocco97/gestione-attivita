<div class="card border-secondary">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h5 class="card-title">{{ $costumer_orders_nums[0]->nome }}</h5>
            <div class="d-flex d-inline">
                <td>
                    <a id="show_{{ $costumer_orders_nums[0]->id }}" class="btn pt-0"
                       href="{{ route('costumer.show', ['costumer' => $costumer_orders_nums[0]->id]) }}">
                        <i class="bi bi-eye text-secondary"></i>
                    </a>
                </td>

                <td>
                    <a id="edit_{{ $costumer_orders_nums[0]->id }}" class="btn pt-0"
                       href="{{ route('costumer.edit', ['costumer' => $costumer_orders_nums[0]->id]) }}">
                        <i class="bi bi-pencil text-warning"></i>
                    </a>
                </td>
                @if($costumer_orders_nums[2]>0)
                    <td>
                        <a id="delete_{{ $costumer_orders_nums[0]->id }}"
                           class="btn pt-0 disabled"
                           href="">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                @else
                    <td>
                        <a id="delete_{{ $costumer_orders_nums[0]->id }}" class="btn pt-0"
                           href="{{ route('costumer.destroy.confirm', ['id' => $costumer_orders_nums[0]->id]) }}">
                            <i class="bi bi-trash text-danger"></i>
                        </a>
                    </td>
                @endif
            </div>
        </div>

        <div>

            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="title_{{ $costumer_orders_nums[0]->id }}">
                    <button id="show_collapse_{{ $costumer_orders_nums[0]->id }}"
                            class="accordion-button collapsed"
                            type="button" data-costumer-id="{{ $costumer_orders_nums[0]->id }}"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse_{{ $costumer_orders_nums[0]->id }}">
                        @lang('labels.num_orders'): {{ count($costumer_orders_nums[1]) }}
                    </button>
                </h2>
                <div id="collapse_{{ $costumer_orders_nums[0]->id }}"
                     class="accordion-collapse collapse"
                     data-bs-parent="#costumers">
                    <div class="accordion-body">

                        <div class="row mt-2">
                            <div class="table-responsive">
                                @include('costumer.table.orders')
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer text-muted">
                <p class="mb-0">
                    @lang('labels.activities'): {{ $costumer_orders_nums[2] }}
                    <span class="px-2"></span>
                    @lang('labels.accounted'): {{ $costumer_orders_nums[3] }}
                </p>
            </div>

        </div>
    </div>
</div>
