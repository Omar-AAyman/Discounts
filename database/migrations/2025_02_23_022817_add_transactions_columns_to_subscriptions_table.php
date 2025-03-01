<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Register enum type mapping first
        // DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('subscriptions', function (Blueprint $table) {
            // Convert existing status column to enum
            $table->enum('status', [
                'active',
                'pending',
                'canceled',
                'expired',
                'past_due',
                'unpaid'
            ])->default('pending');

            // Add new columns
            $table->string('lahza_subscription_id')->nullable()->unique()->after('user_id');
            $table->string('lahza_authorization_code')->nullable()->after('lahza_subscription_id');
            $table->string('payment_method')->nullable()->after('lahza_authorization_code');
            $table->dateTime('canceled_at')->nullable()->after('updated_at');
            $table->text('cancelation_reason')->nullable()->after('canceled_at');
        });

        // Add raw SQL for MySQL 8+ compatibility if needed
        DB::statement("
            ALTER TABLE subscriptions
            MODIFY status ENUM(
                'active',
                'pending',
                'canceled',
                'expired',
                'past_due',
                'unpaid'
            ) NOT NULL DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'lahza_subscription_id',
                'lahza_authorization_code',
                'payment_method',
                'canceled_at',
                'cancelation_reason',
                'status'
            ]);

            // Revert status to string
            $table->string('status')->default('pending')->change();
        });

        // Optional: Add raw SQL to revert enum if needed
        DB::statement("ALTER TABLE subscriptions MODIFY status VARCHAR(255)");
    }
};
