@extends('dashboard')

@section('content')
<h1 class="text-3xl font-semibold mb-10">Report Honor Pribadi</h1>
    <div class="flex justify-end mb-4">
        <button class="inline-block rounded-md bg-[#6E2BB1] px-4 py-2 text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">Print Report</button>
    </div>
    <div class="overflow-x-auto">
        <table id="my-datatable" class="text-sm w-full bg-[#EBE9EE] rounded-lg">
            <thead>
                <tr>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="3">Nama</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="3">GP</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="3">Jabatan dalam Senat</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="3">Komisi</th>

                    @foreach ($rapats as $rapat)
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3">
                            <span class="block text-center">{{ $rapat->komisi->komisi }}</span>
                            <span class="block text-center font-thin">{{ date('d M Y', strtotime($rapat->tanggal)) }}</span>
                        </th>
                    @endforeach

                    <!-- total honor perbulan -->
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3">Total Honor Rapat Senat Bulan</th>

                    <!-- ini npwp -->
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="4">
                      <span class="text center">NPWP</span>
                    </th>
                </tr>>
                <!-- honor perapat -->
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
            @if ($senat)
            <tr>
                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">{{ $senat->name }}</td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->golongan->golongan ?? '-' }}</td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->jabatan }}</td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->komisi->komisi }}</td>

            @foreach ($rapats as $rapat)
            @php
                $rapat_id = $rapat->id;
                $is_kehadiran = isset($kehadirans[$senat->id][$rapat_id]) ? $kehadirans[$senat->id][$rapat_id] : false;
            @endphp
            @if ($is_kehadiran)
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400 text-center">{{ isset($senat->golongan) ? $senat->golongan->honor : '-' }}</td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400 text-center">{{ isset($senat->golongan) ? $senat->golongan->pph : '-' }}</td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400 text-center">{{ isset($honorariumsPerRapat[$rapat->id]) ? $honorariumsPerRapat[$rapat->id] : 'N/A' }}</td>
            @else
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400 text-center">-</td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400 text-center">-</td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400 text-center">-</td>
            @endif
            @endforeach

                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ isset($honors[$senat->id]) ? $honors[$senat->id] : 'N/A' }}</td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ isset($pphs[$senat->id]) ? $pphs[$senat->id] : 'N/A' }}</td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ isset($honorariumsPerSenat[$senat->id]) ? $honorariumsPerSenat[$senat->id] : 'N/A' }}</td>

                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->NPWP }}</td>
           </tr>
           @endif
        </tbody>
        </table>
    </div>
@endsection