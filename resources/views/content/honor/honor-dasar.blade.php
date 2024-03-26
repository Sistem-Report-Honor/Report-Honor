@extends('dashboard')

@section('content')
    <h1 class="text-3xl font-semibold mb-10">Report Honor Dasar</h1>
    <div class="flex justify-end">
        <button
            class="inline-block rounded-md bg-[#6E2BB1] px-4 py-2 text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">Print
            Report</button>
    </div>
    <div class="overflow-x-auto">
        <table id="my-datatable" class="text-sm w-full bg-[#EBE9EE] rounded-lg">
            <thead>
                <tr>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 w-10">
                        <span class="block text-left">No.</span>
                    </th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                        <span class="block text-left">Nomor Rekening</span>
                    </th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Nama Rekening</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                        <span class="block text-left">Honorium</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                      <span class="block text-left">1</span>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                      <span class="block text-left">123456789</span>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">Senat 1</td>
                    <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                      <span class="block text-left">850000</span>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>
@endsection
