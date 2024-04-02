@extends('dashboard')

@section('content')
<h1 class="text-3xl font-semibold mb-10">Report Honor</h1>
<div class="flex justify-end">
  <button class="inline-block rounded-md bg-[#6E2BB1] px-4 py-2 text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">Print Report</button>
</div>
<div class="overflow-x-auto">
  <table id="my-datatable" class="text-sm w-full bg-[#EBE9EE] rounded-lg">
    <thead>
      <tr>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 w-10" rowspan="4">No.</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="4">Nama</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="4">GP</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="4">Jabatan dalam Senat</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="4">Komisi</th>
        <!-- Judul Rapat -->
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3">
          <span class="block text-center">Rapat 1</span>
        </th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3">
          <span class="block text-center">Rapat 2</span>
        </th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3">
          <span class="block text-center">Rapat 3</span>
        </th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3">
          <span class="block text-center">Rapat 4</span>
        </th>

        <!-- Total Honor Perbulan -->
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3" rowspan="2">Total Honor Rapat Senat Bulan</th>
        </th>

        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="4">NPWP</th>
      </tr>

          <!-- Honor Perbulan
      <tr>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
          <span class="block text-center">Honor</span>
        </th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
          <span class="block text-center">PPH</span>
        </th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
          <span class="block text-center">Diterima</span>
        </th>
      </tr> -->

      <!-- Tgl Rapat -->
      <tr>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3">
        <span class="block text-center">Tgl Rapat 1</span>
      </th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3">
        <span class="block text-center">Tgl Rapat 2</span>
      </th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3">
        <span class="block text-center">Tgl Rapat 3</span>
      </th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3">
        <span class="block text-center">Tgl Rapat 4</span>
      </th>
    </tr>

    <!-- Honor Per Rapat -->
    <tr>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Honor</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">PPH</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Diterima</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Honor</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">PPH</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Diterima</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Honor</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">PPH</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Diterima</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Honor</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">PPH</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Diterima</th>
    </tr>



    </thead>

    <tbody>
      @foreach ($senats as $index => $senat)
    <tr>
      <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">{{ $index + 1 }}</td>
      <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">{{ $senat->name }}</td>
      <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->id_golongan }}</td>
      <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->jabatan }}</td>
      <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->komisi->komisi }}</td>

      @foreach ($rapats as $rapat)
        @php
            $senat_id = $senat->id;
            $rapat_id = $rapat->id;
            $is_kehadiran = $kehadirans[$senat_id][$rapat_id];
        @endphp
        @if ($is_kehadiran)
            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                {{ $senat->golongan->honor ?? '-' }}
            </td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                {{ $senat->golongan->pph ?? '-' }}
            </td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                {{ isset($honorariumsPerRapat[$rapat->id]) ? $honorariumsPerRapat[$rapat->id] : 'N/A' }}
            </td>
        @else
            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">-</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">-</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">-</td>
        @endif
      @endforeach
    
      <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ isset($honorariums[$senat->id]) ? $honorariums[$senat->id] : 'N/A' }}</td>
      <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->NPWP }}</td>
    </tr>
      @endforeach
  </tbody>
  </table>
</div>
@endsection