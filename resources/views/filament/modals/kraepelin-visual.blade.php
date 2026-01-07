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
    
    .col-strip {
        display: flex;
        flex-direction: column-reverse;
        align-items: center;
        gap: 2px; 
        min-width: 32px;
    }

    .q-num {
        font-size: 10px;
        font-weight: 700;
        color: #6b7280;
        line-height: 1; 
        margin: 1px 0; 
    }

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

    /* Warna Kustom untuk Grid Visual */
    .correct { background: #dcfce7; color: #166534; border-color: #86efac; }
    .wrong   { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
    .skipped { background: #f3f4f6; color: #9ca3af; border-color: #e5e7eb; } 
</style>

{{-- 
    PERBAIKAN SCROLL: 
    Menggunakan setTimeout 300ms memastikan modal sudah selesai render/animasi
    sebelum memaksa scroll ke 'topMarker' (elemen paling atas).
--}}
<div class="space-y-4" 
     x-data 
     x-init="$nextTick(() => { 
         setTimeout(() => { 
             $refs.topMarker.scrollIntoView({ behavior: 'smooth', block: 'start' });
         }, 300); 
     })">
    
    {{-- Marker untuk target scroll --}}
    <div x-ref="topMarker"></div>

    {{-- 1. Header Legenda --}}
    <div class="flex justify-between items-center bg-white p-3 rounded-lg border shadow-sm">
        <div>
            <h3 class="font-bold text-gray-800 text-sm">Visualisasi Kertas Kerja</h3>
            <p class="text-xs text-gray-500">
                Urutan: <strong>Bawah ke Atas</strong>.<br>
                Angka kecil adalah soal, kotak adalah jawaban.
            </p>
        </div>
        <div class="flex gap-3 text-xs font-medium">
            <span class="px-2 py-1 rounded border correct">Benar</span>
            <span class="px-2 py-1 rounded border wrong">Salah</span>
            <span class="px-2 py-1 rounded border border-dashed skipped">Kosong</span>
        </div>
    </div>

    {{-- 2. Visual Grid Scrollable --}}
    <div class="visual-scroll bg-white p-4 rounded-xl border border-gray-100 shadow-inner">
        <div class="flex">
            @foreach($answersByColumn as $colIndex => $answers)
                <div class="col-container">
                    <div class="col-header">#{{ $colIndex }}</div>
                    
                    <div class="col-strip">
                        @foreach($answers as $ans)
                            <span class="q-num">{{ $ans->bottom_number }}</span>
                            <div class="box {{ $ans->user_answer === null ? 'skipped' : ($ans->is_correct ? 'correct' : 'wrong') }}" 
                                 title="{{ $ans->top_number }} + {{ $ans->bottom_number }} = {{ $ans->top_number + $ans->bottom_number }}">
                                {{ $ans->user_answer }}
                            </div>
                        @endforeach

                        @if($answers->isNotEmpty())
                            <span class="q-num">{{ $answers->last()->top_number }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    {{-- Info Navigasi Kecil --}}
    <div class="flex justify-between text-xs text-gray-400 italic">
        <span>*Format: Angka Atas + Angka Bawah = Kotak Tengah</span>
        <span>Geser ke kanan untuk melihat kolom selanjutnya &rarr;</span>
    </div>

    <hr class="border-gray-200">

    {{-- 3. TABEL DETAIL PER KOLOM --}}
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="p-3 bg-gray-50 border-b">
            <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wide">Rincian Statistik per Kolom</h3>
        </div>
        
        <div class="max-h-64 overflow-y-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-500 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-4 py-2 font-medium">Kolom</th>
                        <th class="px-4 py-2 font-medium text-center">Diisi</th>
                        <th class="px-4 py-2 font-medium text-center" style="color: #16a34a;">Benar</th>
                        <th class="px-4 py-2 font-medium text-center" style="color: #dc2626;">Salah</th>
                        <th class="px-4 py-2 font-medium text-center text-gray-500">Kosong</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($answersByColumn as $colIndex => $answers)
                        @php
                            $filled  = $answers->whereNotNull('user_answer')->count();
                            $correct = $answers->where('is_correct', true)->count();
                            $wrong   = $answers->whereNotNull('user_answer')->where('is_correct', false)->count();
                            $empty   = $answers->whereNull('user_answer')->count();
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-2 font-medium text-gray-700">Kolom {{ $colIndex }}</td>
                            
                            {{-- Diisi --}}
                            <td class="px-4 py-2 text-center font-bold text-gray-800">
                                {{ $filled }}
                            </td>
                            
                            {{-- Benar: Hijau (Pakai style inline agar pasti berwarna) --}}
                            <td class="px-4 py-2 text-center font-bold" style="color: #16a34a;">
                                {{ $correct }}
                            </td>

                            {{-- Salah: Merah jika > 0, Abu-abu jika 0 --}}
                            <td class="px-4 py-2 text-center font-bold" 
                                style="color: {{ $wrong > 0 ? '#dc2626' : '#d1d5db' }};">
                                {{ $wrong }}
                            </td>

                            {{-- Kosong --}}
                            <td class="px-4 py-2 text-center text-gray-400">
                                {{ $empty }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>