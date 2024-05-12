@extends('dashboard')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 p-10">
        <div id="left" class="bg-[#EBE9EE] rounded-lg p-6 h-fit">
            <div class="w-full h-[2px] bg-[#666] my-4"></div>
            <div class="p-4 space-y-4">
                <a href="{{ route('account.detail') }}"
                    class="flex items-center justify-between rounded-lg bg-[#6E2BB1] px-4 py-2.5 text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">
                    <span>Profile</span>
                    <i class='bx bxs-chevron-right text-[1rem]'></i>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                        class="flex items-center justify-between rounded-lg bg-[#c23c44] px-4 py-2 text-xs font-semibold text-white hover:bg-[#d75c5d] transition-all"
                        onclick="event.preventDefault();
                    this.closest('form').submit();">
                        {{ __('Log Out') }}
                        <i class='bx bx-log-out text-[1rem]'></i>
                    </x-dropdown-link>
                </form>
            </div>
        </div>
        <div id="right" class="bg-[#EBE9EE] col-span-2 rounded-lg p-6">
            <h1 class="text-2xl font-bold capitalize">Ubah Kata Sandi</h1>
            <div class="w-full h-[2px] bg-[#666] my-4"></div>
            <div class="p-6 space-y-6">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form id="changePasswordForm" method="POST" action="{{ route('change.password') }}">
                @csrf

                    <div class="form-group row">
                        <label for="current_password"
                            class="col-md-4 col-form-label text-md-right">{{ __('Kata Sandi Saat Ini') }}</label>

                            <div class="col-md-6 relative">
                                    <input id="current_password" type="password" class="mt-1 w-full lg:max-w-[35vw] rounded-md border border-gray-500 shadow-sm text-sm md:text-md lg:text-md form-control @error('current_password') is-invalid @enderror" name="current_password" required autocomplete="current-password">

                                    <button type="button" id="toggleCurrentPassword" class="absolute inset-y-0 -ml-[50px] mt-1 px-4 py-2 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    </button>

                                    @error('current_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                    <div>
                        <label for="new_password"
                            class="col-md-4 col-form-label text-md-right">{{ __('Kata Sandi Baru') }}</label>

                                <div class="col-md-6 relative">
                                    <input id="new_password" type="password" class="mt-1 w-full lg:max-w-[35vw] rounded-md border border-gray-500 shadow-sm text-sm md:text-md lg:text-md form-control @error('new_password') is-invalid @enderror" name="new_password" required autocomplete="new-password">
                                    <button type="button" id="toggleNewPassword" class="absolute inset-y-0 -ml-[50px] mt-1 px-4 py-2 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    </button>

                            @error('new_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="confirm_password"
                            class="col-md-4 col-form-label text-md-right">{{ __('Konfirmasi Kata Sandi Baru') }}</label>

                                <div class="col-md-6 relative">
                                    <input id="confirm_password" type="password" class="mt-1 w-full lg:max-w-[35vw] rounded-md border border-gray-500 shadow-sm text-sm md:text-md lg:text-md form-control @error('confirm_password') is-invalid @enderror" name="confirm_password" required autocomplete="new-password">

                                    
                                    <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 -ml-[50px] mt-1 px-4 py-2 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    </button>

                                    @error('confirm_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                    <div class="form-group row mb-0 mt-5">
                            <button type="submit"
                                class="text-sm md:text-md lg:text-md inline-block w-full min-w-56 rounded-lg bg-[#6E2BB1] hover:bg-[#8b3ce1] px-5 py-2 font-medium text-white sm:w-auto transition-all">
                                {{ __('Simpan') }}
                            </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
// Ambil elemen form
const form = document.getElementById('changePasswordForm');

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
        return response.text(); // Mengembalikan teks dari respons
    })
    .then(data => {
        try {
            data = JSON.parse(data); // Mencoba untuk parse data sebagai JSON
            if (data.success) {
                // Tampilkan SweetAlert ketika kata sandi berhasil diubah
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message,
                }).then(() => {
                    // Redirect ke halaman lain jika perlu
                    window.location.href = '{{ route("account.detail") }}';
                });
            } else {
                // Tampilkan pesan error jika ada kesalahan
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message,
                });
            }
        } catch (error) {
            console.error('Error:', error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
const currentPassword = document.getElementById('current_password');
toggleCurrentPassword.addEventListener('click', function() {
    const type = currentPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    currentPassword.setAttribute('type', type);
    // Ubah ikon untuk menampilkan atau menyembunyikan kata sandi
    this.classList.toggle('text-black');
    this.classList.toggle('text-gray-600');
});

const toggleNewPassword = document.getElementById('toggleNewPassword');
const newPassword = document.getElementById('new_password');
toggleNewPassword.addEventListener('click', function() {
    const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    newPassword.setAttribute('type', type);
    // Ubah ikon untuk menampilkan atau menyembunyikan kata sandi
    this.classList.toggle('text-black');
    this.classList.toggle('text-gray-600');
});

const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
const confirmPassword = document.getElementById('confirm_password');
toggleConfirmPassword.addEventListener('click', function() {
    const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPassword.setAttribute('type', type);
    // Ubah ikon untuk menampilkan atau menyembunyikan kata sandi
    this.classList.toggle('text-black');
    this.classList.toggle('text-gray-600');
});

</script>

@endsection
