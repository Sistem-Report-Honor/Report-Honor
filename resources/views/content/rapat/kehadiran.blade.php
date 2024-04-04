@extends('dashboard')

@section('content')
    <h1 class="text-3xl font-semibold mb-10">
        Data Kehadiran</h1>
    <div class="overflow-x-auto">
        <table id="my-datatable" class="text-sm w-full bg-[#EBE9EE] rounded-lg">
            <thead>
                <tr>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 w-10">
                        <div class="flex items-center">
                            <input id="checkbox-all-search" type="checkbox"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 ">
                            <label for="checkbox-all-search" class="px-2">All</label>
                        </div>
                    </th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 w-10">No</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Nama Senat</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                        <span class="block text-left">Waktu Absen</span>
                    </th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Status</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Hadir/Tidak</th>
                </tr>
            </thead>
            @php
                $counter = 1;
            @endphp
            @foreach ($kehadirans as $kehadiran)
                <tr>
                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                        <div class="flex items-center">
                            <input id="checkbox-table-search-{{ $kehadiran->id_senat }}" type="checkbox"
                                class="checkbox-kehadiran w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 "
                                value="{{ $kehadiran->id_senat }}">
                            <label for="checkbox-table-search-{{ $kehadiran->id_senat }}" class="sr-only">checkbox</label>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                        <span class="block text-left">{{ $counter++ }}</span>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                        {{ $kehadiran->senat->name }}</td>
                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                        {{ $kehadiran->waktu }}</td>
                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                        {{ $kehadiran->verifikasi }}</td>
                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                        @if ($kehadiran->verifikasi == 'Hadir' || $kehadiran->verifikasi == 'Tidak Hadir')
                            <span>Sudah Diverifikasi</span>
                        @else
                            <span>Belum Diverifikasi</span>
                        @endif
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if (Auth::user()->hasRole('pimpinan') || Auth::user()->hasRole('admin'))
        <div class="mt-4">
            <!-- Tampilkan jumlah item yang dipilih di sini -->
            <p class="basis-1/8 text-sm font-medium text-gray-700"><span id="selected-count">0</span> item dipilih</p>
            <div class="mt-2">
                @if ($rapat != null)
                    @if ($rapat->rapat->status != 'selesai')
                        <form id="verifyForm" action="{{ route('verif.selected', [$rapat->id_rapat]) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <div class="">
                                <select name="status" id="status" required
                                    class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm py-2 px-2.5">
                                    <option value="Hadir">Hadir</option>
                                    <option value="Tidak Hadir">Tidak Hadir</option>
                                </select>
                                <input type="hidden" id="selected-senats" name="selected_senats" value="">
                            </div>
                            <div class="mt-1">
                                <button type="submit" onclick="confirmVerify(event)"
                                    class="text-sm inline-block w-fit rounded-lg bg-[#6E2BB1] hover:bg-[#8b3ce1] px-10 py-2 font-medium text-white sm:w-auto transition-all">
                                    Verify
                                </button>
                            </div>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    @endif
    <script>
        function confirmVerify(event) {
            event.preventDefault();
            Swal.fire({
                text: 'Verifikasi kehadiran?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Verikasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form jika dikonfirmasi
                    document.getElementById('verifyForm').submit();
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxAll = document.getElementById('checkbox-all-search');
            const checkboxes = document.querySelectorAll('.checkbox-kehadiran');
            const selectedCountSpan = document.getElementById('selected-count');

            checkboxAll.addEventListener('change', function() {
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = checkboxAll.checked;
                });
            });

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
