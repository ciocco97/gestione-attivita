@extends('layouts.tma')

@section('title', 'Gestione : cliente')

@section('navbar')
    @include('navbar.nav')
@endsection

@section('actions')
    @include('shared.button_filter')
    <a class="btn btn-outline-success" href="{{ route('costumer.create') }}" id="add_costumer_button" title="Ctrl + i">
        <i class="bi bi-person-plus-fill me-2"></i>@lang('labels.add') @lang('labels.costumer')
    </a>
    <a class="btn btn-outline-primary" href="{{ route('order.create') }}" id="add_order_button" title="Ctrl + m">
        <i class="bi bi-file-earmark-ruled-fill me-2"></i>@lang('labels.add') @lang('labels.order')
    </a>
@endsection

@section('filters')
    @csrf
    <div class="row">
        <div class="col-md-6"> <!-- Prima coppia/terna di filtri -->
            @include('shared.filter.costumer')
        </div>

        <div class="col-md-6"> <!-- Seconda coppia di filtri -->
            @include('shared.filter.order_state')
        </div>
        @include('shared.filter.submit')
    </div>
@endsection

@section('main')

    <div id="costumers" class="row row-cols-1 row-cols-xxl-2 g-3 mt-2">
        @foreach($costumers_infos as $num => $costumer_infos)
            <div id="costumer_number_{{ $costumer_infos->id }}">
                @include('costumer.table.costumer')
            </div>
        @endforeach
    </div>
    @include('shared.alert_no_element', ['element_list' => $costumers_infos])

    <script>
        commercial_script();
    </script>

@endsection
