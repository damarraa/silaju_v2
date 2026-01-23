@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Edit Data User
        </h2>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">
        <div class="border-b border-gray-200 py-4 px-6 dark:border-gray-800">
            <h3 class="font-medium text-black dark:text-white">Formulir Edit Pengguna</h3>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT') {{-- Wajib untuk Update --}}

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                {{-- Nama Lengkap --}}
                <div>
                    <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                        Nama Lengkap <span class="text-meta-1">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500 @error('name') border-red-500 @enderror">
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Email Address --}}
                <div>
                    <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                        Alamat Email <span class="text-meta-1">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500 @error('email') border-red-500 @enderror">
                    @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Username --}}
                <div>
                    <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                        Username <span class="text-meta-1">*</span>
                    </label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}"
                        class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500 @error('username') border-red-500 @enderror">
                    @error('username') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Password (Optional) --}}
                <div>
                    <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                        Password Baru <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="password" name="password" placeholder="Biarkan kosong jika tidak diganti"
                        class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500 @error('password') border-red-500 @enderror">
                    @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                        class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500">
                </div>

                {{-- Role Selection --}}
                <div>
                    <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                        Role / Jabatan <span class="text-meta-1">*</span>
                    </label>
                    <select name="role" id="roleSelect"
                        class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500 cursor-pointer @error('role') border-red-500 @enderror">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ (old('role') == $role->name) || ($user->hasRole($role->name)) ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Rayon Selection --}}
                <div id="rayonContainer" class="{{ $user->hasRole('petugas') ? '' : 'hidden' }}">
                    <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                        Penempatan Unit Rayon <span class="text-meta-1">*</span>
                    </label>
                    <select name="rayon_id" id="rayonSelect"
                        class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500 cursor-pointer @error('rayon_id') border-red-500 @enderror">
                        <option value="">-- Pilih Rayon --</option>
                        @foreach($rayons as $rayon)
                            <option value="{{ $rayon->id }}" {{ (old('rayon_id') == $rayon->id) || ($user->rayon_id == $rayon->id) ? 'selected' : '' }}>
                                {{ $rayon->nama }} ({{ $rayon->kode_rayon }})
                            </option>
                        @endforeach
                    </select>
                    @error('rayon_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('users.index') }}"
                    class="rounded-lg border border-stroke px-6 py-3 font-medium text-black hover:bg-gray-100 dark:border-strokedark dark:text-white dark:hover:bg-meta-4 transition">
                    Batal
                </a>
                <button type="submit"
                    class="rounded-lg bg-brand-600 px-6 py-3 font-medium text-white hover:bg-brand-700 transition">
                    Update User
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('roleSelect');
            const rayonContainer = document.getElementById('rayonContainer');

            function checkRole() {
                if (roleSelect.value === 'petugas') {
                    rayonContainer.classList.remove('hidden');
                } else {
                    rayonContainer.classList.add('hidden');
                    // Saat edit, kita tidak mereset value select box 
                    // agar jika user salah pilih role lalu balik lagi, value asli tetap ada.
                    // Backend yang akan handle pembersihan data.
                }
            }

            roleSelect.addEventListener('change', checkRole);
            checkRole(); // Jalankan saat load untuk set state awal
        });
    </script>
@endsection