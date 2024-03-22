<!-- resources/views/absen.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <title>Absen Rapat</title>
</head>

<body>
    @if ($rapat->status == 'prepare')
        <h1>Rapat Belum Di Mulai</h1>
        <p>Tidak Dapat Mengisi Kehadiran</p>
    @else @if ($rapat->status =='mulai')
    <h2>Absen Rapat</h2>
    <form action="{{ route('hadir') }}" method="POST">
        @csrf
        <div>
            <label for="id_senat">Nama</label><br>
            <select id="id_senat" name="id_senat" class="w-full rounded-lg border-gray-200 p-3 text-sm">
                @foreach ($senats as $senat)
                    <option value="{{ $senat->id }}">{{ $senat->nip }} - {{ $senat->name }}</option>
                @endforeach
            </select>
        </div><br>
        <label for="kode_unik">Jenis Rapat</label><br>
        <input type="text" id="Komisi" name="Komisi" value="{{ $rapat->komisi->komisi }}" readonly><br><br>
        <input type="text" id="kode_unik" name="kode_unik" value="{{ $rapat->kode_unik }}" hidden>
        <label for="password">Password</label><br>
        <input type="password" name="password" id="password"><br><br>
        <button type="submit">Absen</button>
    </form>
    @else
        <h1>Rapat Telah Selesai</h1>
        <p>Tidak Dapat Mengisi Kehadiran</p>
    @endif
        
    @endif
    
</body>

</html>
