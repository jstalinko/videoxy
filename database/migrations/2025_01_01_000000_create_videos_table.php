<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('short_code')->unique();
            $table->string('original_name');
            $table->string('disk')->default('public');
            $table->string('storage_path');
            $table->string('hls_path')->nullable();
            $table->unsignedBigInteger('size');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->string('status')->default('processing');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
