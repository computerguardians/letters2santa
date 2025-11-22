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
        // First, check if the column is an ENUM and convert it
        // This handles both ENUM and VARCHAR cases
        DB::statement("ALTER TABLE `letters` MODIFY COLUMN `payment_status` VARCHAR(20) DEFAULT 'pending'");

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
        // Revert to original ENUM structure if needed
        // Note: This will fail if there are 'free' values in the database
        DB::statement("ALTER TABLE `letters` MODIFY COLUMN `payment_status` ENUM('pending', 'completed', 'failed') DEFAULT 'pending'");

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
        $indexes = DB::select("SHOW INDEXES FROM `{$table}` WHERE Key_name = ?", [$index]);
        return !empty($indexes);
    }
};
