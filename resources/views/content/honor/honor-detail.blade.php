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
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 w-10" rowspan="3">No.</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="3">Nama</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="3">GP</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="3">Jabatan dalam Senat</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="3">Komisi</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="8">
          <span class="block text-center">Total Honor Rapat Bulan March</span>
        </th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="3">Diterima</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="3">NPWP</th>
      </tr>
      <tr>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="2">
        <span class="block text-center">Pleno</span>
      </th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="2">
        <span class="block text-center">Akademik</span>
      </th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="2">
        <span class="block text-center">Etika</span>
      </th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="2">
        <span class="block text-center">Kerjasama</span>
      </th>
    </tr>
    <tr>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Honor</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">PPH</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Honor</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">PPH</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Honor</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">PPH</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Honor</th>
      <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">PPH</th>
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
        <!-- rapat pleno -->
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->golongan->honor }}</td>
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->golongan->pph }}</td>
        <!-- rapat akademik -->
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->golongan->honor }}</td>
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->golongan->pph }}</td>
        <!-- rapat etika -->
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->golongan->honor }}</td>
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->golongan->pph }}</td>
        <!-- rapat Kerjasama -->
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->golongan->honor }}</td>
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->golongan->pph }}</td>

        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ isset($honorariums[$senat->id]) ? $honorariums[$senat->id] : 'N/A' }}</td>
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $senat->NPWP }}</td>
    </tr>
    @endforeach
  </tbody>
  </table>
</div>
@endsection