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
        Schema::create('billing_flows', function (Blueprint $table) {
            
            $table->id();

            // Nullable columns
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('associated_user_id')->nullable();
            $table->decimal('final_amount', 10, 2)->nullable();
            $table->decimal('discount', 5, 2)->nullable();
            $table->unsignedBigInteger('selected_employee_id')->nullable();
            $table->json('tests')->nullable(); // Stores multiple test IDs as JSON
            $table->string('lab_id')->nullable(); // Stores lab id
            $table->unsignedBigInteger('patient_assign_flow_id')->nullable(); // Stores patient assign flow id
            $table->string('transaction_id')->nullable(); // Fixed snake_case naming
            $table->string('bill_file')->nullable(); // Ensure bill_file is included
            
            // Indexes
            $table->index('lab_id');
            $table->index('associated_user_id');
            $table->index('selected_employee_id');
            $table->index('transaction_id'); // Fixed snake_case naming
            

            // Foreign keys
            $table->foreign('patient_id')->references('id')->on('patient_data');
            $table->foreign('associated_user_id')->references('id')->on('users');
            $table->foreign('selected_employee_id')->references('id')->on('employees');
            $table->foreign('patient_assign_flow_id')->references('id')->on('patient_assign_flows');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_flows');
    }
};
