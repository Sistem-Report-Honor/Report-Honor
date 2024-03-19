@extends('dashboard')

@section('content')
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
            <thead>
                <tr>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Name</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">NIP</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Username</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Komisi</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Jabatan</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">action</th>
                </tr>
            </thead>
            @foreach ($users as $user)
                @if ($user->hasRole('anggota|pimpinan'))
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $user->name }}</td>
                            @if ($user->senat != null)
                                <!-- Periksa apakah data senat tersedia -->
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $user->senat->nip }}</td>
                                <!-- Anda dapat mengakses kolom-kolom lainnya dari tabel senat seperti ini -->
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $user->username }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $user->senat->komisi->komisi }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $user->senat->jabatan }}</td>
                            @else
                                <!-- Jika data senat tidak tersedia, tampilkan pesan alternatif -->
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">-</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">-</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">-</td>
                            @endif
                            <td class="whitespace-nowrap px-4 py-2">
                                <div class="grid grid-cols-3">
                                    <div>
                                        <a href="#"
                                            class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('edit.user', $user->id) }}"
                                            class="inline-block rounded bg-orange-600 px-4 py-2 text-xs font-medium text-white hover:bg-orange-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                            </svg>
                                        </a>
                                    </div>
                                    <div>
                                        <form id="deleteForm" action="{{ route('delete.user', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete this user?')"
                                                class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                @endif
            @endforeach
        </table>
    </div>
    <script>
        document.getElementById('deleteForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting normally

            // Send an AJAX request to the server
            fetch(this.action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: new FormData(this)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    alert(data.message); // Show success message
                    // You can redirect or do something else here if needed
                    setTimeout(function() {
                        window.location.reload();
                    }, 100);
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    // Handle errors here
                });
        });
    </script>
@endsection
