<table
    class="table table-bordered border-secondary text-center text-dark align-middle">
    <thead>
    <tr>
        <th scope="col">@lang('labels.order')</th>
        <th scope="col">@lang('labels.activities')</th>
        <th scope="col">@lang('labels.state') @lang('labels.order')</th>
        <th scope="col">@lang('labels.report')</th>
        <th scope="col">@lang('labels.edit')</th>
        <th scope="col">@lang('labels.delete')</th>
    </tr>
    </thead>

    <tbody id="master_tbody_{{ $costumer_infos->id }}">
    @foreach($costumer_infos->commesse as $costumer_order)
        <tr id="order_{{ $costumer_order->id }}">
            <td>
                {{ $costumer_order->descrizione_commessa }}
            </td>
            <td>
                {{ $costumer_order->attivita_sum_durata }}h
            </td>
            <td>
                <div class="d-flex justify-content-center">
                    @include('shared.select_general', ['label' => false, 'select_id' => 'order_state_select_' . $costumer_order->id, 'element_list' => $order_states, 'element_descr_key' => 'descrizione_stato_commessa', 'current_element' => $costumer_order->stato_commessa_id, 'style' => 'width: auto;'])
                    @include('shared.spinner_wait', ['element_id' => 'order_state_select_' . $costumer_order->id])
                </div>
            </td>
            <td>
                <div class="d-flex justify-content-center">
                    @include('shared.input_switch', ['element' => $costumer_order->rapportino_commessa, 'switch_id' => 'report_switch_' . $costumer_order->id, 'label' => false])

                    @include('shared.spinner_wait', ['element_id' => 'report_switch_' . $costumer_order->id])
                </div>

            </td>
            <td>
                @include('shared.button_edit', ['element_id' => $costumer_order->id, 'element_type' => 'order'])
            </td>
            <td>
                @if($costumer_order->num_attivita != 0)
                    @include('shared.button_delete', ['element_id' => $costumer_order->id, 'element_type' => 'order', 'disabled' => true])
                @else
                    @include('shared.button_delete', ['element_id' => $costumer_order->id, 'element_type' => 'order'])
                @endif

            </td>
        </tr>
    @endforeach
    </tbody>
</table>
