<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('credits');
            $table->unsignedInteger('unit_price_cents');
            $table->unsignedInteger('amount_cents');
            $table->unsignedInteger('gst_cents')->default(0);
            $table->string('currency', 3)->default('aud');
            $table->string('stripe_session_id')->nullable()->unique();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_invoice_id')->nullable();
            $table->string('invoice_url', 500)->nullable();
            $table->string('status', 12)->default('pending')->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_purchases');
    }
};
