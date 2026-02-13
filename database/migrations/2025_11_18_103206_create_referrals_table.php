<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('referrer_id');
            $table->string('referrer_type', 200);
            $table->uuid('referred_id');
            $table->string('referred_type', 200);
            $table->datetimes();

            $table->index(['referrer_id', 'referrer_type'], 'referrer_index');
            $table->index(['referred_id', 'referred_type'], 'referred_index');
            $table->unique(
                ['referrer_id', 'referrer_type', 'referred_id', 'referred_type'],
                'referral_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referrals');
    }
};
