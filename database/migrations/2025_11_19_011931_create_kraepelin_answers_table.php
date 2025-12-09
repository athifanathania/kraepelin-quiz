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
        Schema::create('kraepelin_answers', function (Blueprint $table) {
            $table->id();

            // sesi tes yang sedang berlangsung
            $table->foreignId('test_session_id')->constrained('test_sessions')->cascadeOnDelete();

            // posisi di grid
            $table->unsignedInteger('column_index'); // kolom ke-berapa (mulai dari 1)
            $table->unsignedInteger('row_index');    // baris ke-berapa (mulai dari 1)

            // angka asli di soal (biar bisa audit & hitung ulang)
            $table->unsignedTinyInteger('top_number');    // angka atas (0-9)
            $table->unsignedTinyInteger('bottom_number'); // angka bawah (0-9)

            // jawaban user
            $table->unsignedTinyInteger('user_answer')->nullable(); // 0-9, bisa null kalau kosong

            // penilaian
            $table->boolean('is_correct')->nullable(); // null = belum dinilai / kosong

            // opsional: waktu diisi per-cell (kalau mau detail banget)
            $table->timestamp('answered_at')->nullable();

            $table->timestamps();

            // index bantu untuk query cepat
            $table->index(['test_session_id', 'column_index', 'row_index'], 'kraepelin_pos_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kraepelin_answers');
    }
};
