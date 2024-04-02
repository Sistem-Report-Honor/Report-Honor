<x-guest-layout>
    <div class="bg-white">
        <div class="flex justify-center h-screen">
            <div class="hidden bg-cover lg:block lg:w-3/5" style="background-image: url(images/bg-login.png)">
                <div class="flex items-center h-full px-20 bg-black bg-opacity-60">
                    <div class="font-bold text-white text-[42px]">
                        <h2 class="">SENAT</h2>

                        <p class="max-w-xl">
                            Politeknik Negeri Medan
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center w-full max-w-md px-6 mx-auto lg:w-2/6">
                <div class="flex-1">
                    <div class="leading-8">
                        <!-- <div class="flex justify-center mx-auto">
                            <img class="w-auto h-7 sm:h-8" src="https://merakiui.com/images/logo.svg" alt="">
                        </div> -->
                        <h1 class="font-bold text-[32px]">Masuk</h1>
                        <p class="text-black font-medium">Masuk untuk melanjutkan</p>
                    </div>

                    <div class="mt-8">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div>
                                <label for="username" class="mb-2 text-sm text-black font-semibold">Username</label>
                                <input type="text" name="username" id="username" placeholder="Masukan username" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-[#E6E6E6] border border-gray-200 rounded-lg focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                            </div>

                            <div class="mt-4">
                                <label for="password" class="text-sm text-black font-semibold">Password</label>
                                <input type="password" name="password" id="password" placeholder="Masukan password" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-[#E6E6E6] border border-gray-200 rounded-lg focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                            </div>
                            <!-- Validation Errors -->
                            <p class="mt-6 text-sm text-center text-gray-400">
                                <x-auth-session-status class="mb-4" :status="session('status')" />
                                <x-auth-validation-errors class="mb-4" :errors="$errors" />
                            </p>
                            <div class="mt-20 md:mt-28 lg:mt-36">
                                <button type="submit" class="w-full px-4 py-2 tracking-wide text-white font-bold transition-colors duration-300 transform bg-[#6E2BB1] rounded-lg hover:bg-[#8b3ce1]">
                                    Masuk
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>