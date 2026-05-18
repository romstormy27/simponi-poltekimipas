<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div>
        <a href="{{ route('approval.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
            &larr; Kembali ke Antrean
        </a>
    </div>

    @if($kegiatan->status == 'pending_cancellation')
        <div class="p-4 bg-orange-50 border-l-4 border-orange-500 rounded dark:bg-orange-950/40 text-sm">
            <h4 class="font-bold text-orange-800 dark:text-orange-300">⚠️ Catatan Permohonan Pembatalan Dosen:</h4>
            <p class="text-orange-700 dark:text-orange-200 italic mt-1">"{{ $kegiatan->cancellation_reason }}"</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md space-y-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $kegiatan->title }}</h2>
                    <p class="text-xs text-gray-500 mt-1">Diusulkan oleh: <span class="font-bold text-blue-600">{{ $kegiatan->user->name }}</span></p>
                </div>
                <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">{{ strtoupper($kegiatan->type) }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm border-t border-b border-gray-100 dark:border-gray-700 py-4">
                <div>
                    <p class="text-gray-500">Target Luaran</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $kegiatan->target_output }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Mitra Sasaran</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $kegiatan->partner ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Lokasi Fisik</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $kegiatan->location_or_target }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Masa Kontrak Rencana</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($kegiatan->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($kegiatan->end_date)->format('d M Y') }}</p>
                </div>
            </div>

            @if($kegiatan->latitude && $kegiatan->longitude)
                <div wire:ignore class="space-y-2">
                    <p class="text-sm font-medium text-gray-500">Pemetaan Titik Geotagging</p>
                    <div id="map-approval" class="w-full h-48 rounded-md border border-gray-200 dark:border-gray-700 z-0"></div>
                </div>
            @endif

            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Abstrak Deskripsi Kegiatan</p>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $kegiatan->description }}</p>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-700 pt-4 space-y-3">
                <h4 class="text-sm font-bold text-gray-800 dark:text-white">Formasi Keanggotaan Tim</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach($kegiatan->members as $member)
                        <div class="flex items-center p-2 border border-gray-100 dark:border-gray-700 rounded text-xs bg-gray-50 dark:bg-gray-900/30">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-gray-900 dark:text-white truncate">{{ $member->user->name }}</p>
                                <p class="text-gray-500 capitalize">{{ $member->role }}</p>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $member->status == 'accepted' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $member->status == 'accepted' ? 'Bergabung' : 'Undangan' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                @if($kegiatan->status == 'pending_kaprodi')
                    <button wire:click="$set('showModalTolak', true)" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded shadow transition">
                        Kembalikan Dokumen (Revisi)
                    </button>
                    <button wire:click="setujui" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded shadow transition">
                        Sahkan & Teruskan ke TU
                    </button>
                @elseif($kegiatan->status == 'pending_cancellation')
                    <button wire:click="tolakBatal" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-bold rounded shadow transition">
                        Tolak Pembatalan (Tetap Jalan)
                    </button>
                    <button wire:click="setujuiBatal" wire:confirm="Sahkan pembatalan permanen?" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded shadow transition">
                        Setujui Pembatalan Resmi
                    </button>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md h-fit">
            <h3 class="text-md font-bold text-gray-800 dark:text-white mb-4">Log Rekam Jejak Dokumen</h3>
            <div class="relative border-l border-gray-200 dark:border-gray-700 ml-2 space-y-4">
                @foreach($riwayat as $log)
                    <div class="ml-4 relative">
                        <span class="absolute -left-[22px] top-1 flex h-2 w-2 rounded-full bg-blue-600"></span>
                        <h5 class="text-xs font-bold text-gray-900 dark:text-white">{{ $log->status }}</h5>
                        <p class="text-[10px] text-gray-400">{{ $log->created_at->format('d/m H:i') }}</p>
                        <p class="text-[11px] text-gray-500 mt-0.5">{{ $log->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @if($showModalTolak)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Formulir Catatan Perbaikan Usulan</h3>
            <textarea wire:model="alasan_penolakan" rows="4" class="w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-white text-sm" placeholder="Sebutkan instrumen draf usulan yang cacat hukum/perlu diperbaiki..."></textarea>
            @error('alasan_penolakan') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
            <div class="flex justify-end space-x-2 mt-4">
                <button wire:click="$set('showModalTolak', false)" class="px-4 py-2 bg-gray-300 text-gray-800 rounded text-sm font-semibold">Batal</button>
                <button wire:click="prosesTolak" class="px-4 py-2 bg-red-600 text-white rounded text-sm font-semibold">Kirim ke Dosen</button>
            </div>
        </div>
    </div>
    @endif

    @if($kegiatan->latitude && $kegiatan->longitude)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            const mapEl = document.getElementById('map-approval');
            if(!mapEl) return;
            const map = L.map('map-approval', { center: [{{ $kegiatan->latitude }}, {{ $kegiatan->longitude }}], zoom: 12, dragging: false, scrollWheelZoom: false });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            L.marker([{{ $kegiatan->latitude }}, {{ $kegiatan->longitude }}]).addTo(map);
        });
    </script>
    @endif
</div>