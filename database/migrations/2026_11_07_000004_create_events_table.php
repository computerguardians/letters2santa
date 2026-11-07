<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('slug', 16)->unique();
            $table->date('event_date')->nullable();
            $table->string('town', 60);
            $table->char('country_code', 2)->default('AU');
            $table->string('logo_path')->nullable();
            $table->unsignedInteger('credits_purchased')->default(0);
            $table->unsignedInteger('credits_remaining')->default(0);
            $table->string('status', 10)->default('active')->index();
            $table->string('qr_svg_path')->nullable();
            $table->string('qr_png_path')->nullable();
            $table->string('poster_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
