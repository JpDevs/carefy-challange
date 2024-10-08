<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drafts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->json('patient_data')->nullable();
            $table->string('guide')->unique();
            $table->date('entry');
            $table->date('exit');
            $table->json('inconsistencies')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drafts');
    }
};
