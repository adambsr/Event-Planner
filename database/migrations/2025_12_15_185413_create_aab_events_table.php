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
        Schema::create('aab_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('place');
            $table->decimal('price', 10, 2)->default(0);
            $table->foreignId('category_id')->constrained('aab_categories')->onDelete('cascade');
            $table->integer('capacity');
            $table->string('image')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_free')->default(false);
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aab_events');
    }
};
