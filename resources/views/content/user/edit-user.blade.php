@extends('dashboard')

@section('content')
<div class="p-10">
    <h1 class="text-3xl font-semibold mb-10">Detail User</h1>
    <div class="w-full h-[1px] bg-[#666]"></div>
    <div class="p-6">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('success')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form id="editUser" action="{{ route('edit.user.post', $user->id) }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-900">NIP</label>
                    <input type="text" placeholder="NIP" name="nip" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" value="{{ $user->senat->nip }}" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-900">Nama Lengkap</label>
                    <input type="text" placeholder="Nama Lengkap" name="name" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" value="{{ $user->senat->name }}" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-900">Username</label>
                    <input type="text" placeholder="Username" name="username" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" value="{{ $user->username }}" required />
                </div>
                <div class="relative">
                    <label class="block text-xs font-semibold text-gray-900">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" />
                    <span class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer" onclick="togglePasswordVisibility('password', 'togglePasswordIcon')">
                        <i id="togglePasswordIcon" class="far fa-eye text-gray-500"></i>
                    </span>
                </div>

                <!-- Confirm Password Field -->
                <div class="relative">
                    <label class="block text-xs font-semibold text-gray-900">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" />
                    <span class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer" onclick="togglePasswordVisibility('password_confirmation', 'toggleConfirmPasswordIcon')">
                        <i id="toggleConfirmPasswordIcon" class="far fa-eye text-gray-500"></i>
                    </span>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-900">Golongan</label>
                    <div class="relative mt-2 text-sm">
                        @foreach ($golongan as $item)
                        <label for="golongan{{ $item->id }}" class="inline-flex items-center bg-[#efefef] py-2.5 px-4 rounded-md">
                            <input type="radio" id="golongan{{ $item->id }}" name="id_golongan" value="{{ $item->id }}" @if ($user->senat->id_golongan == $item->id) checked @endif>
                            <span class="mx-2">{{ $item->golongan }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-900 mt-2" for="NPWP">NPWP</label>
                    <input class="mt-2 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" placeholder="NPWP" type="text" id="NPWP" name="NPWP" value="{{ $user->senat->NPWP }}" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-900 mt-2" for="jabatan">Jabatan Senat</label>
                    <input class="mt-2 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" placeholder="Jabatan" type="text" id="jabatan" name="jabatan" value="{{ $user->senat->jabatan }}" required />
                </div>
                <div>
                    <label for="id_komisi" class="block text-xs font-semibold text-gray-900 mt-2">Komisi</label>
                    <select id="id_komisi" name="id_komisi" class="mt-2 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm py-2 px-2.5" required>
                        @foreach ($komisi as $item)
                        @if ($item->id !== 4)
                        <option value="{{ $item->id }}" @if ($user->senat->id_komisi == $item->id) selected @endif>{{ $item->komisi }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-900 mt-2" for="no_rek">No. Rek</label>
                    <input class="mt-2 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" placeholder="No. Rek" type="text" id="no_rek" name="no_rek" value="{{ $user->senat->no_rek }}" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-900 mt-2" for="nama_rekening">Nama Rekening</label>
                    <input class="mt-2 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" placeholder="Nama Rekening" type="text" id="nama_rekening" name="nama_rekening" value="{{ $user->senat->nama_rekening }}" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-900 mt-2" for="role">Role</label>
                    <select id="role" name="role" class="mt-2 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm py-2 px-2.5" required>
                        @foreach ($role as $item)
                        <option value="{{ $item->name }}" @if ($user->getRoleNames()->first() == $item->name) selected @endif>{{ $item->name }}</option>
                        @endforeach
                    </select>
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
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
    // Ambil elemen form
    const form = document.getElementById('editUser');

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
                    text: 'Data User berhasil diubah.',
                }).then(() => {
                    // Redirect ke halaman lain jika perlu
                    window.location.href = '/user/table';
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

    function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
    </script>
@endsection
