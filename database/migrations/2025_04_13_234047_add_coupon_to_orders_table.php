<?php

// database/migrations/xxxx_xx_xx_add_coupon_to_orders_table.php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Check if 'coupon_code' column exists before adding
            if (!Schema::hasColumn('orders', 'coupon_code')) {
                $table->string('coupon_code')->nullable();
            }

            // Check if 'discount' column exists before adding
            if (!Schema::hasColumn('orders', 'discount')) {
                $table->decimal('discount', 8, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the columns only if they exist
            if (Schema::hasColumn('orders', 'coupon_code')) {
                $table->dropColumn('coupon_code');
            }
            if (Schema::hasColumn('orders', 'discount')) {
                $table->dropColumn('discount');
            }
        });
    }
};
