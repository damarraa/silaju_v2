@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">Pengaturan Profil</h2>
        <p class="text-sm text-gray-500">Perbarui informasi akun dan kata sandi Anda.</p>
    </div>

    @if(session('success'))
        <div
            class="mb-4 rounded-lg bg-green-100 px-6 py-4 text-green-700 border border-green-200 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">

        {{-- KARTU 1: UPDATE INFO DASAR --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-default dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 font-semibold text-black dark:text-white">Informasi Dasar</h3>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="mb-4">
                    <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                        Nama Lengkap
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500">
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500">
                    @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                {{-- Username (Read Only) --}}
                <div class="mb-6">
                    <label class="mb-2.5 block text-sm font-medium text-gray-500 dark:text-gray-400">
                        Username (Tidak dapat diubah)
                    </label>
                    <input type="text" value="{{ $user->username }}" disabled
                        class="w-full cursor-not-allowed rounded-lg border-[1.5px] border-stroke bg-gray-100 py-3 px-5 font-medium text-gray-500 outline-none dark:border-form-strokedark dark:bg-gray-800 dark:text-gray-400">
                </div>

                {{-- PASSWORD CHANGE SECTION --}}
                <div class="border-t border-gray-200 pt-6 mt-6 dark:border-gray-700">
                    <h3 class="mb-4 font-semibold text-black dark:text-white">Ganti Kata Sandi (Opsional)</h3>

                    {{-- Current Password --}}
                    <div class="mb-4">
                        <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                            Password Saat Ini
                        </label>
                        <div class="relative">
                            <input type="password" name="current_password" id="currentPassword"
                                placeholder="Isi hanya jika ingin ganti password"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 pl-5 pr-10 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500">

                            <button type="button" onclick="togglePasswordVisibility('currentPassword', this)"
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
                        @error('current_password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="mb-4">
                        <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                            Password Baru
                        </label>
                        <div class="relative">
                            <input type="password" name="new_password" id="newPassword" placeholder="Minimal 8 karakter"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 pl-5 pr-10 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500">

                            <button type="button" onclick="togglePasswordVisibility('newPassword', this)"
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
                        @error('new_password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-6">
                        <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <input type="password" name="new_password_confirmation" id="confirmPassword"
                                placeholder="Ulangi password baru"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 pl-5 pr-10 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500">

                            <button type="button" onclick="togglePasswordVisibility('confirmPassword', this)"
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
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="rounded-lg bg-brand-600 px-6 py-3 font-medium text-white hover:bg-brand-700 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- KARTU 2: INFO ROLE & RAYON (READ ONLY) --}}
        <div
            class="rounded-lg border border-gray-200 bg-white p-6 shadow-default dark:border-gray-800 dark:bg-gray-900 h-fit">
            <h3 class="mb-4 font-semibold text-black dark:text-white">Detail Akun</h3>

            <div class="flex flex-col gap-4">
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <span class="block text-xs font-bold text-gray-500 uppercase mb-1">Role / Jabatan</span>
                    <span
                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-sm font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/20 uppercase">
                        {{ $user->getRoleNames()->first() ?? 'User' }}
                    </span>
                </div>

                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <span class="block text-xs font-bold text-gray-500 uppercase mb-1">Unit Rayon</span>
                    @if($user->rayon)
                        <span class="block text-lg font-bold text-black dark:text-white">{{ $user->rayon->nama }}</span>
                        <span class="text-sm text-gray-500">Kode: {{ $user->rayon->kode_rayon }}</span>
                    @else
                        <span class="text-sm text-gray-400 italic">Non-Rayon (Pusat)</span>
                    @endif
                </div>

                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <span class="block text-xs font-bold text-gray-500 uppercase mb-1">Bergabung Sejak</span>
                    <span class="block text-sm font-medium text-black dark:text-white">
                        {{ $user->created_at->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- SCRIPT TOGGLE VISIBILITY --}}
    <script>
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
    </script>
@endsection