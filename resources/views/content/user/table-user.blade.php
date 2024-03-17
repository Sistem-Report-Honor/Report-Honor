@extends('dashboard')

@section('content')
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
            <thead >
                <tr>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Name</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">NIP</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Email</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Jabatan</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900"></th>
                </tr>
            </thead>
            @foreach ($users as $user)
                @if ($user->hasRole('anggota|pimpinan'))
                    <tbody class="divide-y divide-gray-200">
                      <tr>
                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $user->name }}</td>
                        @if ($user->senat != NULL) <!-- Periksa apakah data senat tersedia -->
                            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $user->senat->nip }}</td>
                            <!-- Anda dapat mengakses kolom-kolom lainnya dari tabel senat seperti ini -->
                            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $user->email }}</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $user->senat->jabatan }}</td>
                        @else
                            <!-- Jika data senat tidak tersedia, tampilkan pesan alternatif -->
                            <td class="whitespace-nowrap px-4 py-2 text-gray-700" >-</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-700" >-</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-700" >-</td>
                        @endif
                        <td class="whitespace-nowrap px-4 py-2">
                            <a href="#" class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">View</a>
                        </td>
                    </tr>
                    </tbody>
                @endif
            @endforeach
        </table>
    </div>
@endsection
