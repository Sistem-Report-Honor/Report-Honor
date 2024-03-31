@extends('dashboard')

@section('content')
    <h1 class="text-3xl font-semibold mb-10">Data User</h1>
    <div class="overflow-x-auto">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <table id="my-datatable" class="text-sm w-full bg-[#EBE9EE] rounded-lg">
            <thead>
                <tr>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 w-10">No</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Name</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                        <span class="block text-left">NIP</span>
                    </th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Username</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Komisi</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Jabatan</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Action</th>
                </tr>
            </thead>
            @php
                $counter = 1;
            @endphp
            @foreach ($users as $user)
                @if ($user->hasRole('anggota|pimpinan'))
                    <tr>
                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                            <span class="block text-left">{{ $counter++ }}</span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">
                            {{ $user->name }}</td>
                        @if ($user->senat != null)
                            <!-- Periksa apakah data senat tersedia -->
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                                <span class="block text-left">{{ $user->senat->nip }}</span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                                {{ $user->username }}</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                                {{ $user->senat->jabatan }}</td>
                            <!-- Anda dapat mengakses kolom-kolom lainnya dari tabel senat seperti ini -->
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">
                                {{ $user->senat->golongan->golongan }}</td>
                            </td>
                        @else
                            <!-- Jika data senat tidak tersedia, tampilkan pesan alternatif -->
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">-</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">-</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">-</td>
                        @endif
                        <td class="whitespace-nowrap px-4 py-2 flex gap-2 border-b border-gray-400">
                            <a href="{{ route('edit.user', $user->id) }}"
                                class="inline-block rounded bg-[#6E2BB1] px-4 py-2 text-xs font-medium text-white hover:bg-[#8b3ce1] transition-all">
                                View
                            </a>
                            <form action="{{ route('delete.user', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this user?')"
                                    class="inline-block rounded bg-[#c23c44] px-4 py-2 text-xs font-medium text-white hover:bg-[#d75c5d] transition-all">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>

        </table>
    </div>
@endsection
