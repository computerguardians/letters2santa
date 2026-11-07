<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letter_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_id')->constrained()->cascadeOnDelete();
            $table->string('disk', 20);
            $table->string('path');          // 1080px display version
            $table->string('thumb_path');    // thumbnail
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('bytes')->nullable();
            $table->json('moderation_labels')->nullable();
            $table->boolean('has_face')->default(false);
            $table->boolean('flagged')->default(false);
            $table->boolean('moderated')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_images');
    }
};
