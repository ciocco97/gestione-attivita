<div class="accordion-item mb-2">
    <h2 class="accordion-header">
        <button id="show_collapse_team_{{ $user->id }}"
                class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapse_team_{{ $user->id }}">
            @lang('labels.team')
            <div id="wait_change_user_team_{{ $user->id }}" class="spinner-border spinner-border-sm text-success ms-1"
                 role="status" style="display: none;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </button>
    </h2>
    <div id="collapse_team_{{ $user->id }}"
         class="accordion-collapse collapse"
         data-bs-parent="#accordion_parent">
        <div class="accordion-body">
            <div class="row mt-2">
                <ul class="list-group list-group-flush">
                    @foreach($users as $user_deep)
                        <li class="list-group-item d-flex">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       id="check_team_member_{{ $user->id }}_{{ $user_deep->id }}"
                                       data-manager-id="{{ $user->id }}"
                                       data-team-member-id="{{ $user_deep->id }}"
                                       @if($user->team_ids && in_array($user_deep->id, $user->team_ids))
                                       checked
                                    @endif>
                                <label class="form-check-label"
                                       for="#check_team_member_{{ $user_deep->id }}">
                                    {{ $user_deep->nome }} {{ $user_deep->cognome }}
                                </label>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
</div>
