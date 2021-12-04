@extends('layouts.tma')

@section('title', 'Gestione : cliente')

@section('navbar')
    @include('navbar.nav')
@endsection

@section('actions')
    @include('shared.button_filter')
    <a class="btn btn-outline-success" href="{{ route('costumer.create') }}">
        <i class="bi bi-emoji-smile me-2"></i>@lang('labels.add') @lang('labels.costumer')
    </a>
    <a class="btn btn-outline-primary" href="{{ route('order.create') }}">
        <i class="bi bi-file-earmark-ruled me-2"></i>@lang('labels.add') @lang('labels.order')
    </a>
@endsection

@section('filters')
    @csrf
    <div class="row">
        <div class="col-md-7"> <!-- Prima coppia/terna di filtri -->
        </div>

        <div class="col-md-5 ps-lg-5"> <!-- Seconda coppia di filtri -->
        </div>
        @include('shared.filter.submit')
    </div>
@endsection

@section('main')

    <div id="costumers" class="row row-cols-1 row-cols-xxl-2 g-3 mt-2">
        @foreach($costumers_orders_nums as $num => $costumer_orders_nums)
            <div>
                @include('costumer.table.costumer')
            </div>
        @endforeach
    </div>
    @include('shared.alert_no_element', ['element_list' => $costumers_orders_nums])

    <script>
        commercial_script();
    </script>

@endsection
