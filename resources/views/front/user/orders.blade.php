@extends('front.layouts.app')


@section('title', __('nav.title', ['title' => __('general.orders')]))

@section('head-front')
    <style>
        .container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .section-title {
            text-align: center;
            padding: 20px;
            padding-bottom: 0;

        }

        .table-container {
            width: 100%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 1350px;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e0e0e0;
            display: none;
        }

        .table-container table th,
        .table-container table td {
            padding: 15px 10px;
            text-align: center;
            border: 1px solid #e0e0e0;
            border-left: 0;
            border-right: 0;
        }

        .table-container table th {
            background-color: #136e81;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }


        .table-container .table-cards {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }


        .table-container .table-cards .card {
            width: 100%;
            background-color: #0a91ac21;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .table-container .table-cards .card-header,
        .table-container .table-cards .card-body {
            width: 100%;
            display: flex;
            flex-direction: column;
            min-width: max-content;
        }

        .table-container .table-cards .card-header .title,
        .table-container .table-cards .card-body .info {
            font-size: 14px;
            height: 40px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            padding: 0 10px;
            width: 100%;
        }

        .table-container .table-cards .card-header .title:last-child,
        .table-container .table-cards .card-body .info:last-child {
            border-bottom: none;
        }

        .table-container .table-cards .card-header .title {
            font-weight: bold;
        }

        .card-row {
            display: flex;
            flex-direction: row;
        }

        .card-footer {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            /* border-top: 50px solid #e0e0e0; */
            padding-top: 20px;
        }

        .card-footer a {
            text-decoration: none;
            color: #136e81;
            font-weight: bold;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }


        @media screen and (min-width: 768px) {
            .table-container table {
                display: table;
            }

            .table-container .table-cards {
                display: none;
            }
        }

        tbody tr:nth-child(even) {
            background-color: #136f813a;
        }

        tbody tr:nth-child(odd) {
            background-color: #136f8109;
        }
    </style>

@endsection

@section('content-front')
    @php
        $locale = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
    @endphp
    <div class="container">
        <div class="section-title">{{ __('general.orders') }}</div>
        <div class="table-container">

            <table>

                <thead>
                    <tr>
                        <th>{{ __('general.orderid') }}</th>
                        <th>{{ __('general.servicename') }}</th>
                        <th>{{ __('general.servicetype') }}</th>
                        <th>{{ __('general.sessionscount') }}</th>
                        <th>{{ __('general.totalprice') }}</th>
                        {{--                    <th>{{ __('general.status') }}</th> --}}
                        <th>{{ __('general.createdat') }}</th>
                        <th>{{ __('general.showsessions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_code }}</td>
                            @if ($order->category_id != null && $order->category)
                                <td>{{ $order->category->getTranslation('name', $locale) ?? '' }}</td>
                                <td>Service</td>
                            @elseif ($order->package)
                                <td>{{ $order->package->getTranslation('name', $locale) ?? '' }}</td>
                                <td>Packages</td>
                            @endif


                            <td>{{ $order->sessions_count }}</td>
                            <td>{{ $order->total_price }}</td>
                            {{--                    <td>Completed</td> --}}
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            <td><a href="{{ route('myOrdersSessions', $order->order_code) }}"><i
                                        class="icon fas fa-eye"></i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="table-cards">
                @foreach ($orders as $order)
                    <div class="card">
                        <div class="card-row">
                            <div class="card-header">
                                <div class="title">{{ __('general.orderid') }}</div>
                                <div class="title">{{ __('general.servicename') }}</div>
                                <div class="title">{{ __('general.servicetype') }}</div>
                                <div class="title">{{ __('general.sessionscount') }}</div>
                                <div class="title">{{ __('general.totalprice') }}</div>
                                {{--                                <div class="title">{{ __('general.status') }}</div> --}}
                                <div class="title">{{ __('general.createdat') }}</div>
                            </div>
                            <div class="card-body">
                                <div class="info">{{ \Illuminate\Support\Str::limit($order->order_code, 22) }}</div>
                                @if ($order->category_id != null && $order->category)
                                    <div class="info">{{ $order->category->getTranslation('name', $locale) ?? '' }}</div>
                                    <div class="info">Service</div>
                                @elseif ($order->package)
                                    <div class="info">{{ $order->package->getTranslation('name', $locale) ?? '' }}</div>
                                    <div class="info">Packages</div>
                                @endif


                                <div class="info">{{ $order->sessions_count }}</div>
                                <div class="info">{{ $order->total_price }}</div>
                                {{--                                <div class="info">Completed</div> --}}
                                <div class="info">{{ $order->created_at->format('Y-m-d') }}</div>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{-- kerooo --}}
                            <a href="{{ route('myOrdersSessions', $order->order_code) }}"><i class="icon fas fa-eye"></i>
                                {{ __('general.showsessions') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endSection

@section('scripts-front')

@endsection
