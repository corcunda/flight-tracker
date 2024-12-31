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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number')->index();
            $table->string('airline')->nullable();
            $table->string('origin')->index();
            $table->string('destination')->index();
            $table->float('speed'); // Flight speed
            $table->string('color_plane')->nullable();
            $table->string('color_path')->nullable();
            
            // Coordinates for origin and destination
            $table->decimal('origin_latitude', 10, 6); // Latitude of origin
            $table->decimal('origin_longitude', 10, 6); // Longitude of origin
            $table->decimal('destination_latitude', 10, 6); // Latitude of destination
            $table->decimal('destination_longitude', 10, 6); // Longitude of destination

            $table->timestamp('scheduled_departure')->nullable(); // Scheduled departure time
            $table->timestamp('scheduled_arrival')->nullable(); // Scheduled arrival time
            $table->timestamp('actual_departure')->nullable(); // Actual departure time
            $table->timestamp('actual_arrival')->nullable(); // Actual arrival time
            $table->string('status')->default('scheduled')->index(); // Flight status
            
            $table->boolean('is_delayed')->default(false); // Delay status
            $table->decimal('distance', 10, 2)->nullable(); // Flight distance (in km or miles)
            $table->decimal('estimated_duration', 10, 2)->nullable(); // Estimated duration (in minutes)
            $table->timestamps();
        });

        Schema::create('flight_positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flight_id');
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
            $table->timestamps();

            $table->foreign('flight_id')->references('id')->on('flights')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('flight_positions');
        Schema::dropIfExists('flights');
        Schema::enableForeignKeyConstraints();
    }
};
