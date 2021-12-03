<div class="card border-secondary">
    <div class="card-body pb-0">
        <div class="d-flex justify-content-between">
            <div class="d-flex">
                <img src="{{ $user->photo_path }}" class="card-img rounded-pill me-3" alt="Immagine di profilo"
                     style="width: 2rem; height: 2rem;">
                <h5 class="card-title">{{ $user->nome }} {{ $user->cognome }}</h5>
            </div>
            <div class="d-flex">
                <a id="show_{{ $user->id }}" class="btn pt-0" href="{{ route('user.show', ['user' => $user->id]) }}">
                    <i class="bi bi-eye text-secondary"></i>
                </a>

                <a id="edit_{{ $user->id }}" class="btn pt-0" href="{{ route('user.edit', ['user' => $user->id]) }}">
                    <i class="bi bi-pencil text-warning"></i>
                </a>
                @if($user->num_activity > 0)
                    <a id="delete_{{ $user->id }}" class="btn pt-0 disabled" href="">
                        <i class="bi bi-trash"></i>
                    </a>
                @else
                    <a id="delete_{{ $user->id }}" class="btn pt-0"
                       href="{{ route('user.destroy', ['user' => $user->id]) }}">
                        <i class="bi bi-trash text-danger"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">

            <li class="list-group-item d-flex">
                <input id="user_email_{{ $user->id }}" class="form-control" type="email" value="{{ $user->email }}">
                @include('shared.button_confirm', ['element_id' => $user->id])
            </li>
            <li class="list-group-item">
                <div class="accordion-item mb-2">
                    <h2 class="accordion-header">
                        <button id="show_collapse_{{ $user->id }}"
                                class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse_{{ $user->id }}">
                            @lang('labels.team')
                        </button>
                    </h2>
                    <div id="collapse_{{ $user->id }}"
                         class="accordion-collapse collapse"
                         data-bs-parent="#users">
                        <div class="accordion-body">
                            <div class="row mt-2">
                                <ul class="list-group list-group-flush">
                                    @foreach($users as $user_deep)
                                        <li class="list-group-item d-flex">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       id="check_team_member_{{ $user_deep->id }}"
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
            </li>
            <li class="list-group-item">
                <div class="form-check form-switch">
                    <label for="#active_user_switch_{{ $user->id }}">@lang('labels.active') @lang('labels.user')</label>
                    @if($user->attivo == $USER_ACTIVE['ACTIVE'])
                        <input class="form-check-input" type="checkbox" id="active_user_switch_{{ $user->id }}" checked>
                    @elseif($user->attivo == $USER_ACTIVE['NOT_ACTIVE'])
                        <input class="form-check-input" type="checkbox" id="active_user_switch_{{ $user->id }}">
                    @endif
                </div>
            </li>
        </ul>
    </div>
</div>
