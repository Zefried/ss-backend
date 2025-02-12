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
            $table->string('billing_status')->default('pending'); // New: Billing status with default 'pending'
            $table->string('discount')->nullable(); // New: Discount field (string)
            $table->integer('visit_count')->default(0); // New: Visit count starts at 1
            $table->string('patient_card_id')->nullable(); // New: Patient card ID
        
            // Foreign key constraints
            $table->foreign('patient_id')->references('id')->on('patient_data');
            $table->index('patient_card_id');

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
