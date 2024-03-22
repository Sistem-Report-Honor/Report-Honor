@extends('dashboard')

@section('content')
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
            <tr>
                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">{{ $counter++ }}</td>
                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">{{ $user->name }}</td>
                @if ($user->senat != null)
                <!-- Periksa apakah data senat tersedia -->
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $user->senat->nip }}</td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $user->senat->jabatan }}</td>
                <!-- Anda dapat mengakses kolom-kolom lainnya dari tabel senat seperti ini -->
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">{{ $user->senat->golongan->golongan }}</td>
                </td>

                @else
                <!-- Jika data senat tidak tersedia, tampilkan pesan alternatif -->
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">-</td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">-</td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-900 border-b border-gray-400">-</td>
                @endif
                <td class="whitespace-nowrap px-4 py-2 flex gap-2 border-b border-gray-400">
                    <a href="{{ route('edit.user', $user->id) }}" class="inline-block rounded bg-[#6E2BB1] px-4 py-2 text-xs font-medium text-white hover:bg-[#8b3ce1] transition-all">
                        View
                    </a>
                    <form id="deleteForm" action="{{ route('delete.user', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')" class="inline-block rounded bg-[#c23c44] px-4 py-2 text-xs font-medium text-white hover:bg-[#d75c5d] transition-all">
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