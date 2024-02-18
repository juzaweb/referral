<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'referral_user_earn_histories',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('user_id')
                    ->index()
                    ->constrained('users')
                    ->onDelete('cascade');
                $table->string('title', 255);
                $table->string('earn_type', 20)->index();
                $table->float('earn_number');
                $table->foreignId('referred_user_id')
                    ->nullable()
                    ->constrained('users')
                    ->onDelete('set null');
                $table->string('status', 20)->index();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_user_earn_histories');
    }
};
