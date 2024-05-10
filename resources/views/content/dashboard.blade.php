@extends('dashboard')

@section('content')
    <h1 class="text-5xl font-semibold">Selamat Datang</h1>
    <p class="font-semibold text-2xl capitalize">{{ Auth::user()->name }}</p>
    <canvas class="w-full h-[1.5px] bg-[#666] mt-10 "></canvas>
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-14 text-center">
        <div class="space-y-6 mx-auto">
            <p class="font-semibold text-2xl">Total Senat</p>
            <div class="bg-[#EBE9EE] rounded w-[250px] h-[250px] grid place-items-center text-8xl font-semibold">56</div>
        </div>
        <div class="space-y-6 mx-auto">
            <p class="font-semibold text-2xl">Total Rapat Berlangsung</p>
            <div class="bg-[#EBE9EE] rounded w-[250px] h-[250px] grid place-items-center text-8xl font-semibold">1</div>
        </div>
        <div class="space-y-6 mx-auto">
            <p class="font-semibold text-2xl">Total Rapat Keseluruhan</p>
            <div class="bg-[#EBE9EE] rounded w-[250px] h-[250px] grid place-items-center text-8xl font-semibold">10</div>
        </div>
    </div>
    <canvas class="w-full h-[1.5px] bg-[#666] mt-12 "></canvas>
    <p class="font-bold text-2xl capitalize mt-12">Rapat Yang Sedang Berlangsung</p>
    <div class="lg:w-[875px] h-[140px] bg-[#EBE9EE] p-6 rounded-lg mt-5">
        <p class="text-xl leading-loose">24/03/2024 - 10.00 WIB <br> Nama Rapat</p>
    </div>
@endsection
