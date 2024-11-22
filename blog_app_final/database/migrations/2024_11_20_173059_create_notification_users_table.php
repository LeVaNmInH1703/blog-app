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
        Schema::create('notification_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('read')->default(false); // Trạng thái đã đọc hay chưa
            
            // Quan hệ với bảng notifications
            $table->unsignedBigInteger('notification_id'); // ID thông báo
            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade');
            // Quan hệ với bảng users
            $table->unsignedBigInteger('user_id'); // ID người nhận thông báo
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_users');
    }
};
