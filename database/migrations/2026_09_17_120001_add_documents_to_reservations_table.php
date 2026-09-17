<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('proposal_kegiatan_path')->nullable()->after('purpose');
            $table->string('proposal_permohonan_path')->nullable()->after('proposal_kegiatan_path');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['proposal_kegiatan_path', 'proposal_permohonan_path']);
        });
    }
};
