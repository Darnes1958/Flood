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
        Schema::create('vic_talent', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Victim::class);
            $table->foreignIdFor(\App\Models\Talent::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vic_talent');
    }
};
