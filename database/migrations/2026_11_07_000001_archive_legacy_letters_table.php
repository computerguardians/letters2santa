<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The 2025 season used a different product (storybooks). Keep its data as legacy_letters_2025
 * and free up the `letters` name for the v2 schema.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('letters') || ! Schema::hasColumn('letters', 'order_id')) {
            return;
        }

        // SQLite index names are database-wide, so drop them before the rename to avoid clashes.
        Schema::table('letters', function (Blueprint $table) {
            if (Schema::hasIndex('letters', 'letters_payment_status_index')) {
                $table->dropIndex('letters_payment_status_index');
            }
            if (Schema::hasIndex('letters', 'letters_order_id_unique')) {
                $table->dropUnique('letters_order_id_unique');
            }
        });

        Schema::rename('letters', 'legacy_letters_2025');
    }

    public function down(): void
    {
        if (Schema::hasTable('legacy_letters_2025') && ! Schema::hasTable('letters')) {
            Schema::rename('legacy_letters_2025', 'letters');
        }
    }
};
