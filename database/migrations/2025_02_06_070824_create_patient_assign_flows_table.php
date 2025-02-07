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
        Schema::create('patient_assign_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->json('tests'); // Storing tests as JSON

            // Foreign key constraint
            $table->foreign('patient_id')->references('id')->on('patient_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_assign_flows');
    }
};
