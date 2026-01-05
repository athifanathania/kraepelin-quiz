{{-- resources/views/filament/modals/kraepelin-visual.blade.php --}}
<style>
    .visual-scroll {
        overflow-x: auto;
        padding-bottom: 12px;
        scrollbar-width: thin;
        background-color: #ffffff;
    }
    .col-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-right: 12px;
    }
    .col-header {
        font-size: 10px;
        color: #9ca3af;
        font-family: monospace;
        font-weight: bold;
        margin-bottom: 6px;
        border-bottom: 1px solid #e5e7eb;
        width: 100%;
        text-align: center;
        padding-bottom: 2px;
    }
    
    /* Container Utama Per Kolom */
    .col-strip {
        display: flex;
        flex-direction: column-reverse; /* Item pertama di DOM akan muncul paling BAWAH */
        align-items: center;
        /* Gap kita kecilkan karena sekarang angka dan kotak berdiri sendiri-sendiri */
        gap: 2px; 
        min-width: 32px;
    }

    /* Style Angka */
    .q-num {
        font-size: 10px;
        font-weight: 700;
        color: #6b7280;
        line-height: 1; 
        /* Margin kecil agar tidak terlalu nempel dengan kotak */
        margin: 1px 0; 
    }

    /* Style Kotak */
    .box {
        height: 24px;
        width: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
        border-radius: 4px;
        border: 1px solid transparent;
        cursor: default;
        z-index: 10;
    }

    .correct { background: #dcfce7; color: #166534; border-color: #86efac; }
    .wrong   { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
    .skipped { background: #f3f4f6; color: #9ca3af; border-color: #e5e7eb; } 
</style>

<div class="space-y-4">
    {{-- Header Legenda --}}
    <div class="flex justify-between items-center bg-white p-3 rounded-lg border shadow-sm">
        <div>
            <h3 class="font-bold text-gray-800 text-sm">Detail Kertas Kerja</h3>
            <p class="text-xs text-gray-500">
                Urutan: <strong>Bawah ke Atas</strong>.<br>
                Angka kecil adalah soal, kotak adalah jawaban.
            </p>
        </div>
        <div class="flex gap-3 text-xs font-medium">
            <span class="px-2 py-1 rounded bg-green-100 text-green-800 border border-green-200">Benar</span>
            <span class="px-2 py-1 rounded bg-red-100 text-red-800 border border-red-200">Salah</span>
            <span class="px-2 py-1 rounded bg-gray-100 text-gray-500 border border-gray-200 border-dashed">Kosong</span>
        </div>
    </div>

    {{-- Container Scroll Horizontal --}}
    <div class="visual-scroll bg-white p-4 rounded-xl border border-gray-100 shadow-inner">
        <div class="flex">
            @foreach($answersByColumn as $colIndex => $answers)
                <div class="col-container">
                    <div class="col-header">#{{ $colIndex }}</div>
                    
                    {{-- Strip Jawaban --}}
                    <div class="col-strip">
                        @foreach($answers as $ans)
                            {{-- 1. Cetak Angka Bawah Dulu --}}
                            <span class="q-num">{{ $ans->bottom_number }}</span>

                            {{-- 2. Cetak Kotak Jawaban --}}
                            <div class="box {{ $ans->user_answer === null ? 'skipped' : ($ans->is_correct ? 'correct' : 'wrong') }}" 
                                 title="{{ $ans->top_number }} + {{ $ans->bottom_number }} = {{ $ans->top_number + $ans->bottom_number }}">
                                {{ $ans->user_answer }}
                            </div>
                        @endforeach

                        {{-- 3. Terakhir, Cetak Angka Paling Atas (Penutup) --}}
                        {{-- Karena column-reverse, elemen terakhir di sini akan muncul paling ATAS secara visual --}}
                        @if($answers->isNotEmpty())
                            <span class="q-num">{{ $answers->last()->top_number }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="flex justify-between text-xs text-gray-400 italic mt-2">
        <span>*Format: Angka Atas + Angka Bawah = Kotak Tengah</span>
        <span>Geser ke kanan untuk melihat kolom selanjutnya &rarr;</span>
    </div>
</div>