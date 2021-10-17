<table class="table table-bordered border-secondary text-center text-dark align-middle">
    <thead>
    <tr>
        <th scope="col"></th>
        @if ($current_page == $pages['MANAGER'])
            <th scope="col">@lang('labels.tech_tab')</th>
        @endif
        <th scope="col">@lang('labels.date')</th>
        <th scope="col">@lang('labels.from')</th>
        <th scope="col">@lang('labels.description')</th>
        <th scope="col">@lang('labels.costumer')</th>
        <th scope="col">@lang('labels.order')</th>
        <th scope="col">@lang('labels.duration')</th>
        @if ($current_page == $pages['MANAGER'])
            <th scope="col">@lang('labels.billable_duration')</th>
        @endif
        <th scope="col">@lang('labels.state')</th>
        @if($current_page == $pages['MANAGER'])
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

        <tr id="activity_row_{{ $activity->id }}" data-bill="{{ $activity->fatturata }}">
            <td id="select_{{ $activity->id }}">
                <div class="d-flex justify-content-center">
                    <input id="check_select_{{ $activity->id }}" class="form-check" type="checkbox">
                </div>
            </td>
            @if($current_page == $pages['MANAGER'])
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
            @if ($current_page == $pages['MANAGER'])
                <td id="billable_duration_{{ $activity->id }}">
                    <div class="d-flex justify-content-center">
                        <input id="billable_duration_input_{{ $activity->id }}" class="form-control"
                               type="time"
                               value="{{ $activity->durata_fatturabile }}"
                               style="max-width: 100px">
                        <div id="wait_change_billable_duration_{{ $activity->id }}"
                             class="spinner-border spinner-border-sm text-success"
                             role="status"
                             style="display: none;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </td>
            @endif
            <td id="state_{{ $activity->id }}"
                data-state-id="{{ $activity->stato_attivita_id }}">{{ $activity->descrizione_stato_attivita }}</td>
            @if($current_page == $pages['MANAGER'])
                <td id="billing_state_{{ $activity->id }}">
                    <div class="d-flex align-content-center">
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
                        <div id="wait_change_billing_{{ $activity->id }}"
                             class="spinner-border spinner-border-sm text-success"
                             role="status"
                             style="display: none;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </td>
        @endif

        <!-- Bottone rapportino -->
            <td>
                @if($activity->rapportino_cliente && $activity->rapportino_commessa)
                    <button id="report_{{ $activity->id }}" class="btn pt-0">
                        @if($activity->rapportino_attivita)
                            <i class="bi bi-clipboard-check text-success"></i>
                        @else
                            <i class="bi bi-clipboard text-primary"></i>
                        @endif
                    </button>
                    <div id="wait_{{ $activity->id }}" class="spinner-border spinner-border-sm text-success"
                         role="status"
                         style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                @else
                    <a id="report_{{ $activity->id }}" class="btn pt-0 disabled">
                        <i class="bi bi-clipboard-x text-danger"></i>
                    </a>
                @endif
            </td>

            <!--Bottone visualizza-->
            @include('activity.button.view')

            <!--Bottone modifica-->
            <td>
                <a id="edit_{{ $activity->id }}" class="btn pt-0"
                   href="{{ route('activity.edit', ['activity' => $activity->id]) }}">
                    <i class="bi bi-pencil text-warning"></i>
                </a>
            </td>

            <!--Bottone elimina-->
            <td>
                <a id="delete_{{ $activity->id }}" class="btn pt-0"
                   href="{{ route('activity.destroy.confirm', ['id' => $activity->id]) }}">
                    <i class="bi bi-trash text-danger"></i>
                </a>
            </td>
        </tr>
    @endforeach

    </tbody>

    <script>
        @if($current_page == $pages['MANAGER'])
        manager_script();
        @else
        technician_script();
        @endif
    </script>

</table>
