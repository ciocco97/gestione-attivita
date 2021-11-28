@extends('layouts.tma')

@section('title', 'Gestione : cliente')

@section('navbar')
    @include('navbar.nav')
@endsection

@section('actions')
    <div class="d-flex justify-content-end">
        <div class="btn-group" role="group">
            <a class="btn btn-outline-success" href="{{ route('costumer.create') }}">
                <i class="bi bi-emoji-smile me-2"></i>@lang('labels.add') @lang('labels.costumer')
            </a>
            <a class="btn btn-outline-primary" href="{{ route('order.create') }}">
                <i class="bi bi-file-earmark-ruled me-2"></i>@lang('labels.add') @lang('labels.order')
            </a>
        </div>
    </div>
@endsection

@section('filters')
    <form id="master_filter_form" class="mb-0" action="{{ route('activity.filter') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-7"> <!-- Prima coppia/terna di filtri -->
            </div>

            <div class="col-md-5 ps-lg-5"> <!-- Seconda coppia di filtri -->
            </div>
        </div>
    </form>
@endsection

@section('main')

    <div id="costumers" class="row row-cols-1 row-cols-md-2 g-3 mt-2">
        @foreach($costumers_orders_nums as $num => $costumer_orders_nums)
            <div class="col">
                @include('costumer.table.costumer')
            </div>
        @endforeach
    </div>

    <script>
        commercial_script();
    </script>

@endsection
