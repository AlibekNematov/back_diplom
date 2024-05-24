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
        Schema::create('app_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('login');
            $table->string('password');
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->foreignIdFor(Subscription::class, 'subscription_id')->nullable();
            $table->foreignIdFor(Club::class, 'club_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_users');
    }
};
