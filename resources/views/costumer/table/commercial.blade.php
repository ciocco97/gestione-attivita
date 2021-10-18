<table
    class="table table-bordered border-secondary text-center text-dark align-middle">
    <thead>
    <tr>
        <th scope="col">@lang('labels.order')</th>
        <th scope="col">@lang('labels.state') @lang('labels.order')</th>
        <th scope="col">@lang('labels.report')</th>
    </tr>
    </thead>

    <tbody id="master_tbody_{{ $costumer_orders_nums[0]->id }}">
    @foreach($costumer_orders_nums[1] as $costumer_order)
        <tr id="order_{{ $costumer_order->id }}">
            <td>
                {{ $costumer_order->descrizione_commessa }}
            </td>
            <td>
                <div class="d-flex justify-content-center">
                    <select class="form-select" id="order_State_select_{{ $costumer_order->id }}"
                            style="width: auto;">
                        @foreach($order_states as $order_state)
                            @if($order_state->id == $costumer_order->stato_commessa_id)
                                <option value="{{ $order_state->id }}"
                                        selected>{{ $order_state->descrizione_stato_commessa }}</option>
                            @else
                                <option
                                    value="{{ $order_state->id }}">{{ $order_state->descrizione_stato_commessa }}</option>
                            @endif
                        @endforeach
                    </select>
                    <div id="wait_change_order_state_{{ $costumer_order->id }}"
                         class="spinner-border spinner-border-sm text-success"
                         role="status"
                         style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </td>
            <td>
                <div class="d-flex justify-content-center">
                <div class="form-check form-switch">
                    @if($costumer_order->rapportino_commessa)
                        <input class="form-check-input" type="checkbox" id="report_switch_{{ $costumer_order->id }}" checked>
                    @else
                        <input class="form-check-input" type="checkbox" id="report_switch_{{ $costumer_order->id }}">
                    @endif
                </div>
                </div>

            </td>
        </tr>
    @endforeach
    </tbody>
</table>
