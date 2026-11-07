<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('reference', 16)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();

            $table->string('parent_email');
            $table->string('parent_name', 100)->nullable();
            $table->string('child_first_name', 30);
            $table->unsignedTinyInteger('child_age')->nullable();
            $table->string('town', 60);
            $table->char('country_code', 2);
            $table->text('body')->nullable();
            $table->string('timezone', 64);

            $table->string('source', 12)->default('individual')->index();
            $table->string('payment_status', 12)->default('pending')->index();
            $table->string('moderation_status', 12)->default('pending')->index();
            $table->json('moderation_flags')->nullable();
            $table->boolean('has_face')->default(false);

            $table->boolean('wall_consent')->default(false);
            $table->boolean('instagram_consent')->default(false);
            $table->boolean('guardian_confirmed')->default(false);
            $table->timestamp('consent_at')->nullable();
            $table->timestamp('consent_withdrawn_at')->nullable();

            $table->boolean('is_sample')->default(false)->index();
            $table->boolean('is_late')->default(false);
            $table->timestamp('featured_at')->nullable()->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('elf_sent_at')->nullable();
            $table->timestamp('santa_scheduled_for')->nullable()->index();
            $table->timestamp('santa_dispatched_at')->nullable();
            $table->timestamp('santa_sent_at')->nullable();
            $table->unsignedTinyInteger('santa_variant')->nullable();
            $table->timestamp('content_purged_at')->nullable();
            $table->string('ip_hash', 64)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['event_id', 'created_at']);
            $table->index(['moderation_status', 'wall_consent', 'created_at'], 'letters_wall_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
