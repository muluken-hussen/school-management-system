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
        Schema::create('student_grade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained();
            $table->foreignId('student_id')->constrained();
            $table->double('total_marks')->nullable()->default(null);;
            $table->double('avrage')->nullable()->default(null);;
            $table->integer('rank')->nullable()->default(null);;
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_grade');
    }
};
