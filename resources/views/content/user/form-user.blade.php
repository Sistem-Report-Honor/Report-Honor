@extends('dashboard')

@section('content')
<h1 class="text-3xl font-semibold mb-10">Create User</h1>
<div class="w-full h-[1px] bg-[#666]"></div>
<div class="p-6">
    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <form action="{{ route('post.user') }}" method="POST">
        @csrf
        <div class="space-y-6">
            <div>
                <label class="block text-xs font-semibold text-gray-900"> NIP </label>
                <input type="text" placeholder="NIP" name="nip" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" required />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-900"> Nama Lengkap </label>
                <input type="text" placeholder="Nama Lengkap" name="name" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" required />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-900"> Username </label>
                <input type="text" placeholder="Username" name="username" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" required />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-900">Password</label>
                <div class="relative">
                    <input class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" placeholder="Password" type="password" id="password" name="password" required />
                    <button type="button" id="togglePassword" class="absolute inset-y-0 -ml-[50px] mt-1 px-4 py-2 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-900">Golongan</label>
                <div class="relative mt-2 text-sm">
                    @foreach ($golongans as $golongan)
                    <label for="golongan{{ $golongan->id }}" class="inline-flex items-center bg-[#efefef] py-2.5 px-4 rounded-md">
                        <input type="radio" id="golongan{{ $golongan->id }}" name="id_golongan" value="{{ $golongan->id }}">
                        <span class="mx-2">{{ $golongan->golongan }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-900" for="NPWP">NPWP</label>
                <input class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" placeholder="NPWP" type="text" id="NPWP" name="NPWP" required />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-900" for="jabatan">Jabatan Senat</label>
                <input class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" placeholder="Jabatan" type="text" id="jabatan" name="jabatan" required />
            </div>
            <div>
                <label for="id_komisi" class="block text-xs font-semibold text-gray-900">Komisi</label>
                <select id="id_komisi" name="id_komisi" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm py-2 px-2.5" required>
                    <option value="" selected>Pilih</option>
                    @foreach ($komisis as $komisi)
                    @if ($komisi->id !== 4)
                    <option value="{{ $komisi->id }}">{{ $komisi->komisi }}</option>
                    @endif
                    @endforeach

                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-900" for="no_rek">No. Rek</label>
                <input class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" placeholder="No. Rek" type="text" id="no_rek" name="no_rek" required />
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-900" for="nama_rekening">Nama Rekening</label>
                <input class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" placeholder="Nama Rekening" type="text" id="nama_rekening" name="nama_rekening" required />
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-900" for="role">Role</label>
                <select id="role" name="role" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm py-2 px-2.5" required>
                    <option value="" selected>Pilih</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-12">
            <button type="submit" class="inline-block w-full min-w-56 rounded-lg bg-[#6E2BB1] hover:bg-[#8b3ce1] px-5 py-2 font-medium text-white sm:w-auto transition-all">
                {{ __('Save Data') }}
            </button>
        </div>
    </form>
</div>

<!-- Script untuk toggle password -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 1000,
                    didClose: () => {
                        window.location.href = '/user/table';
                    }
                });
            @endif
        });

    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // Ubah ikon untuk menampilkan atau menyembunyikan kata sandi
        this.classList.toggle('text-black');
        this.classList.toggle('text-gray-600');
    });
</script>
@endsection