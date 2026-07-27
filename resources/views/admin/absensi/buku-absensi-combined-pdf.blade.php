<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buku Absensi {{ $kelas->nama_kelas }} - {{ $bulanLabel }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', 'Helvetica', sans-serif; font-size: 9px; color: #1e293b; margin: 0; padding: 0; }
    </style>
</head>
<body>
    @include('admin.absensi.buku-absensi-cover-pdf')
    <div style="page-break-before: always;"></div>
    @include('admin.absensi.buku-absensi-pdf')
</body>
</html>
