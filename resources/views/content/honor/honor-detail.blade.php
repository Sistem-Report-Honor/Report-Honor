@extends('dashboard')

@section('content')
    <div class="p-10">
        <h1 class="text-3xl font-semibold mb-10">Laporan Honor Bulanan</h1>
        <div class="flex justify-between mb-4">
            <form action="{{ route('print.honor.detail') }}" method="GET">
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button type="submit"
                    class="inline-block rounded-md bg-[#6E2BB1] px-4 py-2 text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">Print Laporan</button>
            </form>
            <form action="{{ route('list.honor.detail') }}" method="GET" class="flex gap-2 items-center">
                <div>
                    <label for="bulan" class="mr-2">Pilih Bulan:</label>
                    <select name="bulan" id="bulan" class="border rounded-md px-2 py-1">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="tahun" class="mr-2 ml-4">Pilih Tahun:</label>
                    <select name="tahun" id="tahun" class="border rounded-md px-2 py-1">
                        @for ($i = date('Y'); $i >= 2010; $i--)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <button type="submit"
                    class="inline-block rounded-md bg-blue-500 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition-all">Filter</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table id="my-datatable" class="text-sm w-full bg-[#EBE9EE] rounded-lg">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 w-10" rowspan="3">No.</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="3">Nama</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="3">GP</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="3">Jabatan dalam Senat
                        </th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="3">Komisi</th>

                        <!-- ini jenis rapat -->
                        @foreach ($rapats as $rapat)
                            <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3">
                                <span class="block text-center">{{ $rapat->komisi->komisi }}</span>
                                <span
                                    class="block text-center font-thin">{{ date('d M Y', strtotime($rapat->tanggal)) }}</span>
                            </th>
                        @endforeach

                        @php
                            $firstMonthTaken = false; // variabel sementara untuk menyimpan status bulan pertama telah diambil atau belum
                        @endphp

                        <!-- total honor perbulan -->
                        @foreach ($rapats as $rapat)
                            @if (!$firstMonthTaken)
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3">
                                    Total Honor Rapat Senat {{ date('M', strtotime($rapat->tanggal)) }}
                                </th>
                                @php
                                    $firstMonthTaken = true; // mengatur status bahwa bulan pertama telah diambil
                                @endphp
                            @endif
                        @endforeach

                        <!-- ini npwp -->
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="4">
                            <span class="text center">NPWP</span>
                        </th>
                    </tr>

                    <!-- honor per rapat -->
                    <tr>
                        @foreach ($rapats as $rapat)
                            <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">Honor</th>
                            <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">PPH</th>
                            <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">Diterima</th>
                        @endforeach
                    </tr>

                    <!-- honor perbulan -->
                    <tr>
                        <th class="whitespace-nowrap px-5 py-2 font-medium text-gray-900">Honor</th>
                        <th class="whitespace-nowrap px-5 py-2 font-medium text-gray-900">PPH</th>
                        <th class="whitespace-nowrap px-5 py-2 font-medium text-gray-900">Diterima</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($senats as $index => $senat)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                                {{ $index + 1 }}</td>
                            <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                                {{ $senat->name }}</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                                {{ $senat->golongan->golongan }}</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                                {{ $senat->jabatan }}</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                                {{ $senat->komisi->komisi }}</td>

                            @foreach ($rapats as $rapat)
                                @php
                                    $senat_id = $senat->id;
                                    $rapat_id = $rapat->id;
                                    $is_kehadiran = isset($kehadirans[$senat_id][$rapat_id])
                                        ? $kehadirans[$senat_id][$rapat_id]
                                        : false;
                                @endphp
                                @if ($is_kehadiran)
                                    <td
                                        class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400 text-center">
                                        {{ isset($senat->golongan) ? $senat->golongan->honor : '-' }}</td>
                                    <td
                                        class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400 text-center">
                                        {{ isset($senat->golongan) ? $senat->golongan->pph : '-' }}</td>
                                    <td
                                        class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400 text-center">
                                        {{ isset($honorariumsPerRapat[$rapat->id]) ? $honorariumsPerRapat[$rapat->id] : 'N/A' }}
                                    </td>
                                @else
                                    <td
                                        class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400 text-center">
                                        -</td>
                                    <td
                                        class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400 text-center">
                                        -</td>
                                    <td
                                        class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400 text-center">
                                        -</td>
                                @endif
                            @endforeach

                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                                {{ isset($honors[$senat->id]) ? $honors[$senat->id] : 'N/A' }}</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                                {{ isset($pphs[$senat->id]) ? $pphs[$senat->id] : 'N/A' }}</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                                {{ isset($honorariumsPerSenat[$senat->id]) ? $honorariumsPerSenat[$senat->id] : 'N/A' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                                {{ $senat->NPWP }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
