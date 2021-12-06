<div class="accordion-item mb-2">
    <h2 class="accordion-header">
        <button id="show_collapse_roles_{{ $user->id }}"
                class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapse_roles_{{ $user->id }}">
            @lang('labels.roles')
            <div id="wait_change_user_roles_{{ $user->id }}" class="spinner-border spinner-border-sm text-success ms-1" role="status" style="display: none;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </button>
    </h2>
    <div id="collapse_roles_{{ $user->id }}"
         class="accordion-collapse collapse"
         data-bs-parent="#accordion_parent">
        <div class="accordion-body">
            <div class="row mt-2">
                <ul class="list-group list-group-flush">
                    @foreach($user_roles as $role)
                        <li class="list-group-item d-flex">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       id="check_role_{{ $user->id }}_{{ $role->id }}"
                                       data-user-id="{{ $user->id }}"
                                       data-role-id="{{ $role->id }}"
                                       @if($user->role_ids && in_array($role->id, $user->role_ids))
                                       checked
                                       @endif
                                       @if($role->id == $USER_ROLES['MANAGER'])
                                       disabled
                                       @if(count($user->team_ids) > 0)
                                       checked
                                    @endif
                                    @endif
                                >
                                <label class="form-check-label" for="#check_role_{{ $user->id }}_{{ $role->id }}">
                                    {{ $role->descrizione_ruolo }}
                                </label>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
</div>

