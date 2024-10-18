<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('orders', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('order_code')->unique(); // Unique order code
                $table->unsignedBigInteger('user_id'); // ID of the user who placed the order
                $table->enum('reservation_status', ['at_spa', 'at_home']); // Reservation status
                $table->enum('neighborhoods', [
                    'اليسمين', 'النخيل', 'الصحافة', 'الملك فهد', 'الملقا', 'العليا',
                    'الشفا', 'الخزامى', 'الزهرة', 'التعاون', 'النرجس', 'البديعة',
                    'العريجاء', 'الدار البيضاء', 'السلي', 'الشميسي', 'عتيقة',
                    'الشفاء', 'الخليل', 'العزيزية', 'الجوهرة', 'البطحاء', 'اليرموك',
                    'الفاخرية', 'الريان', 'المونسية', 'النسيم', 'السلام', 'السعادة',
                    'الحمراء', 'السويدي', 'المنصورة', 'الأندلس', 'الحزم', 'الفايزية',
                    'السليمانية', 'العمارية', 'المعذر', 'المبارك', 'المباركة',
                    'المقصد', 'المعذر الشمالي', 'المعذر الجنوبي', 'القادسية', 'الملز',
                    'المغرزات', 'الشهداء', 'التحلية', 'المحمدية', 'المنطقة الصناعية',
                    'العقدة', 'المنيزلة', 'ابن مساعد', 'ابن زيدون', 'الشرفية',
                    'الكهف', 'القدس', 'المنار', 'الأخضر', 'العطايف', 'العارض',
                    'الرمال', 'حطين', 'العقيق', 'البوادي', 'الواحة', 'الضباط', 'جرير'
                ]);
                $table->unsignedBigInteger('package_id')->nullable(); // Package ID if the order is for a package
                $table->unsignedBigInteger('category_id')->nullable(); // Category ID if the order is for a specific service
                $table->unsignedBigInteger('employee_id')->nullable(); // ID of the employee assigned to the order
                $table->unsignedBigInteger('created_by')->nullable(); // ID of the admin who created the order
                $table->decimal('total_price', 10, 2)->check('total_price >= 0'); // Total price with a check to ensure it's not negative
                //$table->dateTime('initial_session_date'); // Date of the first session
                $table->enum('payment_gateway', ['Tamara', 'MyFatoorah', 'Tabby', 'Cash', 'Bank Transfer']); // Payment gateway used
                $table->text('location')->nullable(); // Location for home bookings (can be encrypted if necessary)
                $table->text('notes')->nullable(); // User notes (can be encrypted if necessary)
                $table->string('qr_code')->nullable(); // QR code for the invoice
                $table->integer('sessions_count')->default(1); // Number of sessions for the order, default is 1
                $table->boolean('is_paid')->default(false); // Indicates if the order has been paid, default is false
                $table->timestamp('archived_at')->nullable(); // Add nullable timestamp for archiving
                $table->text('invoice_url')->nullable();//MyFatoorah Payment Gateway
                $table->boolean('is_gift')->default(false); // Indicates if the order is a gift, default is false
                $table->softDeletes(); // Enable soft deletes for orders
                $table->timestamps();

                // Indexes
                $table->index('user_id'); // Index for quick lookups of orders by user
                $table->index('order_code'); // Index for quick lookups by order code
                $table->index('category_id');
                $table->index('package_id');
                //  $table->index('initial_session_date');
                $table->index('employee_id');
                // Relationships
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
                $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
            });

        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('orders');
        }
    };
