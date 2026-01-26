{{-- Notification Dropdown Component --}}
<div class="relative" x-data="{
        open: false,
        notifying: {{ auth()->user()->unreadNotifications->count() > 0 ? 'true' : 'false' }},

        toggle() {
            this.open = !this.open;

            if (this.open && this.notifying) {
                this.notifying = false;
                fetch('{{ route('notifications.read') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
            }
        },

        close() {
            this.open = false;
        }
    }" @click.away="close()">

    {{-- Bell Button --}}
    <button type="button" @click="toggle()" class="relative flex h-11 w-11 items-center justify-center rounded-full
               border border-gray-200 bg-white text-gray-500
               hover:bg-gray-100 hover:text-gray-700
               dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400
               dark:hover:bg-gray-800 dark:hover:text-white transition">
        <span x-show="notifying"
            class="absolute right-0 top-0.5 h-2 w-2 rounded-full bg-orange-500 border-2 border-white dark:border-gray-900">
            <span class="absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75 animate-ping"></span>
        </span>

        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M10.75 2.29248C10.75 1.87827 10.4143 1.54248 10 1.54248C9.58583 1.54248 9.25004 1.87827 9.25004 2.29248V2.83613C6.08266 3.20733 3.62504 5.9004 3.62504 9.16748V14.4591H3.33337C2.91916 14.4591 2.58337 14.7949 2.58337 15.2091C2.58337 15.6234 2.91916 15.9591 3.33337 15.9591H16.6667C17.0809 15.9591 17.4167 15.6234 17.4167 15.2091C17.4167 14.7949 17.0809 14.4591 16.6667 14.4591H16.375V9.16748C16.375 5.9004 13.9174 3.20733 10.75 2.83613V2.29248Z" />
        </svg>
    </button>

    {{-- Dropdown --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" class="
            fixed inset-x-0 top-[4.5rem]
            mx-auto
            w-[calc(100vw-1rem)]
            max-w-[360px]
            rounded-2xl
            border border-gray-200
            bg-white
            p-3
            shadow-theme-lg
            dark:border-gray-800
            dark:bg-gray-dark

            lg:absolute lg:top-auto lg:inset-x-auto
            lg:right-0 lg:mt-2.5
            lg:w-[360px]

            z-[9999]
        " style="display: none;">

        {{-- Header --}}
        <div class="mb-3 flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800">
            <h5 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Notifikasi
            </h5>
            <button @click="close()" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>

        {{-- List --}}
        <ul class="max-h-[380px] space-y-1 overflow-y-auto custom-scrollbar">
            @forelse(auth()->user()->notifications as $notification)
                <li>
                    <a href="{{ $notification->data['url'] ?? '#' }}" @click="close()" class="flex gap-3 rounded-lg p-3 transition
                                   hover:bg-gray-100 dark:hover:bg-white/5
                                   {{ $notification->read_at ? '' : 'bg-gray-50/80 dark:bg-white/[0.03]' }}">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-500">
                            🔔
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between">
                                <p class="truncate pr-2 text-sm font-semibold text-gray-800 dark:text-white/90">
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                </p>
                                <span class="text-xs text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="line-clamp-2 text-xs text-gray-500 dark:text-gray-400">
                                {{ $notification->data['message'] ?? '-' }}
                            </p>
                        </div>
                    </a>
                </li>
            @empty
                <li class="flex h-40 items-center justify-center text-gray-400">
                    Tidak ada notifikasi
                </li>
            @endforelse
        </ul>
    </div>
</div>