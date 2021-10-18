@extends('layouts.tmac')

@section('title', 'Gestione : cliente')

@section('navbar')
    @include('nav')
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
    <div id="costumers" class="mt-3">
        @foreach($costumers_orders_nums as $num => $costumer_orders_nums)
            <div class="card mb-2 border-info col">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title">{{ $costumer_orders_nums[0]->nome }}</h5>
                        <div>
                            <td>
                                <a id="show_{{ $costumer_orders_nums[0]->id }}" class="btn pt-0"
                                   href="{{ route('costumer.show', ['costumer' => $costumer_orders_nums[0]->id]) }}">
                                    <i class="bi bi-eye text-secondary"></i>
                                </a>
                            </td>

                            <td>
                                <a id="edit_{{ $costumer_orders_nums[0]->id }}" class="btn pt-0"
                                   href="{{ route('costumer.edit', ['costumer' => $costumer_orders_nums[0]->id]) }}">
                                    <i class="bi bi-pencil text-warning"></i>
                                </a>
                            </td>
                            @if($costumer_orders_nums[2]>0)
                                <td>
                                    <a id="delete_{{ $costumer_orders_nums[0]->id }}"
                                       class="btn pt-0 disabled"
                                       href="">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            @else
                                <td>
                                    <a id="delete_{{ $costumer_orders_nums[0]->id }}" class="btn pt-0"
                                       href="{{ route('costumer.destroy.confirm', ['id' => $costumer_orders_nums[0]->id]) }}">
                                        <i class="bi bi-trash text-danger"></i>
                                    </a>
                                </td>
                            @endif
                        </div>
                    </div>

                    <div>

                        <div class="accordion-item mb-2">
                            <h2 class="accordion-header" id="title_{{ $costumer_orders_nums[0]->id }}">
                                <button id="show_collapse_{{ $costumer_orders_nums[0]->id }}"
                                        class="accordion-button collapsed"
                                        type="button" data-costumer-id="{{ $costumer_orders_nums[0]->id }}"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse_{{ $costumer_orders_nums[0]->id }}">
                                    @lang('labels.num_orders'): {{ count($costumer_orders_nums[1]) }}
                                </button>
                            </h2>
                            <div id="collapse_{{ $costumer_orders_nums[0]->id }}"
                                 class="accordion-collapse collapse"
                                 data-bs-parent="#costumers">
                                <div class="accordion-body">

                                    <div class="row mt-2">
                                        <div class="table-responsive">
                                            @include('costumer.table.commercial')
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer text-muted">
                            <p class="mb-0">
                                @lang('labels.activities'): {{ $costumer_orders_nums[2] }}
                                <span class="px-2"></span>
                                @lang('labels.not_billed'): {{ $costumer_orders_nums[3] }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        commercial_script();
    </script>

@endsection
