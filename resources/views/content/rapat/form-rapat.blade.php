@extends('dashboard')

@section('content')
    <div class="rounded-lg bg-white p-8 shadow-lg lg:col-span-3 lg:p-12">
      <!-- Validation Errors -->
      <x-auth-validation-errors class="mb-4" :errors="$errors" />
        <form action="{{ route('create.rapat') }}" method="POST">
            @csrf
            <label for="nama">Nama Rapat:</label><br>
            <input type="text" id="nama" name="nama"><br><br>
            <label for="tanggal">Tanggal Rapat:</label><br>
            <input type="date" id="tanggal" name="tanggal"><br><br>
            <label for="jam">Jam Rapat:</label><br>
            <input type="time" id="jam" name="jam"><br><br>
            <button type="submit">Buat QR Code</button>
        </form>
    </div>
@endsection
