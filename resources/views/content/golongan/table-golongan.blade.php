@extends('dashboard')

@section('content')
    <div class="p-10">
        <h1 class="text-3xl font-semibold mb-10">Data Golongan</h1>
        <div class="overflow-x-auto">
            <table id="my-datatable" class="text-sm w-full bg-[#EBE9EE] rounded-lg">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 w-10">No</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Golongan</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Honor</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">PPH</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($golongans as $golongan)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">{{ $loop->iteration }}</td>
                            <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">{{ $golongan->golongan }}</td>
                            <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">{{ $golongan->honor }}</td>
                            <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 border-b border-gray-400">{{ $golongan->pph }}</td>
                            <td class="whitespace-nowrap px-4 py-2 flex gap-2 border-b border-gray-400">
                                <a href="{{ route('golongan.edit', $golongan->id) }}"
                                    class="inline-block rounded bg-[#6E2BB1] px-4 py-2 text-xs font-medium text-white hover:bg-[#8b3ce1] transition-all">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                text: 'Apakah Anda ingin menghapus data?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus Data!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form jika dikonfirmasi
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }
    </script>
@endsection
