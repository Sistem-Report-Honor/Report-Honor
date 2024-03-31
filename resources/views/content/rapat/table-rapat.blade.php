@extends('dashboard')

@section('content')
    <h1 class="text-3xl font-semibold mb-10">Detail Rapat</h1>
    <div class="w-full h-[1px] bg-[#666]"></div>
    <div
        class="mt-10 rounded-lg p-6 flex gap-4 justify-center md:justify-start lg:justify-start flex-wrap bg-[#EBE9EE] min-h-screen">
        @foreach ($rapats as $rapat)
            <div class="bg-white rounded-lg p-4 shadow-sm shadow-indigo-100 w-fit h-fit">
                @if ($rapat->status == 'prepare')
                    <div class="relative ">
                        <img alt="QR Code" src="{{ asset('storage/' . $rapat->qr_code) }}"
                            class="h-fit w-full rounded-md object-cover border" />
                        <span
                            class="block absolute left-[40%] top-[50%] translate-y-[-50%] translate-x-[-30%] w-fit h-fit border border-white py-1 px-2.5 rounded-lg text-center text-white">
                            Rapat belum dimulai
                        </span>
                    </div>
                @elseif ($rapat->status == 'mulai')
                    <img alt="QR Code" src="{{ asset('storage/' . $rapat->qr_code) }}"
                        class="h-fit w-full rounded-md object-cover border" />
                @elseif ($rapat->status == 'selesai')
                    <div class="relative ">
                        <img alt="QR Code" src="{{ asset('storage/' . $rapat->qr_code) }}"
                            class="h-fit w-full rounded-md object-cover border" />
                        <span
                            class="block absolute left-[40%] top-[50%] translate-y-[-50%] translate-x-[-30%] w-fit h-fit border border-white py-1 px-2.5 rounded-lg text-center text-white">
                            Rapat telah selesai
                        </span>
                    </div>
                @endif

                <div class="mt-2">
                    <div>
                        <dt class="sr-only">Rapat</dt>

                        <dd class="font-semibold">Rapat {{ $rapat->komisi->komisi }}</dd>
                    </div>

                    @if ($rapat->status != 'prepare')
                        <div class="mt-4">
                            <a class="inline-flex items-center gap-2 text-sm text-blue-600 underline"
                                href="{{ route('kehadiran.rapat', [$rapat->id]) }}">
                                <span>Cek Kehadiran</span>
                            </a>
                        </div>
                    @endif

                    <div class="mt-4 flex items-center gap-6 text-xs justify-evenly bg-[#faf5ff] p-2.5 rounded-md">
                        <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                            <i class='bx bx-calendar text-xl text-[#6e2bb1]'></i>

                            <div class="mt-1.5 sm:mt-0">
                                <p class="text-gray-500">Tanggal</p>

                                <p class="font-medium">{{ $rapat->tanggal }}</p>
                            </div>
                        </div>

                        <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                            <i class='bx bx-time text-xl text-[#6e2bb1]'></i>

                            <div class="mt-1.5 sm:mt-0">
                                <p class="text-gray-500">Jam Mulai</p>

                                <p class="font-medium ">{{ $rapat->jam }} WIB</p>
                            </div>
                        </div>

                        <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                            <i class='bx bx-info-circle text-xl text-[#6e2bb1]'></i>

                            <div class="mt-1.5 sm:mt-0">
                                <p class="text-gray-500">Status</p>

                                <p class="font-medium capitalize">{{ $rapat->status }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        @if ($rapat->status == 'prepare')
                            <form id="" action="{{ route('mulai', $rapat->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Apakah anda ingin memulai rapat ?')"
                                    class="w-full flex items-center justify-center gap-2 hover:gap-2.5 rounded-md bg-[#6E2BB1] px-4 py-2 text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">
                                    <span class="block">Mulai Rapat</span>
                                    <i class='bx bxs-chevron-right mt-0.5 text-[1rem]'></i>
                                </button>
                            </form>
                        @elseif ($rapat->status == 'mulai')
                            <form id="" action="{{ route('selesai', $rapat->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Apakah anda ingin mengakhiri rapat ?')"
                                    class="w-full flex items-center justify-center gap-2 rounded-md bg-[#c23c44] px-4 py-2 text-xs font-semibold text-white hover:bg-[#d75c5d] transition-all">
                                    <span>Akhiri Rapat</span>
                                </button>
                            </form>
                        @elseif ($rapat->status == 'selesai')
                            <span
                                class="w-full flex items-center justify-center gap-2 rounded-md px-4 py-2 text-xs font-semibold text-gray-500 border">
                                Rapat telah selesai
                            </span>
                        @endif
                    </div>

                </div>
            </div>
        @endforeach
    </div>
@endsection
