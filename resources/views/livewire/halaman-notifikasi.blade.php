<div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Pusat Pemberitahuan</h2>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <button wire:click="bersihkanSemua" class="text-xs text-blue-600 hover:underline dark:text-blue-400">Tandai semua sudah dibaca</button>
            @endif
        </div>

        <div class="space-y-4">
            @forelse($allNotif as $notif)
                <div wire:click="tandaiSudahBaca('{{ $notif->id }}')" 
                     class="p-4 rounded-lg border transition cursor-pointer flex justify-between items-center {{ $notif->read_at ? 'bg-gray-50 border-gray-200 dark:bg-gray-900/30 dark:border-gray-700 opacity-60' : 'bg-blue-50/50 border-blue-100 dark:bg-blue-900/10 dark:border-blue-900' }}">
                    <div>
                        <h4 class="text-sm font-bold {{ $notif->read_at ? 'text-gray-700 dark:text-gray-300' : 'text-blue-900 dark:text-blue-400' }}">{{ $notif->data['title'] }}</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $notif->data['message'] }}</p>
                        <span class="text-[10px] text-gray-400 block mt-2">{{ $notif->created_at->diffForHumans() }}</span>
                    </div>
                    @if(!$notif->read_at)
                        <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500 text-center py-8">Tidak ada notifikasi untuk Anda.</p>
            @endforelse
        </div>
    </div>
</div>