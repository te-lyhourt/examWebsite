
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
        Schema::create('projects_question', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Groups::class,'groups_id')->onDelete('cascade');
            $table->foreignIdFor(\App\Models\Projects::class,'projects_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects_question');
    }
};
