@extends('dashboard')

@section('content')
    <div class="w-full h-[301px] lg:h-[501px] bg-cover bg-no-repeat py-6 lg:py-10 px-6 lg:px-16 flex items-end"
        style="background-image: url(images/dash-img.svg)">
        <div class="text-white font-bold">
            <h1 class="text-4xl lg:text-[64px] leading-none">Selamat Datang</h1>
            <p class="text-xl lg:text-5xl">Senat Politeknik Negeri Medan</p>
        </div>
    </div>
    <div class="p-10">
        <div
            class="lg:mt-14 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8 lg:gap-14 text-center text-white place-items-center">
            <div
                class="bg-gradient-to-r from-[#915CC7] to-[#6E2BB1] p-6 lg:p-10 rounded-lg w-full h-full flex flex-wrap gap-2 lg:gap-6 items-center font-semibold">
                <span class="text-4xl lg:text-8xl">{{ $senatCount }}</span>
                <p class="text-md lg:text-[30px] col-span-2 text-left">Anggota Senat Politeknik Negeri Medan</p>
            </div>
            <div
                class="bg-gradient-to-r from-[#915CC7] to-[#6E2BB1] p-6 lg:p-10 rounded-lg w-full h-full flex flex-wrap gap-2 lg:gap-6 items-center font-semibold">
                <span class="text-4xl lg:text-8xl">{{ $rapatSelesaiCount }}</span>
                <p class="text-md lg:text-[30px] col-span-2 text-left">Rapat Terlaksana</p>
            </div>
        </div>
        @if ($rapatMulai->isEmpty())
            <div class="mt-12 flex lg:gap-2 items-center flex-wrap">
                <p class="font-bold text-sm lg:text-2xl capitalize min-w-fit text-black/90">Tidak ada rapat yang sedang
                    berlangsung</p>
                <div class="w-full h-[1.5px] bg-[#666] mt-2"></div>
            </div>
            <div
                class="lg:text-xl text-gray-600 bg-[#EBE9EE] p-4 lg:p-6 rounded-lg mt-4 lg:mt-6">
                <div class="flex justify-between items-center">
                    <p>00/00/0000</p>
                    <p>00.00 WIB</p>
                </div>
                <div class="mt-4 text-gray-600 flex items-center gap-2">
                    <i class='bx bx-info-circle mt-1'></i>
                    <span>No data</span>
                </div>
            </div>
        @else
            @foreach ($rapatMulai as $rapat)
                <div class="mt-12 flex lg:gap-2 items-center flex-wrap">
                    <p class="font-bold text-sm lg:text-2xl capitalize min-w-fit text-black/90">Rapat Yang Sedang
                        Berlangsung</p>
                    <div class="w-full h-[1.5px] bg-[#666] mt-2"></div>
                </div>
                <div
                    class="lg:text-xl text-white bg-gradient-to-r from-[#915CC7] to-[#6E2BB1] p-4 lg:p-6 rounded-lg mt-4 lg:mt-6">
                    <div class="flex justify-between items-center">
                        <p>{{ $rapat->tanggal }}</p>
                        <p>{{ $rapat->jam }}</p>
                    </div>
                    <p class="mt-2.5">{{ $rapat->komisi->komisi }}</p>
                </div>
            @endforeach
        @endif
    </div>
@endsection

