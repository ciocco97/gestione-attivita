
    <thead>
    <tr>
        <th scope="col">@include('shared.checkbox_universal_activity')</th>
        @if ($current_page == $PAGES['MANAGER'])
            <th scope="col">@lang('labels.tech_tab')</th>
        @endif
        <th scope="col">@lang('labels.date')</th>
        <th scope="col">@lang('labels.from')</th>
        <th scope="col">@lang('labels.description')</th>
        <th scope="col">@lang('labels.costumer')</th>
        <th scope="col">@lang('labels.order')</th>
        <th scope="col">@lang('labels.duration')</th>
        @if ($current_page == $PAGES['MANAGER'])
            <th scope="col">@lang('labels.billable_duration')</th>
        @endif
        <th scope="col">@lang('labels.state')</th>
        @if($current_page == $PAGES['MANAGER'])
            <th scope="col">@lang('labels.billing_state')</th>
        @endif
        <th scope="col">@lang('labels.report')</th>
        <th scope="col">@lang('labels.show')</th>
        <th scope="col">@lang('labels.edit')</th>
        <th scope="col">@lang('labels.delete')</th>

    </tr>
    </thead>
    <tbody id="master_tbody">

    @foreach($activities as $activity)

        <tr id="activity_row_{{ $activity->id }}" data-accounted="{{ $activity->contabilizzata }}">
            <td id="select_{{ $activity->id }}">
                <div class="d-flex justify-content-center">
                    <input id="check_select_{{ $activity->id }}" class="form-check" type="checkbox">
                </div>
            </td>
            @if($current_page == $PAGES['MANAGER'])
                <td id="technician_{{ $activity->id }}">{{ $activity->nome }}</td>
            @endif
            <td id="date_{{ $activity->id }}" class="text-nowrap">{{ $activity->data }}</td>
            <td id="startTime_{{ $activity->id }}">{{ substr($activity->ora_inizio, 0, 5) }}</td>
            <td class="fw-bold" id="desc_{{ $activity->id }}" data-bs-toggle="tooltip"
                data-bs-placement="right"
                title="{{ $activity->descrizione_attivita }}">
                {{ $activity->desc_attivita }}
            </td>
            <td id="costumer_{{ $activity->id }}">{{ $activity->nome_cliente }}</td>
            <td id="order_{{ $activity->id }}">{{ $activity->descrizione_commessa }}</td>
            <td id="duration_{{ $activity->id }}">{{ substr($activity->durata, 0, 5) }}</td>

            @if ($current_page == $PAGES['MANAGER'])
                <td id="billable_duration_{{ $activity->id }}">
                    <div class="d-flex justify-content-center">
                        <input id="billable_duration_input_{{ $activity->id }}" class="form-control"
                               type="time"
                               value="{{ $activity->durata_fatturabile }}"
                               style="max-width: 100px">
                        @include('shared.spinner_wait', ['element_id' => 'billable_duration_input_' . $activity->id])
                    </div>
                </td>
            @endif

            <td id="state_{{ $activity->id }}"
                data-state-id="{{ $activity->stato_attivita_id }}">{{ $activity->descrizione_stato_attivita }}</td>

            @if($current_page == $PAGES['MANAGER'])
                <td id="billing_state_{{ $activity->id }}">
                    <div class="d-flex justify-content-center">
                        <select class="form-select" id="billing_state_select_{{ $activity->id }}"
                                style="width: auto;">
                            @foreach($billing_states as $billing_state)
                                @if($billing_state->id == $activity->stato_fatturazione_id)
                                    <option value="{{ $billing_state->id }}"
                                            selected>{{ $billing_state->descrizione_stato_fatturazione }}</option>
                                @else
                                    <option
                                        value="{{ $billing_state->id }}">{{ $billing_state->descrizione_stato_fatturazione }}</option>
                                @endif
                            @endforeach
                        </select>
                        @include('shared.spinner_wait', ['element_id' => 'billing_state_select_' . $activity->id])
                    </div>
                </td>
        @endif

        <!-- Bottone rapportino -->
            <td>
                @include('shared.button_report')
            </td>

            <!--Bottone visualizza-->
            <td>
                @include('shared.button_view', ['element_id' => $activity->id, 'element_type' => 'activity'])
            </td>

            <!--Bottone modifica-->
            <td>
                @include('shared.button_edit', ['element_id' => $activity->id, 'element_type' => 'activity'])
            </td>

            <!--Bottone elimina-->
            <td>
                @include('shared.button_delete', ['element_id' => $activity->id, 'element_type' => 'activity'])
            </td>
        </tr>
    @endforeach

    </tbody>

    <script>
        @if($current_page == $PAGES['MANAGER'])
        manager_script();
        @else
        technician_script();
        @endif
    </script>

