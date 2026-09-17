<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('deskripsi');
            $table->time('jam_buka')->default('07:00')->after('image_path');
            $table->time('jam_tutup')->default('18:00')->after('jam_buka');
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'jam_buka', 'jam_tutup']);
        });
    }
};
