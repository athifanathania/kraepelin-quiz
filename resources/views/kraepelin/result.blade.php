<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Tes Kraepelin - Sesi #{{ $session->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            border-radius: 12px;
            padding: 1.5rem 2rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }
        h1 {
            margin-top: 0;
            margin-bottom: .25rem;
        }
        .badge {
            display: inline-block;
            padding: .15rem .5rem;
            border-radius: 999px;
            font-size: .75rem;
            background: #dcfce7;
            color: #166534;
        }
        .meta {
            font-size: .875rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }
        .box {
            padding: 1rem;
            border-radius: .75rem;
            background: #f9fafb;
            border: 1px dashed #e5e7eb;
            font-size: .9rem;
            margin-bottom: 1.5rem;
        }
        .button {
            display: inline-block;
            margin-top: 1rem;
            padding: .5rem 1rem;
            border-radius: .5rem;
            background: #f97316;
            color: white;
            text-decoration: none;
            font-size: .9rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: .9rem;
        }
        th, td {
            padding: .35rem .25rem;
            border-bottom: 1px solid #f3f4f6;
        }
        th {
            border-bottom-color: #e5e7eb;
            text-align: left;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Hasil Tes Kraepelin</h1>
    <div class="meta">
        Sesi ID: <strong>#{{ $session->id }}</strong><br>
        Status: <span class="badge">{{ $session->status }}</span><br>
        Dimulai: {{ $session->started_at?->format('d-m-Y H:i:s') ?? '-' }}
    </div>

    @php
        $accuracy = $totalAnswered > 0
            ? round(($totalCorrect / $totalAnswered) * 100, 1)
            : 0;
    @endphp

    <div class="box">
        <p><strong>Ringkasan:</strong></p>
        <p>
            Total jawaban terisi: <strong>{{ $totalAnswered }}</strong><br>
            Benar: <strong>{{ $totalCorrect }}</strong> |
            Salah: <strong>{{ $totalWrong }}</strong><br>
            Akurasi: <strong>{{ $accuracy }}%</strong>
        </p>
        <p style="font-size: .8rem; color: #6b7280;">
            Catatan: ini masih ringkasan sederhana. Nanti bisa dikembangkan jadi interpretasi
            psikotes sesuai panduan yang kamu pakai.
        </p>
    </div>

    <h3>Rekap per Kolom</h3>

    <table>
        <thead>
        <tr>
            <th>Kolom</th>
            <th style="text-align: right;">Diisi</th>
            <th style="text-align: right;">Benar</th>
            <th style="text-align: right;">Salah</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($stats as $col => $colStats)
            <tr>
                <td>Col {{ $col }}</td>
                <td style="text-align: right;">{{ $colStats['answered'] }}</td>
                <td style="text-align: right; color:#16a34a;">{{ $colStats['correct'] }}</td>
                <td style="text-align: right; color:#b91c1c;">{{ $colStats['wrong'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('kraepelin.start') }}" class="button">
        Mulai sesi baru
    </a>
</div>
</body>
</html>
