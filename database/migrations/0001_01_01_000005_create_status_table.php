<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status', function (Blueprint $table) {
            $table->bigIncrements('id_status'); // Auto-incrementing primary key
            $table->string('status', 8); // Job title or role name
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status');
    }
};
