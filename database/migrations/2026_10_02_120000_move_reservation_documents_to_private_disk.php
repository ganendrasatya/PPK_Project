<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Proposal reservasi dulu disimpan di disk public (bisa diakses tanpa login).
// Path di database tidak berubah, hanya lokasi file yang dipindah.
return new class extends Migration
{
    public function up(): void
    {
        $this->moveDocuments(from: 'public', to: 'local');
    }

    public function down(): void
    {
        $this->moveDocuments(from: 'local', to: 'public');
    }

    private function moveDocuments(string $from, string $to): void
    {
        $paths = DB::table('reservations')
            ->select('proposal_kegiatan_path', 'proposal_permohonan_path')
            ->get()
            ->flatMap(fn ($row) => [$row->proposal_kegiatan_path, $row->proposal_permohonan_path])
            ->filter()
            ->unique();

        foreach ($paths as $path) {
            if (! Storage::disk($from)->exists($path) || Storage::disk($to)->exists($path)) {
                continue;
            }

            Storage::disk($to)->writeStream($path, Storage::disk($from)->readStream($path));
            Storage::disk($from)->delete($path);
        }
    }
};
