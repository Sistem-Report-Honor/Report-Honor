@extends('dashboard')

@section('content')
<div class="p-10">
    <h1 class="text-3xl font-semibold mb-10">Edit Golongan</h1>
    <div class="w-full h-[1px] bg-[#666]"></div>
    <div class="p-6">
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form id="editGolongan" action="{{ route('golongan.update', $golongan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-900">Golongan</label>
                    <input type="text" placeholder="Golongan" name="golongan" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" value="{{ $golongan->golongan }}" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-900">Honor</label>
                    <input type="text" placeholder="Honor" name="honor" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" value="{{ $golongan->honor }}" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-900">PPH</label>
                    <input type="text" placeholder="PPH" name="pph" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" value="{{ $golongan->pph }}" required />
                </div>
            </div>
            <div class="mt-12">
                <button type="submit" class="inline-block w-full min-w-56 rounded-lg bg-[#6E2BB1] hover:bg-[#8b3ce1] px-5 py-2 font-medium text-white sm:w-auto transition-all">
                    {{ __('Update') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    // Ambil elemen form
    const form = document.getElementById('editGolongan');

    // Tambahkan event listener untuk mengirimkan form
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Mencegah pengiriman form default

        // Kirim form menggunakan AJAX
        fetch(this.action, {
            method: this.method,
            body: new FormData(this),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Parsing respon ke JSON
        })
        .then(data => {
            console.log(data); // Log data untuk debugging
            if (data.success) {
                // Tampilkan SweetAlert ketika data berhasil diubah
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data golongan berhasil diubah.',
                }).then(() => {
                    // Redirect ke halaman lain jika perlu
                    window.location.href = '/golongan';
                });
            } else {
                // Tampilkan pesan error jika ada kesalahan
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Tampilkan pesan error jika ada kesalahan parsing JSON
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat memproses data.',
            });
        });
    });
</script>
@endsection