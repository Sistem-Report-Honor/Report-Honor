<x-app-layout>
    <div class="pt-3">
        <div class="max-w-screen sm:px-6 lg:px-8">
            <div class="sm:grid sm:grid-flow-col sm:grid-cols-5 sm:gap-3">
                <div class="sm:block hidden">
                    @include('layouts.sidebar')
                </div>
                <div class="border sm:col-span-4 sm:min-w-screen ">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
