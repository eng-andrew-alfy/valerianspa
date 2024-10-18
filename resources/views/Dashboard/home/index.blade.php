@extends('Dashboard.layouts.master')
{{--@section('title_dashboard')--}}
{{--@endsection--}}
@section('css_dashboard')
    <style>
        .status-message {
            font-size: 1.25rem;
            font-weight: bold;
            text-align: center;
        }

    </style>

@endsection
@section('title_page')
    الصفحة الرئيسية
@endsection

@section('link_name_page_back')
@endsection

@section('title_link_name_page_back')
@endsection

@section('link_name_page')
@endsection

@section('title_link_name_page')

@endsection

@section('page-body')

    <div class="row">
        <!-- counter-card-1 start-->
        <div class="col-md-12 col-xl-4">
            <div class="card counter-card-1">
                <div class="card-block-big">
                    <div class="row">
                        <div class="col-6 counter-card-icon">
                            <i class="icofont icofont-cart-alt"></i>
                        </div>
                        <div class="col-6 text-right">
                            <div class="counter-card-text">
                                <p>عدد الطلبات </p>
                                <h3>{{\App\Models\Order::count()}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- counter-card-1 end-->
        <!-- counter-card-2 start -->
        <div class="col-md-6 col-xl-4">
            <div class="card counter-card-{{ \App\Models\Order::where('is_paid', false)->count() == 0 ? '1' : '2' }}">
                <div class="card-block-big">
                    <div class="row">
                        <div class="col-6 counter-card-icon">
                            <i class="icofont icofont-waiter-alt"></i>
                        </div>
                        <div class="col-6 text-right">
                            <div class="counter-card-text">
                                <p>الطلبات الغير مدفوعة</p>
                                <h3 class="status-message"
                                    style="color:  {{ \App\Models\Order::where('is_paid', false)->count() == 0 ? '#4CAF50' : '#F44336' }}">
                                    {{ \App\Models\Order::where('is_paid', false)->count() == 0
                                        ? 'لا يوجد طلبات'
                                        :  \App\Models\Order::where('is_paid', false)->count()
                                    }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- counter-card-2 end -->

        <!-- counter-card-3 start -->
        <div class="col-md-6 col-xl-4">
            <div class="card counter-card-3">
                <div class="card-block-big">
                    <div class="row">
                        <div class="col-6 counter-card-icon">
                            <i class="icofont icofont-user-alt-3" style="color: #0a6aa1"></i>
                        </div>
                        <div class="col-6 text-right">
                            <div class="counter-card-text">
                                <p>عدد العملاء</p>
                                <h3>{{\App\Models\User::count()}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- counter-card-3 end -->
    </div>


    <div class="row">
        <!-- Order task Start -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-block">
                    <h5>أحدث الطلبات</h5>
                </div>
                <div class="card-block order-task p-t-0">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            @php
                                use App\Models\Order;
                                    $latestOrders = Order::with(['user', 'employee'])
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();
                            @endphp
                            @foreach($latestOrders as $order)
                                <tr>
                                    <td>
                                        <h6>{{ $order->user->name }}</h6>
                                        <p class="text-muted">{{ $order->reservation_status == 'at_home' ? 'منزلى' : 'فرع' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-muted">{{ $order->created_at->format('jS M Y') }}</p>
                                        <p class="text-muted">{{ $order->created_at->format('h:iA') }}</p>
                                    </td>
                                    <td>
                                        @if($order->is_paid)
                                            <button type="button" class="btn btn-success btn-round">مدفوع</button>
                                        @else
                                            <button type="button" class="btn btn-danger btn-round">غير مدفوع</button>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->user->id) }}"
                                           class="text-default"><i
                                                class="icofont icofont-emo-rolling-eyes f-20"></i></a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Order task End -->

        <div class="col-md-5">
            <div class="card">
                <div class="card-block p-b-10">
                    <h5>قائمة العاملين اليوم</h5>
                </div>
                @php
                    use Carbon\Carbon;
                    use App\Models\Employee;
                 $today = Carbon::now()->dayOfWeek;
                 $employeesWorkingToday = Employee::whereHas('availability.employeeDays', function ($query) use ($today) {
                     $query->where('day_of_week_id', $today);
                 })->with(['availability' => function($query) {
                     $query->orderBy('start_time');
                 }])->get();
                @endphp
                <div class="card-block p-t-0">
                    <table class="table">

                        @foreach($employeesWorkingToday as $employee)
                            <tr>
                                <td>{{ $employee->getTranslation('name', 'ar') }}</td>
                                <td>{{ \Carbon\Carbon::parse($employee->availability->start_time)->format('h:i A') }}</td>
                                <td>{{ \Carbon\Carbon::parse($employee->availability->end_time)->format('h:i A') }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="notifications">
        <audio id="notificationSound" src="{{ asset('dashboard/assets/sounds/notification.wav') }}"
               preload="auto"></audio>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5>الطلبات</h5>
                </div>
                <div class="card-block">
                    <canvas id="ordersPolarChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5>الموظفين</h5>
                </div>
                <div class="card-block">
                    <canvas id="employeesPolarChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script_dashboard')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <script>
        /** *******************************************************
         * 📡 Fetch Data from Server
         * 🎯 Retrieve order data for chart from the specified endpoint
         * ****************************************************** */
        fetch('{{ route('admin.ordersDataChartJS') }}')
            .then(response => response.json())
            .then(data => {
                var ctx = document.getElementById('ordersPolarChart').getContext('2d');

                /** *******************************************************
                 * 🎨 Create Professional Gradients
                 * 🌟 Define gradient colors for each dataset
                 * ****************************************************** */
                var gradientBlue = ctx.createLinearGradient(0, 0, 0, 400);
                gradientBlue.addColorStop(0, 'rgba(54, 162, 235, 0.8)'); // Start color
                gradientBlue.addColorStop(1, 'rgba(54, 162, 235, 0.2)'); // End color

                var gradientGreen = ctx.createLinearGradient(0, 0, 0, 400);
                gradientGreen.addColorStop(0, 'rgba(75, 192, 192, 0.8)'); // Start color
                gradientGreen.addColorStop(1, 'rgba(75, 192, 192, 0.2)'); // End color

                var gradientYellow = ctx.createLinearGradient(0, 0, 0, 400);
                gradientYellow.addColorStop(0, 'rgba(255, 206, 86, 0.8)'); // Start color
                gradientYellow.addColorStop(1, 'rgba(255, 206, 86, 0.2)'); // End color

                /** *******************************************************
                 * 📊 Initialize Polar Area Chart
                 * 📈 Display order data with custom gradients and enhanced options
                 * ****************************************************** */
                var chart = new Chart(ctx, {
                    type: 'polarArea', // Chart type: Polar Area
                    data: {
                        labels: ['يومى', 'اسبوعى', 'شهرى'], // Labels for the data
                        datasets: [{
                            label: 'عدد عمليات الشراء', // Dataset label
                            data: [data.daily, data.weekly, data.monthly], // Data from server
                            backgroundColor: [
                                gradientGreen, // Gradient for daily
                                gradientBlue,  // Gradient for weekly
                                gradientYellow // Gradient for monthly
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)', // Border color for daily
                                'rgba(54, 162, 235, 1)', // Border color for weekly
                                'rgba(255, 206, 86, 1)'  // Border color for monthly
                            ],
                            borderWidth: 2, // Border width
                            hoverBorderWidth: 3, // Border width on hover
                            hoverBorderColor: [
                                'rgba(75, 192, 192, 1)', // Hover border color for daily
                                'rgba(54, 162, 235, 1)', // Hover border color for weekly
                                'rgba(255, 206, 86, 1)'  // Hover border color for monthly
                            ]
                        }]
                    },
                    options: {
                        responsive: true, // Make chart responsive
                        maintainAspectRatio: false, // Allow chart to resize freely
                        animation: {
                            duration: 2000, // Animation duration
                            easing: 'easeInOutBounce' // Animation easing
                        },
                        plugins: {
                            legend: {
                                display: true, // Show legend
                                labels: {
                                    color: '#333', // Legend text color
                                    font: {
                                        size: 14, // Font size
                                        family: 'Arial' // Font family
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.7)', // Tooltip background color
                                titleFont: {
                                    size: 16 // Tooltip title font size
                                },
                                bodyFont: {
                                    size: 14 // Tooltip body font size
                                },
                                callbacks: {
                                    label: function (tooltipItem) {
                                        return ' عمليات: ' + tooltipItem.raw; // Tooltip label text
                                    }
                                }
                            }
                        },
                        scales: {
                            r: {
                                ticks: {
                                    backdropColor: 'rgba(255, 255, 255, 0.5)', // Background color for ticks
                                    color: '#333', // Tick text color
                                    font: {
                                        size: 12 // Tick font size
                                    }
                                }
                            }
                        }
                    }
                });
            });
    </script>

    <script>
        /** *******************************************************
         * 📡 Fetch Data from Server
         * 🎯 Retrieve employee orders data from the specified endpoint
         * ****************************************************** */
        fetch('{{ route('admin.getEmployeeOrdersData') }}')
            .then(response => response.json())
            .then(data => {
                var ctx = document.getElementById('employeesPolarChart').getContext('2d');

                /** *******************************************************
                 * 🌈 Create Gradients for Each Employee
                 * 🎨 Generate a gradient color for each employee's data
                 * ****************************************************** */
                var gradients = data.map(employee => {
                    var gradient = ctx.createLinearGradient(0, 0, 0, 400); // Create linear gradient
                    gradient.addColorStop(0, employee.color); // Start color (employee's color)
                    gradient.addColorStop(1, 'rgba(0, 0, 0, 0.5)'); // End color (dark transparent)
                    return gradient;
                });

                /** *******************************************************
                 * 📊 Initialize Polar Area Chart
                 * 📈 Display employee order data using a polar area chart
                 * ****************************************************** */
                var chart = new Chart(ctx, {
                    type: 'polarArea', // Chart type: Polar Area
                    data: {
                        labels: data.map(employee => employee.name), // Employee names
                        datasets: [{
                            label: 'عدد الطلبات للموظف', // Dataset label
                            data: data.map(employee => employee.order_count), // Order count per employee
                            backgroundColor: gradients, // Use the created gradients
                            borderColor: 'rgba(255, 255, 255, 1)', // Border color (white)
                            borderWidth: 1 // Border width
                        }]
                    },
                    options: {
                        responsive: true, // Make the chart responsive
                        maintainAspectRatio: false, // Allow the chart to resize freely
                        plugins: {
                            legend: {
                                display: true, // Show the legend
                                position: 'right', // Position the legend to the right
                                labels: {
                                    color: '#333', // Legend text color
                                    font: {
                                        size: 14 // Font size for legend labels
                                    }
                                }
                            }
                        }
                    }
                });
            });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var audio = document.getElementById('notificationSound');
            var notifications = document.querySelectorAll('.notification');

            if (notifications.length > 0) {
                audio.play().catch(function (error) {
                    console.error('خطأ في تشغيل الصوت:', error);
                });
            }
        });

    </script>

@endsection
