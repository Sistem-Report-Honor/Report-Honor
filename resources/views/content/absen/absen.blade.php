<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="bg-white min-h-screen">
        @include('layouts.navigation-absensi')
        <main class="p-10">
            <h1 class="text-xl md:text-3xl lg:text-3xl font-semibold mb-6 md:mb-10 lg:mb-10">Form Absensi</h1>
            <div class="w-full h-[1px] bg-[#666]"></div>
            <div class="py-6 lg:p-6">
                @if ($rapat->status == 'mulai')
                    <form action="{{ route('hadir', [$rapat->kode_unik, $id_komisi]) }}" method="POST">
                        @csrf
                        <input type="text" id="id_rapat" name="id_rapat" value="{{ $rapat->id }}" hidden>
                        <div class="space-y-6">
                            <div>
                                <label for="id_senat" class="block text-sm md:text-md lg:text-md font-semibold text-gray-900">Nama</label>
                                <select id="id_senat" name="id_senat"
                                    class="mt-1 w-full lg:max-w-[55vw] rounded-md border border-gray-500 shadow-sm text-sm md:text-md lg:text-md py-2 px-2.5"
                                    required>
                                    <option value="" selected>Pilih</option>
                                    @foreach ($senats as $senat)
                                        <option value="{{ $senat->id }}">{{ $senat->nip }} - {{ $senat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm md:text-md lg:text-md font-semibold text-gray-900"> Jenis Rapat </label>
                                <input type="text" name="Komisi" value="{{ $rapat->komisi->komisi }}" readonly
                                    class="mt-1 w-full lg:max-w-[55vw] rounded-md border border-gray-500 shadow-sm text-sm md:text-md lg:text-md"
                                    required />
                            </div>
                            <div>
                                <label class="block text-sm md:text-md lg:text-md font-semibold text-gray-900">Password</label>
                                <div class="relative">
                                    <input
                                        class="mt-1 w-full lg:max-w-[55vw] rounded-md border border-gray-500 shadow-sm text-sm md:text-md lg:text-md"
                                        placeholder="Password" type="password" id="password" name="password"
                                        required />
                                    <button type="button" id="togglePassword"
                                        class="absolute inset-y-0 -ml-[50px] mt-1 px-4 py-2 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                            <path strokeLinecap="round" strokeLinejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path strokeLinecap="round" strokeLinejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit"
                                class="text-sm md:text-md lg:text-md inline-block w-full min-w-56 rounded-lg bg-[#6E2BB1] hover:bg-[#8b3ce1] px-5 py-2 font-medium text-white sm:w-auto transition-all">
                                {{ __('Submit') }}
                            </button>
                        </div>
                        </div>
                    </form>
                @endif
            </div>

    </div>
    </main>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 2000
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

</body>

</html>
