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
                <label class="sr-only" for="email">Email</label>
                <input class="w-full rounded-lg border-gray-200 p-3 text-sm" placeholder="Email address" type="email"
                    id="email" name="email" required />
            </div>

            <div>
                <label class="sr-only" for="password">password</label>
                <input class="w-full rounded-lg border-gray-200 p-3 text-sm" placeholder="password" type="password"
                    id="password" name="password" required />
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
                <label class="sr-only">Golongan</label>
                @foreach ($golongans as $golongan)
                    <label for="golongan{{ $golongan->id }}" class="inline-flex items-center">
                        <input type="radio" id="golongan{{ $golongan->id }}" name="id_golongan" value="{{ $golongan->id }}">
                        <span class="ml-2">{{ $golongan->golongan }}</span>
                    </label>
                @endforeach
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
@endsection
