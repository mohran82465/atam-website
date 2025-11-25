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
        Schema::create('chunk_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chunk_id')->constrained('chunks')->cascadeOnDelete();
            $table->string('locale',2);
            $table->string('title');
            $table->text('body')->nullable();
            $table->timestamps();
            $table->unique(['chunk_id', 'locale']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chunk_translations');
    }
};
