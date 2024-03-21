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
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 w-10" rowspan="2">No.</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">Nama</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">GP</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">Jabatan dalam Senat</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" colspan="3">
          <span class="block text-center">Total Honor Rapat Bulan Januari</span>
        </th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">Diterima</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">NPWP</th>
        <!-- <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900" rowspan="2">Action</th> -->
      </tr>
      <tr>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Komisi</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Honor</th>
        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">PPH</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">1</td>
        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">Senat 1</td>
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">III</td>
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">Ketua Senat</td>
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">Akademik</td>
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">894,736</td>
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">44,736</td>
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">850,000</td>
        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">123456789</td>
        <!-- <td class="whitespace-nowrap px-4 py-2 flex gap-2 border-b border-gray-400">
          <a href="#" class="inline-block rounded bg-[#6E2BB1] px-4 py-2 text-xs font-medium text-white hover:bg-[#8b3ce1] transition-all">
            View
          </a>
          <form id="deleteForm" action="" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')" class="inline-block rounded bg-[#c23c44] px-4 py-2 text-xs font-medium text-white hover:bg-[#d75c5d] transition-all">
              Delete
            </button>
          </form>
        </td> -->
      </tr>
    </tbody>

  </table>
</div>
@endsection