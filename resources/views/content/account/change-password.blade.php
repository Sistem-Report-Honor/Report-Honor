@extends('dashboard')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 p-10">
        <div id="left" class="bg-[#EBE9EE] rounded-lg p-6 h-fit">
            <h1 class="text-2xl font-bold capitalize">{{ Auth::user()->name }}</h1>
            <div class="w-full h-[2px] bg-[#666] my-4"></div>
            <div class="p-4 space-y-4">
                <a href="{{ route('change.password.form') }}"
                    class="flex items-center justify-between rounded-lg bg-[#6E2BB1] px-4 py-2.5 text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">
                    <span>Ubah Password</span>
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
            <form method="POST" action="{{ route('change.password') }}" class="space-y-6">
                @csrf
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-xs font-semibold text-gray-900">Kata Sandi Saat Ini</label>
                    <input id="current_password" type="password" class="mt-1 w-full max-w-[55vw] form-input rounded-lg" name="current_password" required autofocus />
                    @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- New Password -->
                <div>
                    <label for="new_password" class="block text-xs font-semibold text-gray-900">Kata Sandi Baru</label>
                    <input id="new_password" type="password" class="mt-1 w-full max-w-[55vw] form-input rounded-lg" name="new_password" required />
                    @error('new_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Confirm New Password -->
                <div>
                    <label for="confirm_password" class="block text-xs font-semibold text-gray-900">Konfirmasi Kata Sandi Baru</label>
                    <input id="confirm_password" type="password" class="mt-1 w-full max-w-[55vw] form-input rounded-lg" name="confirm_password" required />
                    @error('confirm_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="bg-[#6E2BB1] px-4 py-2.5 rounded-lg text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
