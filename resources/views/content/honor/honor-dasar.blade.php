@extends('dashboard')

@section('content')
    <h1 class="text-3xl font-semibold mb-10">Report Honor Dasar</h1>
    <div class="flex justify-end">
        <a href="{{ route('print.honor.dasar') }}" class="inline-block rounded-md bg-[#6E2BB1] px-4 py-2 text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">Print Report</a>
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
            @foreach ($senats as $index => $senat)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $index + 1 }}</td>
                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">{{ $senat->no_rek }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-gray-700 border-b border-gray-400">{{ $senat->nama_rekening }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-gray-700 border-b border-gray-400">{{ isset($honorariums[$senat->id]) ? $honorariums[$senat->id] : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
