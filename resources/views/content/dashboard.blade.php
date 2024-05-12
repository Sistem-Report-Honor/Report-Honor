@extends('dashboard')

@section('content')
    <div class="w-full h-[501px] bg-cover bg-no-repeat py-10 px-16 flex items-end"
        style="background-image: url(images/dash-img.svg)">
        <div class="text-white font-bold">
            <h1 class="text-[64px]">Selamat Datang</h1>
            <p class="text-5xl">Senat Politeknik Negeri Medan</p>
        </div>
    </div>
    <div class="p-10">
        <div class="mt-14 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-14 text-center text-white place-items-center">
            <div
                class="bg-gradient-to-r from-[#915CC7] to-[#6E2BB1] p-10 rounded-lg w-[581px] h-[198px] flex justify-evenly gap-6 items-center text-8xl font-semibold">
                <span>56</span>
                <p class="text-[30px] col-span-2 text-left">Anggota Senat Politeknik Negeri Medan</p>
            </div>
            <div
                class="bg-gradient-to-r from-[#915CC7] to-[#6E2BB1] p-10 rounded-lg w-[581px] h-[198px] flex justify-evenly gap-6 items-center text-8xl font-semibold">
                <span>10</span>
                <p class="text-[30px] col-span-2 text-left">Rapat Terlaksana</p>
            </div>
        </div>
        <div class="my-12 flex gap-8 items-center">
            <p class="font-bold text-2xl capitalize min-w-fit">Rapat Yang Sedang Berlangsung</p>
            <div class="w-full h-[1.5px] bg-[#666] mt-2"></div>
        </div>
        <div class="text-xl text-white bg-gradient-to-r from-[#915CC7] to-[#6E2BB1] p-6 rounded-lg mt-5">
            <div class="flex justify-between items-center">
                <p>24/03/2024</p>
                <p>10.00 WIB</p>
            </div>
            <p class="mt-2.5">Nama Rapat / Komisi 1</p>
        </div>
    </div>
@endsection
