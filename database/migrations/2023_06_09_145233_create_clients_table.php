<?php

use App\Models\Club;
use App\Models\Subscription;
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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Subscription::class, "subscription_id");
            $table->foreignIdFor(Club::class, "club_id");
            $table->string("name");
            $table->string("surname");
            $table->string("patronymic");
            $table->string("phone_number");
            $table->string("address");
            $table->date("birth_date");
            $table->string("email");
            $table->bigInteger("accounting_number");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
