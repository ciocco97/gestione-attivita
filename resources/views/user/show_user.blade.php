@extends('layouts.master')

@section('title', 'dipende, da mettere a posto')

@section('navbar')
    @include('navbar.nav')
@endsection

@section('body')
    <div class="container mt-lg-4"> <!-- Corpo della pagina -->
        <div class="row">
            <div class="col-md-1 col-lg-2 col-xl-1"></div>

            <div class="col-md-10 col-lg-8 col-xl-10">

                <form name="user"

                      @if($method == $EDIT)
                      action="{{ route('user.update', ['id' => $user->id]) }}" method="post">
                    @elseif($method == $SHOW)
                        >
                    @elseif($method == $DELETE)
                        action="{{ route('user.destroy', ['id' => $user->id]) }}" method="delete">
                    @else
                        action="{{ route('user.store') }}" method="post">
                    @endif

                    @csrf

                    @if($method == $EDIT)
                        <h3>@lang('labels.edit') @lang('labels.tech')</h3>
                    @elseif($method == $SHOW)
                        <h3>@lang('labels.show') @lang('labels.tech')</h3>
                    @elseif($method == $DELETE)
                        <h3>@lang('labels.delete') @lang('labels.tech')</h3>
                    @else
                        <h3>@lang('labels.add') @lang('labels.tech')</h3>
                    @endif


                    <div class="row mb-md-2 mt-3"> <!-- Primi tre campi -->

                        <div class="col-md-6 mb-2 mb-md-0">
                            @include('shared.input_general', ['input_id' => 'name', 'element_type' => __('labels.name'), 'input_type' => 'text', 'element_descr_key' => 'nome', 'element' => $method == $ADD ? null : $user, 'placeholder' => __('labels.type') . ' ' . __('labels.name')])
                        </div>
                        <div class="col-md-6 mb-2 mb-md-0">
                            @include('shared.input_general', ['input_id' => 'surname', 'element_type' => __('labels.surname'), 'input_type' => 'text', 'element_descr_key' => 'cognome', 'element' => $method == $ADD ? null : $user, 'placeholder' => __('labels.type') . ' ' . __('labels.surname')])
                        </div>

                    </div> <!-- Fine primi 2 campi -->

                    <div class="row mb-md-2 mt-3"> <!-- Primi tre campi -->
                        <div class="col-md-6 mb-2 mb-md-0">
                            @include('shared.input_general', ['input_id' => 'email', 'element_type' => __('labels.email'), 'input_type' => 'email', 'element_descr_key' => 'email', 'element' => $method == $ADD ? null : $user, 'placeholder' => __('labels.type') . ' ' . __('labels.email')])
                        </div>
                    </div> <!-- Fine primi 2 campi -->

                    @include('shared.footer_add_edit.buttons', ['icon_code' => 'bi-file-earmark-person-fill'])

                </form>
            </div>

            <div class="col-md-1 col-lg-2 col-xl-1"></div>
        </div>
    </div>

    <script>
        $("[id^=administrator_nav_tab_]").children().show()
    </script>

@endsection

