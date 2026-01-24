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
            @method('PUT')

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

                {{-- Password Baru (Dengan Toggle) --}}
                <div>
                    <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                        Password Baru <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="passwordInput"
                            placeholder="Biarkan kosong jika tidak diganti"
                            class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 pl-5 pr-10 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500 @error('password') border-red-500 @enderror">

                        <button type="button" onclick="togglePasswordVisibility('passwordInput', this)"
                            class="absolute right-4 top-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                            <svg class="h-5 w-5 show-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <svg class="h-5 w-5 hide-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.574-2.59M6 6l12 12">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.94 17.94A10.07 10.07 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.574-2.59M6 6l12 12">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.73 5.08A10.43 10.43 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.574 2.59m-5.322-2.338a3 3 0 11-4.242-4.242">
                                </path>
                            </svg>
                        </button>
                    </div>
                    @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Konfirmasi Password Baru (Dengan Toggle) --}}
                <div>
                    <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="passwordConfirmInput"
                            placeholder="Ulangi password baru"
                            class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 pl-5 pr-10 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500">

                        <button type="button" onclick="togglePasswordVisibility('passwordConfirmInput', this)"
                            class="absolute right-4 top-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                            <svg class="h-5 w-5 show-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <svg class="h-5 w-5 hide-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.574-2.59M6 6l12 12">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.94 17.94A10.07 10.07 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.574-2.59M6 6l12 12">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.73 5.08A10.43 10.43 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.574 2.59m-5.322-2.338a3 3 0 11-4.242-4.242">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Rayon Selection --}}
                <div id="rayonContainer" class="{{ $user->hasRole('petugas') ? '' : 'hidden' }} sm:col-span-2">
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
        // Fungsi Toggle Password
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const showIcon = button.querySelector('.show-icon');
            const hideIcon = button.querySelector('.hide-icon');

            if (input.type === 'password') {
                input.type = 'text';
                showIcon.classList.remove('hidden');
                hideIcon.classList.add('hidden');
            } else {
                input.type = 'password';
                showIcon.classList.add('hidden');
                hideIcon.classList.remove('hidden');
            }
        }

        // Logic Show/Hide Rayon
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('roleSelect');
            const rayonContainer = document.getElementById('rayonContainer');

            function checkRole() {
                if (roleSelect.value === 'petugas') {
                    rayonContainer.classList.remove('hidden');
                } else {
                    rayonContainer.classList.add('hidden');
                }
            }

            roleSelect.addEventListener('change', checkRole);
            checkRole(); // Jalankan saat load
        });
    </script>
@endsection