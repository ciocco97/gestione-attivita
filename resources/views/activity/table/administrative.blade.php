<table class="table table-bordered border-secondary text-center text-dark align-middle">
    <thead>
    <tr>
        <th scope="col"></th>
        <th scope="col">@lang('labels.billing_state')</th>
        <th scope="col">@lang('labels.tech_tab')</th>
        <th scope="col">@lang('labels.date')</th>
        <th scope="col">@lang('labels.from')</th>
        <th scope="col">@lang('labels.description')</th>
        <th scope="col">@lang('labels.costumer')</th>
        <th scope="col">@lang('labels.order')</th>
        <th scope="col">@lang('labels.billable_duration')</th>
        <th scope="col">@lang('labels.show')</th>

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
            <td id="billing_state_{{ $activity->id }}">
                {{ $activity->descrizione_stato_fatturazione }}
            </td>
            <td id="technician_{{ $activity->id }}">{{ $activity->nome }}</td>
            <td id="date_{{ $activity->id }}" class="text-nowrap">{{ $activity->data }}</td>
            <td id="startTime_{{ $activity->id }}">{{ substr($activity->ora_inizio, 0, 5) }}</td>
            <td class="fw-bold" id="desc_{{ $activity->id }}"
                style="min-width: 230px" data-bs-toggle="tooltip" data-bs-placement="right"
                title="{{ $activity->descrizione_attivita }}">
                {{ $activity->desc_attivita }}
            </td>
            <td id="costumer_{{ $activity->id }}">{{ $activity->nome_cliente }}</td>
            <td id="order_{{ $activity->id }}">{{ $activity->descrizione_commessa }}</td>

            <td id="billable_duration_{{ $activity->id }}">
                <div class="d-flex justify-content-center">
                    {{ substr($activity->durata_fatturabile, 0, 5) }}
                </div>
            </td>

            <!--Bottone visualizza-->
            @include('activity.button.view')

        </tr>
    @endforeach

    </tbody>

    <script>
        administrative_activity_script();
    </script>

</table>
