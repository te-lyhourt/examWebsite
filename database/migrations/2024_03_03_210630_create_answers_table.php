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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Questions::class,'questions_id')->onDelete('cascade');
            $table->foreignIdFor(\App\Models\User::class,'user_id')->onDelete('cascade');
            $table->text('answer')->nullable();
            $table->text('fileName')->nullable();
            $table->text('filePath')->nullable();
            $table->text('url')->nullable();
            $table->integer('repeatIndex')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
