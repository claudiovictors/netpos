<?php

declare(strict_types=1);

use Slenix\Database\Migrations\Migration;
use Slenix\Database\Migrations\Schema;
use Slenix\Database\Migrations\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('nif')->unique()->nullable();
            $table->string('address')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};