@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">Halo, {{ auth()->user()->name }}! 👋</h2>
        <p class="text-sm text-gray-500">Selamat bekerja, berikut adalah ringkasan aktivitas Anda.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 mb-6">
        {{-- Card 1 --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-full bg-brand-50 text-brand-600 dark:bg-brand-900/20 dark:text-brand-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Inputan Saya</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($myTotalInput) }}</h3>
                </div>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-full bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Inputan Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($myInputToday) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Recent --}}
    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <h3 class="font-semibold text-black dark:text-white">Riwayat Inputan Terakhir</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="bg-gray-50 dark:bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3">ID Pelanggan</th>
                        <th class="px-6 py-3">Rayon</th>
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($myRecents as $item)
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $item->id_pelanggan }}</td>
                            <td class="px-6 py-4">{{ $item->rayon->nama ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $item->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('pju.edit', $item->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-400">Belum ada data inputan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection