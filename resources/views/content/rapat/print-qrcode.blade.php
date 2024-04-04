@extends('dashboard')

@section('content')
    <h1 class="text-3xl font-semibold mb-10">Detail Rapat</h1>
    <div class="w-full h-[1px] bg-[#666]"></div>

    <div class="mt-10 flex flex-col gap-4 items-center" id="contentToPrint" style="text-align: center;">
        <h2  style="text-align: center;" class="text-xl font-semibold">Informasi Rapat</h2>

        <p  style="text-align: center;">Nama Rapat: {{ $rapat->komisi->komisi }}</p>
        <p  style="text-align: center;">Tanggal: {{ $rapat->tanggal }}</p>
        <p  style="text-align: center;">Jam: {{ $rapat->jam }}</p>

        <img src="{{ asset('storage/' . $rapat->qr_code) }}" alt="QR Code" style="margin: 0 auto;">
    </div>

    <button onclick="printContent()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Print Detail Rapat</button>

    <script>
        function printContent() {
            var contentToPrint = document.getElementById('contentToPrint');
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = contentToPrint.innerHTML;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
