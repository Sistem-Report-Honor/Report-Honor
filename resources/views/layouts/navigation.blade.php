<nav x-data="{ open: false }" class="bg-[#915CC7] text-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex gap-4">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logo-polmed-png.png') }}" alt="" class="w-10 h-10">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="flex justify-center items-center sm:-my-px sm:flex leading-5">
                    <h1>Senat <br> Politeknik Negeri Medan</h1>
                </div>
            </div>

            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
                <ul
                    class="uppercase flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 md:flex-row md:mt-0 md:border-0 md:bg-transparent">
                    <li>
                        <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownUser"
                            class="flex items-center justify-between w-full py-2 px-3 roundedmd:hover:bg-transparent md:border-0 md:p-0 md:w-auto uppercase">User
                            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg></button>
                        <!-- Dropdown menu -->
                        <div id="dropdownUser"
                            class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                            <ul class="py-2 text-sm text-gray-700 capitalize" aria-labelledby="dropdownLargeButton">
                                <li>
                                    <a href="{{ route('table.user') }}" class="block px-4 py-2 hover:bg-gray-100">User
                                        List</a>
                                </li>
                                <li>
                                    <a href="{{ route('form.user') }}" class="block px-4 py-2 hover:bg-gray-100">User
                                        Create</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownReport"
                            class="flex items-center justify-between w-full py-2 px-3 roundedmd:hover:bg-transparent md:border-0 md:p-0 md:w-auto uppercase">Report
                            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg></button>
                        <!-- Dropdown menu -->
                        <div id="dropdownReport"
                            class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                            <ul class="py-2 text-sm text-gray-700 capitalize" aria-labelledby="dropdownLargeButton">
                                <li>
                                    <a href="{{ route('list.honor.detail') }}"
                                        class="block px-4 py-2 hover:bg-gray-100">Honor Detail</a>
                                </li>
                                <li>
                                    <a href="{{ route('list.honor.dasar') }}"
                                        class="block px-4 py-2 hover:bg-gray-100">Honor Dasar</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownRapat"
                            class="flex items-center justify-between w-full py-2 px-3 roundedmd:hover:bg-transparent md:border-0 md:p-0 md:w-auto uppercase">Rapat
                            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg></button>
                        <!-- Dropdown menu -->
                        <div id="dropdownRapat"
                            class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                            <ul class="py-2 text-sm text-gray-700 capitalize" aria-labelledby="dropdownLargeButton">
                                <li>
                                    <a href="{{ route('list.rapat') }}" class="block px-4 py-2 hover:bg-gray-100">Rapat
                                        List</a>
                                </li>
                                <li>
                                    <a href="{{ route('form.rapat') }}" class="block px-4 py-2 hover:bg-gray-100">Rapat
                                        Create</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="hidden md:flex lg:flex items-center md:order-2 space-x-3 md:space-x-0">
                <button type="button"
                    class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300"
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                    data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-10 h-10 rounded-full" src="https://placehold.co/400" alt="user photo">
                </button>
                <!-- Dropdown menu -->
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow min-w-52"
                    id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900">{{ Auth::user()->name }}</span>
                        <span class="block text-sm  text-gray-500 truncate">{{ Auth::user()->username }}</span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="{{ route('account.detail') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                        </li>
                        <li>
                            <a href="{{ route('change.password') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Change Password</a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 bg-gray-100 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">

        <ul>
            <li>
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
            </li>
            <li>
                <details class="group [&_summary::-webkit-details-marker]:hidden">
                    <summary mary class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2">
                        <span class="text-sm font-medium"> {{ __('User') }} </span>

                        <span class="shrink-0 transition duration-300 group-open:-rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </summary>

                    <ul class="mt-2 space-y-1 px-4">
                        <x-responsive-nav-link :href="route('table.user')" :active="request()->routeIs('table.user')">
                            {{ __('User List') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('form.user')" :active="request()->routeIs('form.user')">
                            {{ __('User Create') }}
                        </x-responsive-nav-link>
                    </ul>
                </details>
            </li>
        </ul>
    </div>
</nav>
