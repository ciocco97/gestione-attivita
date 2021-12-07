<div class="card border-secondary">
    <div class="card-body pb-0">
        <div class="d-flex justify-content-between">
            <div class="d-flex">
                <img src="{{ $user->photo_path }}" class="card-img rounded-pill me-3" alt="Immagine di profilo"
                     style="width: 2rem; height: 2rem;">
                <h5 class="card-title">{{ $user->nome }} {{ $user->cognome }}</h5>
            </div>
            <div class="d-flex">
                @if($user->num_activity > 0)
                    @include('shared.button_delete', ['element_id' => $user->id, 'element_type' => 'user', 'disabled' => true])
                @else
                    @include('shared.button_delete', ['element_id' => $user->id, 'element_type' => 'user', 'disabled' => true])
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">

            <li class="list-group-item d-flex">
                @include('shared.input_general', ['input_id' => 'user_email_' . $user->id, 'input_type' => 'email', 'element_descr_key' => 'email', 'element' => $user, 'placeholder' => __('labels.type'). ' ' .__('labels.email'), 'data' => ['user-id' => $user->id], 'required' => false, 'label' => false])
                @include('shared.button_confirm', ['element_id' => $user->id])
            </li>
            <li class="list-group-item">
                @include('user.card.accordion_team')
            </li>
            <li class="list-group-item">
                @include('user.card.accordion_role')
            </li>
            <li class="list-group-item">
                <div class="d-flex">
                    @include('shared.input_switch', ['element_type' => __('labels.active_user'), 'switch_id' => 'active_user_switch_' . $user->id, 'element' => $user->attivo == $USER_ACTIVE['ACTIVE'], 'label' => true, 'in_line_label' => true])
                    @include('shared.spinner_wait', ['element_id' => 'active_user_switch_' . $user->id])
                </div>
            </li>
        </ul>
    </div>
</div>
