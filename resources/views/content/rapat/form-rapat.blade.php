@extends('dashboard')

@section('content')
    <h1 class="text-3xl font-semibold mb-10">Create Rapat</h1>
    <div class="w-full h-[1px] bg-[#666]"></div>
    <div class="p-6">
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        <form action="{{ route('create.rapat') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-900"> Tanggal </label>
                    <input type="date" name="tanggal"
                        class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-900"> Jam </label>
                    <input type="time" name="jam"
                        class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" required />
                </div>
                <div>
                    <label for="id_komisi" class="block text-xs font-semibold text-gray-900">Komisi</label>
                    <select id="id_komisi" name="id_komisi"
                        class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm py-2 px-2.5"
                        required>
                        <option value="" selected>Pilih</option>
                        @foreach ($komisis as $komisi)
                            <option value="{{ $komisi->id }}">{{ $komisi->komisi }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-12">
                <button type="submit"
                    class="inline-block w-full min-w-56 rounded-lg bg-[#6E2BB1] hover:bg-[#8b3ce1] px-5 py-2 font-medium text-white sm:w-auto transition-all">
                    {{ __('Submit') }}
                </button>
            </div>
        </form>
    </div>
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
                        window.location.href = '/rapat';
                    }
                });
            @endif
        });
    </script>
@endsection
