<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chats')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('type', ['text', 'image', 'audio', 'document']);
            $table->text('content');
            $table->timestamp('scheduled_for');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduled_messages');
    }
};
