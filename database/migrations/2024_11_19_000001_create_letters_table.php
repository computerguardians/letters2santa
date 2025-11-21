<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('child_name');
            $table->string('age_range');
            $table->text('message_to_santa');
            $table->string('parent_name');
            $table->string('parent_email');
            $table->string('parent_mobile');
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_checkout_session_id')->nullable();
            $table->decimal('amount', 8, 2)->default(9.49);
            $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending');
            $table->enum('delivery_status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('letters');
    }
};
