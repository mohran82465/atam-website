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
        Schema::create('creative_departments_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creative_departments_id')
            ->constrained('creative_departments', indexName: 'dept_trans_fk') 
            ->cascadeOnDelete();
            $table->string('locale');
            $table->string('name'); 
            $table->string('description'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creative_departments_translations');
    }
};
