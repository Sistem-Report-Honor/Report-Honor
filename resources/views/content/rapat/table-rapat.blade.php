@extends('dashboard')

@section('content')
    <div class="grid grid-cols-3 gap-3">
        @foreach ($rapats as $rapat)
            <div class="w-80 p-4 bg-white rounded-lg">
                <img src="{{ asset('storage/' . $rapat->qr_code) }}" alt="QR Code"
                    class="h-fit w-fit rounded-md object-cover">

                <div class="mt-2">
                    <dl>
                        <div>
                            <dd class="font-medium">Rapat {{ $rapat->komisi->komisi }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 flex items-center gap-8 text-xs">
                        <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                            <div class="mt-1.5 sm:mt-0">
                                <p class="text-gray-500">Tanggal</p>

                                <p class="font-medium">{{ $rapat->tanggal }}</p>
                            </div>
                        </div>

                        <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                            <div class="mt-1.5 sm:mt-0">
                                <p class="text-gray-500">Jam Dimulai</p>

                                <p class="font-medium">{{ $rapat->jam }}</p>
                            </div>
                        </div>

                        <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                            <div class="mt-1.5 sm:mt-0">
                                <p class="text-gray-500">Status</p>

                                <p class="font-medium">{{ $rapat->status }}</p>
                            </div>
                        </div>
                    </div>
                    <dl class="mt-2">
                        <a class="inline-flex items-center gap-2 rounded border border-indigo-600 bg-indigo-600 px-4 py-2 text-white hover:bg-transparent hover:text-indigo-600 focus:outline-none focus:ring active:text-indigo-500"
                            href="{{ route('kehadiran.rapat', $rapat->id) }}">
                            <span>Kehadiran</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                            </svg>
                        </a>
                        @if ($rapat->status == 'prepare')
                            <form id="" action="{{ route('mulai', $rapat->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Mau Mulai Rapat Ini?')"
                                    class="inline-flex items-center gap-2 rounded border border-emerald-600 bg-emerald-600 px-4 py-2 text-white hover:bg-transparent hover:text-emerald-600 focus:outline-none focus:ring active:text-emerald-500">
                                    <span>Mulai</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                    </svg>
                                </button>
                            </form>
                        @else
                            @if ($rapat->status == 'mulai')
                                <form id="" action="{{ route('selesai', $rapat->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 rounded border border-red-600 bg-red-600 px-4 py-2 text-white hover:bg-transparent hover:text-red-600 focus:outline-none focus:ring active:text-red-500">
                                        <span>Akhiri</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        @endif

                    </dl>
                </div>
            </div>
        @endforeach
    </div>
@endsection
