@extends('dashboard')

@section('content')
    <h1 class="text-3xl font-semibold mb-10">Report Honor</h1>
    <div class="flex justify-end mb-4">
        <button class="inline-block rounded-md bg-[#6E2BB1] px-4 py-2 text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">Print Report</button>
    </div>
    <div class="overflow-x-auto">
        <table id="my-datatable" class="text-sm w-full bg-[#EBE9EE] rounded-lg">
            <thead>
                <tr>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 w-10" rowspan="2">No.</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">Nama</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">GP</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">Jabatan dalam Senat</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">Komisi</th>

                    @foreach ($rapats as $rapat)
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3">
                            <span class="block text-center">{{ $rapat->komisi->komisi }}</span>
                            <span class="block text-center font-thin">{{ date('d M Y', strtotime($rapat->tanggal)) }}</span>
                        </th>
                    @endforeach

                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">Total Honor Rapat Senat Bulan</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">NPWP</th>
                </tr>

                <tr>
                    @foreach ($rapats as $rapat)
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Honor</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">PPH</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Diterima</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach ($senats as $index => $senat)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">{{ $index + 1 }}</td>
                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">{{ $senat->name }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->golongan->golongan }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->jabatan }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->komisi->komisi }}</td>

                        @foreach ($rapats as $rapat)
                            @php
                                $senat_id = $senat->id;
                                $rapat_id = $rapat->id;
                                $is_kehadiran = isset($kehadirans[$senat_id][$rapat_id]) ? $kehadirans[$senat_id][$rapat_id] : false;
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

                        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ isset($honorariumsPerSenat[$senat->id]) ? $honorariumsPerSenat[$senat->id] : 'N/A' }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->NPWP }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
