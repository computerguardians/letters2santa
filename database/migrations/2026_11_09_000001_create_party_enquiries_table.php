<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('party_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20)->index();           // demo | setup | question
            $table->string('organisation', 120);
            $table->string('contact_name', 100);
            $table->string('email');
            $table->string('phone', 30)->nullable();
            $table->char('country_code', 2)->default('AU');
            $table->date('event_date')->nullable();
            $table->unsignedInteger('expected_guests')->nullable();
            $table->text('message')->nullable();
            $table->string('status', 20)->default('new')->index(); // new | contacted | closed
            $table->text('admin_notes')->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('party_enquiries');
    }
};
