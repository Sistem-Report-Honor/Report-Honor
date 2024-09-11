@extends('dashboard')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-10 p-10">
    <div id="left" class="bg-[#EBE9EE] rounded-lg p-6 h-fit">
        <!-- Sidebar Content -->
    </div>
    <div id="right" class="bg-[#EBE9EE] col-span-2 rounded-lg p-6">
        <h1 class="text-2xl font-bold capitalize">Ubah Kata Sandi</h1>
        <div class="w-full h-[2px] bg-[#666] my-4"></div>
        <form id="passwordForm" method="POST" action="{{ route('change.password') }}" class="space-y-6">
            @csrf
            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-xs font-semibold text-gray-900">Kata Sandi Saat Ini</label>
                <input id="current_password" type="password" class="mt-1 w-full max-w-[55vw] form-input rounded-lg" name="current_password" required autofocus />
                <p id="current_password_error" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>
            <!-- New Password -->
            <div>
                <label for="new_password" class="block text-xs font-semibold text-gray-900">Kata Sandi Baru</label>
                <input id="new_password" type="password" class="mt-1 w-full max-w-[55vw] form-input rounded-lg" name="new_password" required />
                <p id="new_password_error" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>
            <!-- Confirm New Password -->
            <div>
                <label for="confirm_password" class="block text-xs font-semibold text-gray-900">Konfirmasi Kata Sandi Baru</label>
                <input id="confirm_password" type="password" class="mt-1 w-full max-w-[55vw] form-input rounded-lg" name="confirm_password" required />
                <p id="confirm_password_error" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>
            <div>
                <button type="submit" class="bg-[#6E2BB1] px-4 py-2.5 rounded-lg text-xs font-semibold text-white hover:bg-[#8b3ce1] transition-all">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tangani submit formulir
        document.getElementById('passwordForm').addEventListener('submit', function (e) {
            e.preventDefault();
            fetch("{{ route('change.password') }}", {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengubah kata sandi.');
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.fire({
                        title: 'Sukses!',
                        text: data.success,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "{{ route('account.detail') }}"; // Rute ke dashboard
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: error.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
        });
    });
</script>
@endsection

