<?php

use App\Models\Club;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Club::class, "club_id");
            $table->string("name");
            $table->string("surname");
            $table->string("patronymic");
            $table->string("position");
            $table->string("phone_number");
            $table->string("email");
            $table->string("address");
            $table->string("avatar")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
