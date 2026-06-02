<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('director');
            $table->text('description')->nullable();
            $table->string('cast')->nullable();       // comma-separated
            $table->string('genre')->nullable();      // comma-separated
            $table->string('language')->default('English');
            $table->year('release_year')->nullable();
            $table->string('duration')->nullable();   // e.g. "2h 12m"
            $table->decimal('rating', 3, 1)->default(0); // e.g. 8.2
            $table->integer('votes')->default(0);
            $table->string('poster')->nullable();     // image path
            $table->string('backdrop')->nullable();   // hero bg image path
            $table->boolean('is_featured')->default(false);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};