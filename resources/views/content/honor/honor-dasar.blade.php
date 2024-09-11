@extends('dashboard')

@section('content')
    <div class="p-10">
        <h1 class="text-3xl font-semibold mb-10">Laporan Honor Dasar</h1>
        <div class="flex justify-between mb-4">
            <a href="{{ route('print.honor.dasar', ['month' => $month, 'year' => $year]) }}"
                class="inline-block rounded-md bg-purple-600 px-4 py-2 text-xs font-semibold text-white hover:bg-purple-800 transition-all">Print Laporan</a>
            <form action="{{ route('list.honor.dasar') }}" method="GET" class="flex gap-2 items-center">
                <div>
                    <label for="month" class="mr-2">Pilih Bulan:</label>
                    <select name="month" id="month" class="border rounded-md px-2 py-1">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="year" class="ml-4 mr-2">Pilih Tahun:</label>
                    <select name="year" id="year" class="border rounded-md px-2 py-1">
                        @foreach(range(date('Y') - 5, date('Y')) as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="inline-block rounded-md bg-blue-500 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition-all">Filter</button>
            </form>            
        </div>
        <div class="overflow-x-auto">
            <table id="my-datatable" class="text-sm w-full bg-gray-100 rounded-lg">
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
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                                {{ $index + 1 }}</td>
                            <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                                {{ $senat->no_rek }}</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-700 border-b border-gray-400">
                                {{ $senat->nama_rekening }}</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-700 border-b border-gray-400">
                                {{ isset($honorariums[$senat->id]) ? $honorariums[$senat->id] : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

