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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chats')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('type', ['text', 'image', 'audio', 'document'])->nullable();
            $table->text('content')->nullable();
            $table->foreignId('replying_to_id')->nullable()->constrained('messages');
            $table->boolean('is_forwarded')->default(false);
            $table->foreignId('original_message_id')->nullable()->constrained('messages');
            $table->boolean('is_starred')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('seen_at')->nullable();
            $table->boolean('show_read_receipts');
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
        Schema::dropIfExists('messages');
    }
};
