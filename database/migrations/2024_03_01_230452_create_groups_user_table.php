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
        Schema::create('groups_user', function (Blueprint $table) {
            $table->id();
            //create columnt groups_id from table group
            $table->foreignIdFor(\App\Models\Groups::class,'groups_id')->onDelete('cascade');
            $table->foreignIdFor(\App\Models\User::class,'user_id')->onDelete('cascade');
            $table->foreignIdFor(\App\Models\User::class,'added_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_user');
    }
};
