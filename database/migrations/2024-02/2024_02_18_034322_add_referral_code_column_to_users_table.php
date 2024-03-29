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
        Schema::table(
            'users',
            function (Blueprint $table) {
                $table->string('referral_code', 16)->unique()->nullable();
                $table->foreignId('ref_by')
                    ->nullable()
                    ->constrained('users')
                    ->onDelete('set null');
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
        Schema::table(
            'users',
            function (Blueprint $table) {
                $table->dropForeign(['ref_by']);
                $table->dropColumn(['referral_code', 'ref_by']);
            }
        );
    }
};
