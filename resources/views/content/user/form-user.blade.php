@extends('dashboard')

@section('content')
    <div class="rounded-lg bg-white p-8 shadow-lg lg:col-span-3 lg:p-12">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('succes')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        
        <form action="{{ route('post.user') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="sr-only" for="name">Name</label>
                <input class="w-full rounded-lg border-gray-200 p-3 text-sm" placeholder="Name" type="text"
                    id="name" name="name" required />
            </div>

            <div>
                <label class="sr-only" for="username">Username</label>
                <input class="w-full rounded-lg border-gray-200 p-3 text-sm" placeholder="Username" type="text"
                    id="username" name="username" required />
            </div>

            <div>
                <label class="sr-only" for="password">Password</label>
                <div class="relative">
                    <input class="w-full rounded-lg border-gray-200 p-3 text-sm pr-10" placeholder="Password" type="password"
                    id="password" name="password" required />
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 px-4 py-2 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" className="w-6 h-6">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </button>
                </div>
            </div>

            <div>
                <label class="sr-only" for="nip">NIP</label>
                <input class="w-full rounded-lg border-gray-200 p-3 text-sm" placeholder="NIP" type="text" id="nip"
                    name="nip" required />
            </div>

            <div>
                <label class="sr-only" for="no_rek">No. Rek</label>
                <input class="w-full rounded-lg border-gray-200 p-3 text-sm" placeholder="No. Rek" type="text"
                    id="no_rek" name="no_rek" required />
            </div>

            <div>
                <label class="sr-only" for="nama_rekening">Nama Rekening</label>
                <input class="w-full rounded-lg border-gray-200 p-3 text-sm" placeholder="Nama Rekening" type="text"
                    id="nama_rekening" name="nama_rekening" required />
            </div>

            <div>
                <label class="sr-only" for="NPWP">NPWP</label>
                <input class="w-full rounded-lg border-gray-200 p-3 text-sm" placeholder="No NPWP" type="text"
                    id="NPWP" name="NPWP" required />
            </div>

            <div>
                <label class="sr-only">Golongan</label>
                @foreach ($golongans as $golongan)
                    <label for="golongan{{ $golongan->id }}" class="inline-flex items-center">
                        <input type="radio" id="golongan{{ $golongan->id }}" name="id_golongan" value="{{ $golongan->id }}">
                        <span class="ml-2">{{ $golongan->golongan }}</span>
                    </label>
                @endforeach
            </div>

            <div>
                <label for="id_komisi" class="komisi">Komisi</label>
                <select id="id_komisi" name="id_komisi" class="w-full rounded-lg border-gray-200 p-3 text-sm">
                    @foreach ($komisis as $komisi)
                        <option value="{{ $komisi->id }}">{{ $komisi->komisi }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="sr-only" for="jabatan">Jabatan</label>
                <input class="w-full rounded-lg border-gray-200 p-3 text-sm" placeholder="Jabatan" type="text"
                    id="jabatan" name="jabatan" required />
            </div>

            <div>
                <label class="sr-only" for="role">Role</label>
                <select id="role" name="role" class="w-full rounded-lg border-gray-200 p-3 text-sm">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>


            <div class="mt-4">
                <button type="submit"
                    class="inline-block w-full rounded-lg bg-black px-5 py-3 font-medium text-white sm:w-auto">
                    {{ __('Create') }}
                </button>
            </div>
        </form>
    </div>

        <!-- Script untuk toggle password -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // Ubah ikon untuk menampilkan atau menyembunyikan kata sandi
            this.classList.toggle('text-black');
            this.classList.toggle('text-gray-600');
        });
    </script>
@endsection
