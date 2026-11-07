<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration updates the payment_status column to support the new 'free' tier
     * for Black Friday orders, while maintaining existing values.
     */
    public function up(): void
    {
        // MySQL/MariaDB only: convert the ENUM to VARCHAR. SQLite (tests) stores it as a string already.
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            DB::statement("ALTER TABLE `letters` MODIFY COLUMN `payment_status` VARCHAR(20) DEFAULT 'pending'");
        }

        // Add index for better query performance (optional but recommended)
        Schema::table('letters', function (Blueprint $table) {
            // Only add index if it doesn't exist
            if (!$this->indexExists('letters', 'letters_payment_status_index')) {
                $table->index('payment_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            DB::statement("ALTER TABLE `letters` MODIFY COLUMN `payment_status` ENUM('pending', 'completed', 'failed') DEFAULT 'pending'");
        }

        // Remove the index
        Schema::table('letters', function (Blueprint $table) {
            if ($this->indexExists('letters', 'letters_payment_status_index')) {
                $table->dropIndex('letters_payment_status_index');
            }
        });
    }

    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $index): bool
    {
        return Schema::hasIndex($table, $index);
    }
};
