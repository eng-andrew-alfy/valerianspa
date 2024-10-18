<?php

    namespace App\Jobs;

    use App\Models\ArchivedOrder;
    use App\Models\Order;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Foundation\Queue\Queueable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;

    class ArchiveOrders implements ShouldQueue
    {
        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

        public function __construct()
        {
            Log::info('ArchiveOrders job constructed at ' . now());
        }

        public function handle()
        {
            Log::info('ArchiveOrders job started at ' . now());
            $query = DB::table('orders as o')
                ->select('o.id')
                ->join('order_sessions as os', 'o.id', '=', 'os.order_id')
                ->where('o.created_at', '<', now()->subDays(15))
                ->where('o.is_gift', '=', 0)
                ->groupBy('o.id')
                ->havingRaw('COUNT(*) = SUM(CASE WHEN os.status = "completed" THEN 1 ELSE 0 END)');


            $ordersToArchive = $query->get();
            Log::info('Found ' . $ordersToArchive->count() . ' orders to archive');
            foreach ($ordersToArchive as $order) {
                $order = Order::find($order->id);
                ArchivedOrder::create([
                    'order_id' => $order->id,
                    'data' => [
                        'order' => $order->toArray(),
                        'sessions' => $order->sessions->toArray(),
                    ],
                    'archived_at' => now(),
                ]);

                $order->delete();
            }

        }


    }
