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
    <h2>Daftar Permohonan Magang</h2>
    <p class="sub">
        Instansi: {{ $skpdNama }} &middot;
        Filter: {{ $filterLabel }} &middot;
        Dicetak: {{ now()->translatedFormat('d M Y, H:i') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Pemohon</th>
                <th>Email</th>
                <th>Institusi Asal</th>
                <th>Jurusan</th>
                <th>Bidang</th>
                <th>Tanggal Pengajuan</th>
                <th>Status</th>
                <th>SLA</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permohonans as $row)
                @php $ketua = $row->anggota->first(); @endphp
                <tr>
                    <td>{{ $ketua->nama_lengkap ?? ($row->perwakilan->name ?? 'Pemohon') }}</td>
                    <td>{{ $row->perwakilan->email ?? '-' }}</td>
                    <td>{{ $row->institusi_asal ?? '-' }}</td>
                    <td>{{ $ketua->jurusan_prodi ?? '-' }}</td>
                    <td>{{ $row->bidang->nama_bidang ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->translatedFormat('d M Y H:i') }}</td>
                    <td>{{ $row->status }}</td>
                    <td>{{ $slaTextResolver($row) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
