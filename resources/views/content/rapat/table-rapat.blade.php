@extends('dashboard')

@section('content')
    <h1 class="text-3xl font-semibold mb-10">Detail Rapat</h1>
    <div class="w-full h-[1px] bg-[#666]"></div>
    <div class="flex overflow-x-auto overflow-y-hidden border-b border-gray-200 whitespace-nowrap mt-10 mb-6">
        <a href="#" data-tab="rapat-sekarang"
            class="tab-btn inline-flex items-center h-10 px-4 -mb-px text-sm text-center bg-transparent border-b-2 border-transparent sm:text-base whitespace-nowrap focus:outline-none active:text-[#6E2BB1] active:border-[#6E2BB1]">
            Rapat Sekarang
        </a>

        <a href="#" data-tab="rapat-selesai"
            class="tab-btn inline-flex items-center h-10 px-4 -mb-px text-sm text-center bg-transparent border-b-2 border-transparent sm:text-base whitespace-nowrap cursor-base focus:outline-none hover:border-gray-400">
            Rapat Selesai
        </a>

    </div>
    <div class="rounded-lg p-6 bg-[#EBE9EE] min-h-screen">
        <div id="rapat-sekarang" class="tab-content grid lg:grid-cols-4 gap-4">
            @php
                $filteredRapats = $rapats->filter(function ($rapat) {
                    return $rapat->status == 'prepare' || $rapat->status == 'mulai';
                });
            @endphp

            @if ($filteredRapats->isEmpty())
                <div class="text-gray-600 flex flex-col items-center justify-center gap-1.5 col-span-4 w-full h-screen">
                    <i class='bx bx-info-circle text-4xl'></i>
                    <span>Tidak ada rapat yang sedang berlangsung.</span>
                </div>
            @else
                @foreach ($filteredRapats as $rapat)
                    <div class="bg-white rounded-lg p-4 shadow-sm shadow-indigo-200 w-full h-fit">
                        <div class="relative">
                            <img alt="QR Code" src="{{ asset('storage/' . $rapat->qr_code) }}"
                                class="h-fit w-full rounded-md object-cover border" />
                        </div>
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

                            <div class="mt-4 flex grid lg:grid-cols-2 gap-6 text-xs justify-evenly bg-[#faf5ff] p-2.5 rounded-md">
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
                                        <p class="text-gray-500">Jam</p>
                                        <p class="font-medium">{{ $rapat->jam }} WIB</p>
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
                                    <form id="start-meeting-form" action="{{ route('mulai', $rapat->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="confirmStartMeeting(event)"
                                            class="w-full flex items-center justify-center gap-2 hover:gap-2.5 rounded-md bg-[#6E2BB1] px-4 py-2 text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">
                                            <span class="block">Mulai Absen</span>
                                            <i class='bx bxs-chevron-right mt-0.5 text-[1rem]'></i>
                                        </button>
                                    </form>
                                @elseif ($rapat->status == 'mulai')
                                    <form id="end-meeting-form" action="{{ route('selesai', $rapat->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="confirmEndMeeting(event)"
                                            class="w-full flex items-center justify-center gap-2 rounded-md bg-[#c23c44] px-4 py-2 text-xs font-semibold text-white hover:bg-[#d75c5d] transition-all">
                                            <span>Akhiri Absen</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <a href="{{ route('print.qr', $rapat->id) }}" target="_blank" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">Print QR Code</a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>


        <div id="rapat-selesai" class="hidden tab-content grid lg:grid-cols-4 gap-4">
            @php
                $filteredRapats = $rapats->filter(function ($rapat) {
                    return $rapat->status == 'selesai';
                });
            @endphp

            @if ($filteredRapats->isEmpty())
                <div class="text-gray-600 flex flex-col items-center justify-center gap-1.5 col-span-4 w-full h-screen">
                    <i class='bx bx-info-circle text-4xl'></i>
                    <span>Tidak ada rapat yang telah selesai.</span>
                </div>
            @else
                @foreach ($filteredRapats as $rapat)
                    <div class="bg-white rounded-lg p-4 shadow-sm shadow-indigo-200 w-fit h-fit">
                        <div class="relative">
                            <img alt="QR Code" src="{{ asset('storage/' . $rapat->qr_code) }}"
                                class="h-fit w-full rounded-md object-cover border" />
                        </div>
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
                                        <p class="font-medium">{{ $rapat->jam }} WIB</p>
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
                                <span
                                    class="w-full flex items-center justify-center gap-2 rounded-md px-4 py-2 text-xs font-semibold text-gray-500 border">
                                    Rapat telah selesai
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </div>

    <script>
        function confirmStartMeeting(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Mulai Rapat',
                text: "Apakah Anda ingin memulai rapat? Pastikan untuk memulai rapat hanya pada waktunya!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Mulai Rapat!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form jika dikonfirmasi
                    document.getElementById('start-meeting-form').submit();
                }
            });
        }

        function confirmEndMeeting(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Akhiri Rapat',
                text: "Apakah Anda ingin mengakhiri rapat? Pastikan untuk mengakhiri rapat jika sudah selesai!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Akhiri Rapat!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form jika dikonfirmasi
                    document.getElementById('end-meeting-form').submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            document.getElementById('rapat-sekarang').classList.remove('hidden');
            document.querySelector('[data-tab="rapat-sekarang"]').classList.add('text-[#6E2BB1]',
                'border-[#6E2BB1]');

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabId = this.getAttribute('data-tab');

                    tabs.forEach(tab => {
                        tab.classList.remove('text-[#6E2BB1]', 'border-[#6E2BB1]');
                        tab.classList.add('text-gray-700', 'border-transparent');
                    });
                    this.classList.remove('text-gray-700', 'border-transparent');
                    this.classList.add('text-[#6E2BB1]', 'border-[#6E2BB1]');

                    tabContents.forEach(content => {
                        if (content.id === tabId) {
                            content.classList.remove('hidden');
                        } else {
                            content.classList.add('hidden');
                        }
                    });
                });
            });
        });
    </script>
@endsection
