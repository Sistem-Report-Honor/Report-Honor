<!-- resources/views/absen.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Absen Rapat</title>
</head>
<body>
    <h2>Absen Rapat</h2>
    <form action="" method="POST">
        @csrf
        <label for="nama">Nama:</label><br>
        <input type="text" id="nama" name="nama"><br><br>
        <input type="text" id="kode_unik" name="kode_unik" value="{{ $rapat->nama }}"><br><br>
        <button type="submit">Absen</button>
    </form>
</body>
</html>