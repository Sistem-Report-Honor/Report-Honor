@extends('dashboard')

@section('content')
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="pb-4 bg-white">
            <label for="table-search" class="sr-only">Search</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="table-search"
                    class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 "
                    placeholder="Search for items">
            </div>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="p-4">
                        <div class="flex items-center">
                            <input id="checkbox-all-search" type="checkbox"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 ">
                            <label for="checkbox-all-search" class="sr-only">checkbox</label>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nama Senat
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Waktu Absen
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Hadir/Tidak
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kehadirans as $kehadiran)
                    <tr class="bg-white border-b  hover:bg-gray-50">
                        <td class="w-4 p-4">
                            <div class="flex items-center">
                                <input id="checkbox-table-search-{{ $kehadiran->id_senat }}" type="checkbox"
                                    class="checkbox-kehadiran w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 "
                                    value="{{ $kehadiran->id_senat }}">
                                <label for="checkbox-table-search-{{ $kehadiran->id_senat }}"
                                    class="sr-only">checkbox</label>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            {{ $kehadiran->senat->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $kehadiran->waktu }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $kehadiran->verifikasi }}
                        </td>
                        <td class="px-4 py-4">
                            @if ($kehadiran->verifikasi == 'Hadir' || $kehadiran->verifikasi == 'Tidak Hadir')
                                Sudah Di Verifikasi
                            @else
                                <div class="flex gap-2">
                                    <div>
                                        <form
                                            action="{{ route('verif', ['id_rapat' => $kehadiran->id_rapat, 'id_senat' => $kehadiran->id_senat]) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Hadir">
                                            <button type="submit"
                                                onclick="return confirm('Apakah Benar {{ $kehadiran->senat->name }} hadir?')"
                                                class="inline-block rounded bg-green-600 px-4 py-2 text-xs font-medium text-white hover:bg-green-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                    <div>
                                        <form
                                            action="{{ route('verif', ['id_rapat' => $kehadiran->id_rapat, 'id_senat' => $kehadiran->id_senat]) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Tidak Hadir">
                                            <button type="submit"
                                                onclick="return confirm('Apakah Benar {{ $kehadiran->senat->name }} tidak hadir?')"
                                                class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18 18 6M6 6l12 12" />
                                                </svg>

                                            </button>
                                        </form>
                                    </div>
                            @endif

    </div>
    </td>
    </tr>
    @endforeach
    </tbody>
    </table>
    </div>
    <div class="mt-4 flex">
        <!-- Tampilkan jumlah item yang dipilih di sini -->
        <span id="selected-count">0</span> item dipilih
        <form action="{{ route('verif.selected', [$rapat->id_rapat]) }}" method="POST">
            @csrf
            <div class="mt-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="Hadir">Hadir</option>
                    <option value="Tidak Hadir">Tidak Hadir</option>
                </select>
            </div>
            <input type="hidden" id="selected-senats" name="selected_senats" value="">
            <button type="submit" onclick="return confirm('Apakah Anda yakin?')"
                class="inline-block rounded bg-green-600 px-4 py-2 text-xs font-medium text-white hover:bg-green-700 mr-2">
                Verify
            </button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.checkbox-kehadiran');
            const selectedCountSpan = document.getElementById('selected-count');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const selectedCount = document.querySelectorAll('.checkbox-kehadiran:checked')
                        .length;
                    selectedCountSpan.textContent = selectedCount;
                });
            });

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const selectedSenats = [];

                    checkboxes.forEach(function(checkbox) {
                        if (checkbox.checked) {
                            selectedSenats.push(checkbox.value);
                        }
                    });

                    const selectedSenatsString = selectedSenats.join(',');
                    document.getElementById('selected-senats').value = selectedSenatsString;
                });
            });
        });
    </script>
@endsection
