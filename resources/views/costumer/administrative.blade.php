@extends('layouts.tmac')

@section('title', 'Gestione : cliente')

@section('navbar')
    @include('nav')
@endsection

@section('actions')

@endsection

@section('filters')

@endsection

@section('main')

    @foreach($costumers as $costumer)
        <div class="card mb-2">
            <div class="card-body">
                <h5 class="card-title">{{ $costumer->nome }}</h5>

                <div class="" id="orders_{{ $costumer->id }}">

                    @foreach($orders->filter(function($item) use(&$costumer) { return $item->cliente_id == $costumer->id; }) as $order)
                        <div class="accordion-item mb-2">
                            <h2 class="accordion-header" id="title_{{ $order->id }}">
                                <button id="show_collapse_{{ $order->id }}" class="accordion-button collapsed" type="button" data-order-id="{{ $order->id }}" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $order->id }}">
                                    {{ $order->descrizione_commessa }}
                                </button>
                            </h2>
                            <div id="collapse_{{ $order->id }}" class="accordion-collapse collapse" data-bs-parent="#orders_{{ $costumer->id }}">
                                <div class="accordion-body">

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    @endforeach

    <script>
        administrative_script();
    </script>

@endsection
