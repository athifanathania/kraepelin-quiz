{{-- resources/views/filament/modals/kraepelin-visual.blade.php --}}
<style>
    .visual-scroll {
        overflow-x: auto;
        max-height: 70vh; 
        overflow-y: auto;
        padding: 12px;
        scrollbar-width: thin;
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }
    .col-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        /* Jarak antar kolom diperkecil sedikit agar lebih rapat */
        margin-right: 8px; 
        flex-shrink: 0; 
    }
    .col-header {
        font-size: 9px; /* Font header lebih kecil */
        color: #9ca3af;
        font-family: monospace;
        font-weight: bold;
        margin-bottom: 4px;
        border-bottom: 1px solid #e5e7eb;
        width: 100%;
        text-align: center;
        padding-bottom: 2px;
    }
    
    .col-strip {
        display: flex;
        flex-direction: column-reverse; 
        align-items: center;
        gap: 1px; /* Jarak vertikal antar kotak diperkecil */
        min-width: 26px; /* Lebar strip mengikuti lebar box baru */
    }

    .q-num {
        font-size: 9px; /* Angka soal lebih kecil */
        font-weight: 700;
        color: #9ca3af; /* Warna diperhalus agar tidak bertabrakan dengan isi kotak */
        line-height: 1; 
        margin: 1px 0; 
    }

    /* --- PERUBAHAN UKURAN KOTAK DI SINI --- */
    .box {
        height: 20px; /* Sebelumnya 24px */
        width: 26px;  /* Sebelumnya 32px */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px; /* Font dalam kotak diperkecil (sebelumnya 11px) */
        font-weight: bold;
        border-radius: 3px;
        border: 1px solid transparent;
        cursor: default;
        z-index: 10;
    }

    /* Warna Kustom untuk Grid Visual */
    .correct { background: #dcfce7; color: #166534; border-color: #86efac; }
    .wrong   { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
    .skipped { background: #f3f4f6; color: #9ca3af; border-color: #e5e7eb; } 
</style>

@php
    $currentSession = $record ?? $session ?? null;

    if ($currentSession && !isset($answersByColumn)) {
        $answersByColumn = $currentSession->kraepelinAnswers()
            ->orderBy('column_index')
            ->orderBy('row_index', 'asc') 
            ->get()
            ->groupBy('column_index');
    }
@endphp

<div class="space-y-4" 
     x-data 
     x-init="$nextTick(() => { 
         setTimeout(() => { 
             $refs.topMarker.scrollIntoView({ behavior: 'smooth', block: 'start' });
         }, 300); 
     })">
    
    <div x-ref="topMarker"></div>

    {{-- 1. Header Legenda & Tombol Export --}}
    {{-- Menggunakan bg-gray-50 agar header terpisah visualnya dari konten --}}
    <div class="flex flex-col md:flex-row justify-between items-center bg-gray-50 p-4 rounded-xl border border-gray-200 shadow-sm gap-4">
        
        {{-- Bagian Kiri: Judul & Legenda --}}
        <div class="flex flex-col gap-2 w-full md:w-auto">
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">
                    Visualisasi Kertas Kerja
                </h3>

                <p class="text-xs text-gray-500">
                    Arah pengerjaan: <strong>Bawah ke Atas</strong>.
                </p>
            </div>
            
            {{-- Legenda --}}
            <div class="flex gap-2 text-xs font-medium mt-1">
                <span class="px-2 py-0.5 rounded border correct flex items-center gap-1">
                    <div class="w-2 h-2 rounded-full bg-green-600"></div> Benar
                </span>
                <span class="px-2 py-0.5 rounded border wrong flex items-center gap-1">
                    <div class="w-2 h-2 rounded-full bg-red-600"></div> Salah
                </span>
                <span class="px-2 py-0.5 rounded border border-dashed skipped text-gray-500">Kosong</span>
            </div>
        </div>
        
        {{-- Bagian Kanan: TOMBOL EXPORT YANG DI-HIGHLIGHT --}}
        @if($currentSession)
            <div class="w-full md:w-auto flex justify-end">
                <a href="{{ route('kraepelin.export', $currentSession->id) }}" 
                    target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2
                        bg-white hover:bg-gray-100
                        text-gray-700
                        border border-gray-300
                        text-xs font-medium
                        rounded-lg
                        shadow-sm
                        transition">

                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="h-4 w-4 text-gray-500"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>

                    <div class="leading-tight">
                        <div class="text-[10px] text-gray-500">
                            Download Laporan
                        </div>
                        <div class="text-xs font-semibold text-gray-800">
                            Export ke PDF
                        </div>
                    </div>
                </a>
            </div>
        @endif
    </div>

    {{-- 2. Visual Grid Scrollable --}}
    @if(isset($answersByColumn) && count($answersByColumn) > 0)
        <div class="visual-scroll shadow-inner">
            <div class="flex">
                @foreach($answersByColumn as $colIndex => $answers)
                    <div class="col-container">
                        <div class="col-header">{{ $colIndex }}</div>
                        
                        <div class="col-strip">
                            @foreach($answers as $ans)
                                <span class="q-num">{{ $ans->bottom_number }}</span>
                                
                                <div class="box {{ $ans->user_answer === null ? 'skipped' : ($ans->is_correct ? 'correct' : 'wrong') }}" 
                                     title="Soal: {{ $ans->top_number }} + {{ $ans->bottom_number }} = {{ ($ans->top_number + $ans->bottom_number) % 10 }} | Jawab: {{ $ans->user_answer }}">
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
        
        <div class="flex justify-between text-[10px] text-gray-400 italic px-1">
            <span>*Geser horizontal untuk melihat seluruh kolom</span>
        </div>
    @else
        <div class="p-8 text-center text-gray-500 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p>Data jawaban tidak ditemukan atau sesi belum dimulai.</p>
        </div>
    @endif

    <hr class="border-gray-200">

    {{-- 3. TABEL DETAIL PER KOLOM --}}
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="p-3 bg-gray-50 border-b flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
            </svg>
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
                    @if(isset($answersByColumn))
                        @foreach($answersByColumn as $colIndex => $answers)
                            @php
                                $filled  = $answers->whereNotNull('user_answer')->count();
                                $correct = $answers->where('is_correct', true)->count();
                                $wrong   = $answers->whereNotNull('user_answer')->where('is_correct', false)->count();
                                $empty   = $answers->whereNull('user_answer')->count();
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-2 font-medium text-gray-700">Kolom {{ $colIndex }}</td>
                                <td class="px-4 py-2 text-center font-bold text-gray-800">{{ $filled }}</td>
                                <td class="px-4 py-2 text-center font-bold" style="color: #16a34a;">{{ $correct }}</td>
                                <td class="px-4 py-2 text-center font-bold" style="color: {{ $wrong > 0 ? '#dc2626' : '#d1d5db' }};">{{ $wrong }}</td>
                                <td class="px-4 py-2 text-center text-gray-400">{{ $empty }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>