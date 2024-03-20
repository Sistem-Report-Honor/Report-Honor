@extends('dashboard')

@section('content')
    <div class="rounded-lg bg-white p-8 shadow-lg lg:col-span-3 lg:p-12">
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        <form action="{{ route('create.rapat') }}" method="POST">
            @csrf
            <div>
                <label for="id_komisi" class="komisi">Komisi</label>
                <select id="id_komisi" name="id_komisi" class="w-full rounded-lg border-gray-200 p-3 text-sm">
                    @foreach ($komisis as $komisi)
                        <option value="{{ $komisi->id }}">{{ $komisi->komisi }}</option>
                    @endforeach
                </select>
            </div>
            <div  class="mt-6">
                <label for="tanggal">Tanggal Rapat:</label><br>
                <input type="date" id="tanggal" name="tanggal"><br><br>
                <label for="jam">Jam Rapat:</label><br>
                <input type="time" id="jam" name="jam"><br><br>
            </div>
            <button type="submit" class="inline-block w-full rounded-lg bg-black px-5 py-3 font-medium text-white sm:w-auto">Buat QR Code</button>
        </form>
    </div>
@endsection
