<x-app-layout>
    <div class="pt-3">
        <div class="max-w-screen sm:px-6 lg:px-8">
            <div class="grid grid-flow-col grid-cols-5 gap-3">
                @include('layouts.sidebar')
                <div class="border col-span-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
