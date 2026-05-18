<div class="inline-flex items-center">
    @if($jumlahUnread > 0)
        <span class="flex h-2 w-2 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
        </span>
        <span class="ml-2 px-1.5 py-0.5 text-[10px] font-bold bg-red-100 text-red-600 rounded-full dark:bg-red-900/40 dark:text-red-400">
            {{ $jumlahUnread }}
        </span>
    @endif
</div>