<!DOCTYPE html>
<html>
<head>
    <title>Hasil Kraepelin - {{ $session->user->name ?? 'User' }}</title>
    <style>
        @page { 
            size: A4 landscape; 
            margin: 0.5cm; 
        }
        
        body { 
            font-family: sans-serif; 
            margin: 0; 
            padding: 0; 
            font-size: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            padding: 0;
            vertical-align: middle;
            text-align: center;
        }

        .q-num {
            font-size: 8px; 
            color: #000;
            line-height: 1;
            display: block;
            font-weight: normal;
        }

        .box {
            display: block;
            height: 13px;
            line-height: 13px;
            font-size: 9px;
            font-weight: bold;
            border: 0.5px solid #aaa;
            border-radius: 1px;
            background-color: #fff;
            margin: 1px auto;
            width: 85%;
        }

        .correct { background-color: #dcfce7; color: #166534; border-color: #166534; }
        .wrong   { background-color: #fee2e2; color: #991b1b; border-color: #991b1b; }
        .skipped { background-color: #f3f4f6; color: #9ca3af; border-style: dotted; border-color: #d1d5db; }
        
        .col-idx { font-size: 7px; color: #666; border-bottom: 0.5px solid #ddd; margin-bottom: 2px; font-weight: bold; }
        .stat-val { font-size: 8px; font-weight: bold; margin-top: 2px; }
        .stat-label { text-align: right; font-size: 8px; font-weight: bold; padding-right: 5px; }

        .header-info { 
            text-align: center; 
            margin-bottom: 8px; 
            border-bottom: 1.5px solid #000; 
            padding-bottom: 5px; 
        }
    </style>
</head>
<body>

    <div class="header-info">
        <h3 style="margin:0; font-size: 14px; text-transform: uppercase;">Hasil Tes Kraepelin</h3>
        <div style="font-size: 10px; margin-top: 3px;">
            <strong>Peserta:</strong> {{ $session->user->name ?? '-' }} | 
            <strong>Tanggal:</strong> {{ $session->created_at->format('d/m/Y H:i') }} | 
            
            @php
                // Menghitung total diisi (benar + salah)
                $totalFilled = $totalCorrect + $totalWrong;
                // Menghitung akurasi berdasarkan yang diisi
                $accuracyPercentage = $totalFilled > 0 ? round(($totalCorrect / $totalFilled) * 100, 1) : 0;
                // Menghitung total yang tidak diisi
                $totalSkipped = ($maxCol * $maxRow) - $totalFilled;
            @endphp

            <strong style="background: #ffff00; padding: 2px 5px;">BENAR: {{ $totalCorrect }}</strong> | 
            <span style="color: #991b1b;">SALAH: {{ $totalWrong }}</span> | 
            <span style="color: #666;">KOSONG: {{ $totalSkipped }}</span> | 
            <strong>AKURASI: {{ $accuracyPercentage }}%</strong>
        </div>
    </div>

    <table>
        <tr>
            <td style="width: 25px;"></td> 
            @for($c = 1; $c <= $maxCol; $c++)
                <td><div class="col-idx">{{ $c }}</div></td>
            @endfor
        </tr>

        @for($r = $maxRow; $r >= 1; $r--)
            <tr>
                <td style="font-size: 6px; color: #999; text-align: right; padding-right: 4px;">{{ $r }}</td>

                @for($c = 1; $c <= $maxCol; $c++)
                    @php 
                        $item = $grid[$r][$c] ?? null; 
                        $val = '&nbsp;';
                        $class = 'skipped'; 
                        
                        if ($item) {
                            if (!is_null($item->user_answer)) {
                                $val = $item->user_answer;
                                $class = $item->is_correct ? 'correct' : 'wrong';
                            }
                        }
                    @endphp
                    
                    <td>
                        <span class="q-num">{{ $item->top_number ?? '' }}</span>
                        
                        <div class="box {{ $class }}">{!! $val !!}</div>
                        
                        @if($r == 1)
                            <span class="q-num">{{ $item->bottom_number ?? '' }}</span>
                        @endif
                    </td>
                @endfor
            </tr>
        @endfor

        <tr style="border-top: 1px solid #000;">
            <td class="stat-label" style="color: #166534; padding-top: 5px;">BENAR</td>
            @for($c = 1; $c <= $maxCol; $c++)
                <td><div class="stat-val" style="color: #166534;">{{ $stats[$c]['correct'] ?? 0 }}</div></td>
            @endfor
        </tr>
        <tr>
            <td class="stat-label" style="color: #991b1b;">SALAH</td>
            @for($c = 1; $c <= $maxCol; $c++)
                <td><div class="stat-val" style="color: #991b1b;">{{ $stats[$c]['wrong'] ?? 0 }}</div></td>
            @endfor
        </tr>
    </table>

</body>
</html>