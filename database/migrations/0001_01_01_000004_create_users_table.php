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
        // Create the 'users' table
        Schema::create('users', function (Blueprint $table) {
            $table->string('id_user', 3)->primary(); // Adjust length as needed
            $table->string('nama', 30);
            $table->unsignedBigInteger('id_jabatan'); // Must match the type of 'id_jabatan' in 'jabatan'
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_jabatan')
                ->references('id_jabatan')
                ->on('jabatan')
                ->onDelete('cascade'); // Cascade delete on related users
        });

        // Password reset tokens table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('jabatan'); // Drop the 'jabatan' table last
    }
};
