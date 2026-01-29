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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruang_id')->constrained('ruang')->onDelete('cascade');
            $table->string('nama_device');
            $table->enum('tipe', ['onoff', 'remote', 'cctv'])->default('onoff');
            $table->string('icon')->nullable();
            
            // ON/OFF Specific
            $table->string('power_topic')->nullable();
            $table->string('power_payload_on')->default('ON');
            $table->string('power_payload_off')->default('OFF');
            $table->boolean('power_retain')->default(false);
            
            // Remote Specific
            $table->string('remote_topic')->nullable();
            
            // kWh Monitoring (Universal)
            $table->boolean('kwh_enabled')->default(false);
            $table->string('kwh_topic')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
