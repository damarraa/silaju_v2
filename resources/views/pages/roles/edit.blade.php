@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Edit Role: {{ ucwords(str_replace('_', ' ', $role->name)) }}
        </h2>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">
        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            {{-- Nama Role --}}
            <div class="mb-6 max-w-lg">
                <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                    Nama Role <span class="text-meta-1">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}"
                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-form-strokedark dark:bg-form-input dark:focus:border-brand-500 @error('name') border-red-500 @enderror">
                @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-black dark:text-white">Atur Hak Akses (Permissions)</h3>
                    <button type="button" id="checkAllBtn" class="text-sm text-brand-600 hover:underline font-medium">
                        Pilih/Batalkan Semua
                    </button>
                </div>

                {{-- Permission Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        // Grouping Logic: Ambil kata pertama sebelum '_'
                        $groups = $permissions->groupBy(function($item) {
                            return explode('_', $item->name)[0];
                        });
                    @endphp

                    @foreach($groups as $groupName => $perms)
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                            {{-- Header Group --}}
                            <div class="flex items-center justify-between mb-3 border-b border-gray-200 dark:border-gray-700 pb-2">
                                <span class="font-bold text-sm uppercase text-gray-500 dark:text-gray-400">
                                    Modul {{ $groupName }}
                                </span>
                            </div>

                            {{-- List Checkbox --}}
                            <div class="space-y-2">
                                @foreach($perms as $perm)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" 
                                            {{-- LOGIC INI YANG PENTING DI HALAMAN EDIT --}}
                                            {{ in_array($perm->name, $rolePermissions) ? 'checked' : '' }}
                                            class="perm-checkbox h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700">
                                        
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-black dark:text-gray-300 dark:group-hover:text-white transition select-none">
                                            {{ ucwords(str_replace($groupName.'_', '', $perm->name)) }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('permissions') <span class="text-xs text-red-500 mt-2 block">{{ $message }}</span> @enderror
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('roles.index') }}" class="rounded-lg border border-stroke px-6 py-3 font-medium text-black hover:bg-gray-100 dark:border-strokedark dark:text-white dark:hover:bg-meta-4 transition">
                    Batal
                </a>
                <button type="submit" class="rounded-lg bg-brand-600 px-6 py-3 font-medium text-white hover:bg-brand-700 transition">
                    Update Role
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('checkAllBtn').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.perm-checkbox');
            const anyUnchecked = Array.from(checkboxes).some(cb => !cb.checked);
            
            checkboxes.forEach(cb => cb.checked = anyUnchecked);
        });
    </script>
@endsection