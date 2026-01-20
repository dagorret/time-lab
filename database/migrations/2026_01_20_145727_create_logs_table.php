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
    Schema::create('logs', function (Blueprint $table) {
        $table->id();
        $table->string('message');
        $table->string('status'); // success, error, warning
        $table->string('user_email');
        $table->integer('duration_ms');
        $table->timestamp('created_at')->useCurrent();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
