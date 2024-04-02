@extends('dashboard')

@section('content')
<h1 class="text-3xl font-semibold mb-10">View User</h1>
<div class="w-full h-[1px] bg-[#666]"></div>
<div class="p-6">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('succes')" />

    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <form action="{{route('edit.user.post',$user->id)}}" method="POST">
        @csrf
        <div class="space-y-6">
            <div>
                <label class="block text-xs font-semibold text-gray-900"> NIP </label>
                <input type="text" placeholder="NIP" name="nip" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" value="{{ $user->senat->nip }}" required />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-900"> Nama Lengkap </label>
                <input type="text" placeholder="Nama Lengkap" name="name" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" value="{{ $user->senat->name }}" required />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-900"> Username </label>
                <input type="text" placeholder="Username" name="username" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" value="{{ $user->username }}" required />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-900">Golongan</label>
                <div class="relative mt-2 text-sm">
                    @foreach ($golongans as $golongan)
                    <label for="golongan{{ $golongan->id }}" class="inline-flex items-center bg-[#efefef] py-2.5 px-4 rounded-md">
                        <input type="radio" id="golongan{{ $golongan->id }}" name="id_golongan" value="{{ $golongan->id }}" @if ($user->senat->id_golongan == $golongan->id) checked @endif>
                        <span class="mx-2">{{ $golongan->golongan }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-900" for="NPWP">NPWP</label>
                <input class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" placeholder="NPWP" type="text" id="NPWP" name="NPWP" value="{{ $user->senat->NPWP }}" required />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-900" for="jabatan">Jabatan Senat</label>
                <input class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" placeholder="Jabatan" type="text" id="jabatan" name="jabatan" value="{{ $user->senat->jabatan }}" required />
            </div>
            <div>
                <label for="id_komisi" class="block text-xs font-semibold text-gray-900">Komisi</label>
                <select id="id_komisi" name="id_komisi" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm py-2 px-2.5" required>
                    @foreach ($komisis as $komisi)
                    <option value="{{ $komisi->id }}" @if ($user->senat->id_komisi == $komisi->id) selected @endif>{{ $komisi->komisi }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-900" for="no_rek">No. Rek</label>
                <input class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" placeholder="No. Rek" type="text" id="no_rek" name="no_rek" value="{{ $user->senat->no_rek }}" required />
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-900" for="nama_rekening">Nama Rekening</label>
                <input class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm" placeholder="Nama Rekening" type="text" id="nama_rekening" name="nama_rekening" value="{{ $user->senat->nama_rekening }}" required />
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-900" for="role">Role</label>
                <select id="role" name="role" class="mt-1 w-full max-w-[55vw] rounded-md border border-gray-500 shadow-sm sm:text-sm py-2 px-2.5" required>
                    <option value="" selected>Pilih</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-12">
            <button type="submit" class="inline-block w-full min-w-56 rounded-lg bg-[#6E2BB1] hover:bg-[#8b3ce1] px-5 py-2 font-medium text-white sm:w-auto transition-all">
                {{ __('Update') }}
            </button>
        </div>
    </form>
</div>
@endsection