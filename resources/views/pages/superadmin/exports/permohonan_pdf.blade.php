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
    </style>
</head>

<body>
    <h2>Permohonan Magang — Seluruh SKPD</h2>
    <p class="sub">
        Periode: {{ $bulanLabel }} {{ $tahunLabel }} &middot;
        Dicetak: {{ now()->translatedFormat('d M Y, H:i') }}
    </p>

    <table>
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No</th>
                <th>Nama SKPD</th>
                <th>Pemohon</th>
                <th>Tanggal Pengajuan</th>
                <th>Tenggat Waktu</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($antreans as $row)
                @php $ketua = $row->anggota->first(); @endphp
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $row->bidang->skpd->nama_skpd ?? '-' }}</td>
                    <td>{{ $ketua->nama_lengkap ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->translatedFormat('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->batas_verifikasi)->translatedFormat('d M Y') }}</td>
                    <td>{{ $statusResolver($row) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
