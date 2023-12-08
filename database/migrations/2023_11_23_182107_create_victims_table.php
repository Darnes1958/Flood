<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Family;
use App\Models\Street;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('victims', function (Blueprint $table) {
            $table->id();
          $table->string('Name1');
          $table->string('Name2');
          $table->string('Name3');
          $table->string('Name4');
          $table->string('FullName');
          $table->foreignIdFor(Family::class);
          $table->foreignIdFor(Street::class);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('victims');
    }
};
