@extends('dashboard')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div id="left" class="bg-[#EBE9EE] rounded-lg p-6 h-fit">
            <h1 class="text-2xl font-bold capitalize">{{ Auth::user()->name }}</h1>
            <div class="w-full h-[2px] bg-[#666] my-4"></div>
            <div class="p-4 space-y-4">
                <a href=""
                    class="flex items-center justify-between rounded-lg bg-[#6E2BB1] px-4 py-2.5 text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">
                    <span>Ubah Password</span>
                    <i class='bx bxs-chevron-right text-[1rem]'></i>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')" class="flex items-center justify-between rounded-lg bg-[#c23c44] px-4 py-2 text-xs font-semibold text-white hover:bg-[#d75c5d] transition-all"
                        onclick="event.preventDefault();
                    this.closest('form').submit();">
                        {{ __('Log Out') }}
                        <i class='bx bx-log-out text-[1rem]' ></i>
                    </x-dropdown-link>
                </form>
            </div>
        </div>
        <div id="right" class="bg-[#EBE9EE] col-span-2 rounded-lg p-6">
            <h1 class="text-2xl font-bold capitalize">Informasi Pribadi</h1>
            <div class="w-full h-[2px] bg-[#666] my-4"></div>
            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-900"> Nama Lengkap </label>
                    <input type="text" placeholder="Nama Lengkap" name="name"
                        class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm"
                        value="{{ Auth::user()->name }}" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-900"> NIP </label>
                    <input type="text" placeholder="NIP" name="nip"
                        class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-900"> Username </label>
                    <input type="text" placeholder="Username" name="username"
                        class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm"
                        value="{{ Auth::user()->username }}" required />
                </div>
            </div>
        </div>
    </div>
@endsection
