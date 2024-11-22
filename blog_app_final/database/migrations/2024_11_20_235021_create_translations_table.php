<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('key')->unique();  // Cột lưu khóa (key)
            $table->text('original');         // Nội dung gốc (nguyên bản)
            $table->text('en')->nullable();  // Dịch tiếng Anh
            $table->text('vi')->nullable();  // Dịch tiếng Việt
            $table->text('original_language')->nullable();  // ngôn ngữ nguyên bản
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
