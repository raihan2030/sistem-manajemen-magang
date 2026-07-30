<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #1f2937;
        }

        h2 {
            margin-bottom: 2px;
            color: #00236F;
        }

        p.sub {
            margin-top: 0;
            color: #6b7280;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #F4F7FF;
            color: #00236F;
            text-transform: uppercase;
            font-size: 9px;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>Daftar Peserta Magang</h2>
    <p class="sub">
        Instansi: {{ $skpdNama }} &middot;
        Filter status: {{ $statusFilter === 'all' ? 'Semua' : $statusFilter }} &middot;
        Dicetak: {{ now()->translatedFormat('d M Y, H:i') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Nama Peserta</th>
                <th>Tipe</th>
                <th>NIM/NISN</th>
                <th>Institusi</th>
                <th>Jurusan</th>
                <th>Periode</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesertas as $row)
                <tr>
                    <td>
                        {{ $row['name'] }}
                        @if ($row['tipe'] === 'Kelompok')
                            <br><small style="color: #00236F">(+{{ $row['total_anggota'] - 1 }} anggota)</small>
                        @endif
                    </td>
                    <td>{{ $row['tipe'] }}</td>
                    <td>{{ $row['nim'] }}</td>
                    <td>{{ $row['institusi_asal'] }}</td>
                    <td>{{ $row['jurusan_prodi'] }}</td>
                    <td>
                        {{ $row['tanggal_mulai'] ? \Carbon\Carbon::parse($row['tanggal_mulai'])->translatedFormat('d M Y') : '-' }}
                        -
                        {{ $row['tanggal_selesai'] ? \Carbon\Carbon::parse($row['tanggal_selesai'])->translatedFormat('d M Y') : '-' }}
                    </td>
                    <td>{{ $row['status'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
