@extends('dashboard')

@section('content')
<div class="p-10">
    <h1 class="text-3xl font-semibold mb-10">Laporan Kehadiran Senat</h1>

    <div class="flex justify-end mb-4">
        <form action="{{ route('kehadiran.index') }}" method="GET">
            <label for="bulan" class="mr-2">Pilih Bulan:</label>
            <select name="bulan" id="bulan" class="border rounded-md px-2 py-1">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                @endfor
            </select>

            <label for="tahun" class="mr-2 ml-4">Pilih Tahun:</label>
            <select name="tahun" id="tahun" class="border rounded-md px-2 py-1">
                @for ($i = date('Y'); $i >= 2010; $i--)
                    <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>

            <label for="id_komisi" class="mr-2 ml-4">Nama Rapat (Komisi):</label>
            <select name="id_komisi" id="id_komisi" class="border rounded-md px-2 py-1">
                <option value="">Semua Rapat</option>
                @foreach ($komisi as $k)
                    <option value="{{ $k->id }}" {{ $idKomisi == $k->id ? 'selected' : '' }}>{{ $k->komisi }}</option>
                @endforeach
            </select>

            <button type="submit" class="inline-block rounded-md bg-[#6E2BB1] px-4 py-2 text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">Filter</button>
        </form>
    </div>

    <form action="{{ route('kehadiran.export') }}" method="GET">
        <input type="hidden" name="bulan" value="{{ $bulan }}">
        <input type="hidden" name="tahun" value="{{ $tahun }}">
        <input type="hidden" name="id_komisi" value="{{ $idKomisi }}">
        <button type="submit" class="inline-block rounded-md bg-[#6E2BB1] px-4 py-2 text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">Print Report</button>
    </form>

    <div class="overflow-x-auto mt-6">
        <table id="my-datatable" class="text-sm bg-[#EBE9EE] rounded-lg">
            <thead>
                <tr>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Nama Senat</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Status Kehadiran</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Nama Rapat</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Tanggal Rapat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kehadiran as $item)
                <tr>
                    <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $item->senat->name }}</td>
                    <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $item->verifikasi }}</td>
                    <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $item->rapat->komisi->komisi }}</td>
                    <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ \Carbon\Carbon::parse($item->rapat->tanggal)->format('d-m-Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
