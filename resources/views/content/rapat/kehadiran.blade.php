@extends('dashboard')

@section('content')
<div class="p-10">
    <h1 class="text-3xl font-semibold mb-10">Data Kehadiran</h1>
    <div class="overflow-x-auto">
        <table id="my-datatable" class="text-sm w-full bg-[#EBE9EE] rounded-lg">
            <thead>
                <tr>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 w-10">
                        <div class="flex items-center">
                            <input id="checkbox-all-search" type="checkbox"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
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
                            class="checkbox-kehadiran w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                            value="{{ $kehadiran->id_senat }}">
                        <label for="checkbox-table-search-{{ $kehadiran->id_senat }}" class="sr-only">checkbox</label>
                    </div>
                </td>
                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                    <span class="block text-left">{{ $counter++ }}</span>
                </td>
                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                    {{ $kehadiran->senat->name }}
                </td>
                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                    {{ $kehadiran->waktu }}
                </td>
                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                    {{ $kehadiran->verifikasi }}
                </td>
                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                    @if ($kehadiran->verifikasi == 'Hadir' || $kehadiran->verifikasi == 'Tidak Hadir')
                    <span>Sudah Diverifikasi</span>
                    @else
                    <span>Belum Diverifikasi</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    @if (Auth::user()->hasRole('pimpinan') || Auth::user()->hasRole('admin'))
    <div class="mt-4">
        <!-- Tampilkan jumlah item yang dipilih di sini -->
        <p class="basis-1/8 text-sm font-medium text-gray-700"><span id="selected-count">0</span> item dipilih</p>
        <div class="mt-2">
            @if ($rapat != null)
            <form id="verifyForm" action="{{ route('verif.selected', [$rapat->id_rapat]) }}" method="POST"
                class="flex items-center gap-2">
                @csrf
                <div class="">
                    <select name="status" id="status" required
                        class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm py-2 px-2.5">
                        <option value="Hadir">Hadir</option>
                        <option value="Tidak Hadir">Tidak Hadir</option>
                    </select>
                    <input type="hidden" id="selected-senats" name="selected_senats" value="">
                    <input type="hidden" id="all-senats" name="all_senats" value="{{ implode(',', $allKehadiranIds) }}">
                </div>
                <div class="mt-1">
                    <button type="submit" onclick="confirmVerify(event)"
                        class="text-sm inline-block w-fit rounded-lg bg-[#6E2BB1] hover:bg-[#8b3ce1] px-10 py-2 font-medium text-white sm:w-auto transition-all">
                        Verify
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>
    @endif
    <div class="mt-4">
        @if ($rapat != null)
        <a href="{{ route('export.kehadiran', [$rapat->id_rapat]) }}"
            class="text-sm inline-block w-fit rounded-lg bg-[#6E2BB1] hover:bg-[#8b3ce1] px-10 py-2 font-medium text-white sm:w-auto transition-all">
            Print Kehadiran
        </a>
        @endif
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />

<script>
    function confirmVerify(event) {
        event.preventDefault();
        Swal.fire({
            text: 'Verifikasi kehadiran?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Verifikasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form jika dikonfirmasi
                document.getElementById('verifyForm').submit();
            }
        });
    }

    $(document).ready(function() {
        const table = $('#my-datatable').DataTable({
            "paging": true,
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 100],
            "order": [
                [1, 'asc']
            ],
        });

        const checkboxAll = document.getElementById('checkbox-all-search');
        const selectedCountSpan = document.getElementById('selected-count');
        const selectedSenatsInput = document.getElementById('selected-senats');
        const allSenatsInput = document.getElementById('all-senats');

        function updateSelectedCountAndSenats() {
            const selectedSenats = table.$('.checkbox-kehadiran:checked').map(function() {
                return this.value;
            }).get();
            selectedCountSpan.textContent = selectedSenats.length;
            selectedSenatsInput.value = selectedSenats.join(',');
        }

        checkboxAll.addEventListener('change', function() {
            const checkboxes = table.$('.checkbox-kehadiran');
            checkboxes.prop('checked', checkboxAll.checked);
            updateSelectedCountAndSenats();
        });

        $('#my-datatable').on('change', '.checkbox-kehadiran', function() {
            updateSelectedCountAndSenats();
        });
    });
</script>
@endsection