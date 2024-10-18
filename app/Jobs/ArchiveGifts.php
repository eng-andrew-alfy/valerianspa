<?php

    namespace App\Jobs;

    use App\Models\ArchivedGiftOrder;
    use App\Models\ArchivedOrder;
    use App\Models\ArchivrdGiftOrder;
    use App\Models\Gift;
    use App\Models\Order;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Foundation\Queue\Queueable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Support\Facades\DB;

    class ArchiveGifts implements ShouldQueue
    {
        use Queueable;

        /**
         * Create a new job instance.
         */
        public function __construct()
        {
            //
        }

        /**
         * Execute the job.
         */
        public function handle()
        {
            // استعلام لجلب الهدايا المرتبطة بأوردرات تم إنشاؤها منذ أكثر من 15 يومًا ومكتملة
            $query = DB::table('gifts as g')
                ->select('g.id')
                ->join('orders as o', 'g.order_id', '=', 'o.id')
                ->join('order_sessions as os', 'o.id', '=', 'os.order_id')
                ->where('g.created_at', '<', now()->subDays(15))
                ->where('o.is_gift', '=', 1)
                ->groupBy('g.id')
                ->havingRaw('COUNT(*) = SUM(CASE WHEN os.status = "completed" THEN 1 ELSE 0 END)');

            $giftsToArchive = $query->get();

            foreach ($giftsToArchive as $gift) {
                // جلب بيانات الهدية
                $gift = Gift::find($gift->id);

                if ($gift) {
                    // جلب بيانات الطلب المرتبط بالهدية
                    $order = $gift->order;

                    if ($order) {
                        // أرشفة الهدية مع تفاصيل الطلب والجلسات
                        ArchivedGiftOrder::create([
                            'gift_id' => $gift->id,
                            'gift_data' => [
                                'gift' => $gift->toArray(), // بيانات الهدية
                                'order' => $order->toArray(), // بيانات الطلب
                                'sessions' => $order->sessions->toArray(), // بيانات الجلسات المرتبطة بالطلب
                            ],
                            'archived_at' => now(),
                        ]);

                        // حذف الهدية بعد الأرشفة
                        $gift->delete();
                    }
                }
            }
        }

    }
