<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    
    <div>
        <a href="{{ route('pengajuan.riwayat') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Status Perjalanan Kegiatan</h3>
        <div class="relative flex items-center justify-between w-full">
            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200 dark:bg-gray-700"></div>
            
            @php
                $tahap = 1;
                if(in_array($kegiatan->status, ['pending_tu'])) $tahap = 2;
                if(in_array($kegiatan->status, ['active'])) $tahap = 3;
                if(in_array($kegiatan->status, ['pending_final_approval', 'completed'])) $tahap = 4;
            @endphp

            <div class="relative z-10 flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm {{ $tahap >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">1</div>
                <div class="mt-2 text-xs font-semibold text-gray-600 dark:text-gray-400">Pengajuan</div>
            </div>
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm {{ $tahap >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">2</div>
                <div class="mt-2 text-xs font-semibold text-gray-600 dark:text-gray-400">Approval Kaprodi</div>
            </div>
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm {{ $tahap >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">3</div>
                <div class="mt-2 text-xs font-semibold text-gray-600 dark:text-gray-400">Pelaksanaan</div>
            </div>
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm {{ $tahap >= 4 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">4</div>
                <div class="mt-2 text-xs font-semibold text-gray-600 dark:text-gray-400">Selesai / BKD</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="md:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md space-y-6">
            <div class="flex justify-between items-start">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $kegiatan->title }}</h2>
                <span class="px-3 py-1 bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded-full text-xs font-bold">{{ strtoupper($kegiatan->type) }}</span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm pt-4 border-t border-gray-100 dark:border-gray-700">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Target Luaran</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $kegiatan->target_output }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Mitra Sasaran</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $kegiatan->partner ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Waktu Pelaksanaan</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($kegiatan->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($kegiatan->end_date)->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Nomor Surat Tugas</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $kegiatan->document_number_task ?? 'Belum Terbit' }}</p>
                    <p class="text-gray-500 dark:text-gray-400">Nomor Surat Izin</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $kegiatan->document_number_permit ?? 'Belum Terbit' }}</p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-gray-700 space-y-3">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Alamat Lokasi Kegiatan</p>
                    <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $kegiatan->location_or_target }}</p>
                </div>
                @if($kegiatan->latitude && $kegiatan->longitude)
                    <div wire:ignore>
                        <div id="map-view" class="w-full h-48 rounded-md border border-gray-200 dark:border-gray-700 z-0"></div>
                    </div>
                @endif
            </div>

            <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">Deskripsi Kegiatan</p>
                <p class="text-gray-800 dark:text-gray-200 text-sm leading-relaxed">{{ $kegiatan->description }}</p>
            </div>
            
            <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                <h3 class="text-md font-bold text-gray-800 dark:text-white mb-4">Susunan Tim Kegiatan</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($kegiatan->members as $member)
                        <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-700 dark:text-blue-300 font-bold text-lg shadow-sm">
                                {{ strtoupper(substr($member->user->name, 0, 1)) }}
                            </div>
                            <div class="ml-3 flex-1 overflow-hidden">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $member->user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ $member->role }}</p>
                            </div>
                            <div class="text-right ml-2 flex-shrink-0">
                                @if($member->status == 'accepted')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-[10px] font-bold rounded-full border border-green-200">Bergabung</span>
                                @elseif($member->status == 'pending')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-[10px] font-bold rounded-full border border-yellow-200 animate-pulse">Menunggu</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-[10px] font-bold rounded-full border border-red-200">Menolak</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if(auth()->id() == $kegiatan->user_id)
                <div class="mt-8 flex space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    @if(in_array($kegiatan->status, ['draft', 'pending_kaprodi', 'perlu_revisi']))
                        <button wire:click="hapusLangsung" wire:confirm="Yakin ingin menghapus pengajuan ini secara permanen?" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm font-bold shadow transition">
                            Hapus Permanen
                        </button>
                    @elseif(in_array($kegiatan->status, ['pending_tu', 'active']))
                        <button wire:click="$set('showModalBatal', true)" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded text-sm font-bold shadow transition">
                            Ajukan Pembatalan ke Kaprodi
                        </button>
                    @endif
                </div>
            @else
                <div class="mt-8 p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded text-sm font-medium">
                    Anda tergabung sebagai <strong>Anggota Tim</strong> dalam kegiatan ini. Segala perubahan dokumen hanya dapat dilakukan oleh Ketua Tim.
                </div>
            @endif

        </div> <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md h-fit">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Riwayat Aktivitas</h3>
            
            <div class="relative border-l border-gray-200 dark:border-gray-700 ml-3">
                @forelse($riwayat as $log)
                    <div class="mb-6 ml-6">
                        <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white dark:ring-gray-800 dark:bg-blue-900">
                            <svg class="w-3 h-3 text-blue-800 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        </span>
                        <h4 class="mb-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $log->status }}</h4>
                        <time class="block mb-2 text-xs font-normal leading-none text-gray-400 dark:text-gray-500">{{ $log->created_at->diffForHumans() }} ({{ $log->created_at->format('d/m H:i') }})</time>
                        <p class="text-xs font-normal text-gray-500 dark:text-gray-400">{{ $log->description }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 ml-4">Belum ada riwayat tercatat.</p>
                @endforelse
            </div>
        </div>

    </div> @if($showModalBatal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Pengajuan Pembatalan Kegiatan</h3>
            <p class="text-sm text-gray-500 mb-4">Kegiatan ini sudah disetujui sebelumnya. Anda harus menyertakan alasan yang kuat untuk membatalkannya.</p>
            
            <textarea wire:model="alasan_pembatalan" rows="4" class="w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white mb-2" placeholder="Contoh: Terjadi bentrok dengan jadwal diklat mendadak dari Kemenkumham..."></textarea>
            @error('alasan_pembatalan') <span class="text-red-500 text-xs block mb-4">{{ $message }}</span> @enderror

            <div class="flex justify-end space-x-2 mt-4">
                <button wire:click="$set('showModalBatal', false)" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded text-sm font-semibold">Batal</button>
                <button wire:click="ajukanPembatalan" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm font-semibold">Kirim Permohonan</button>
            </div>
        </div>
    </div>
    @endif

    @if($kegiatan->latitude && $kegiatan->longitude)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            const mapElement = document.getElementById('map-view');
            if (!mapElement) return;

            const lat = {{ $kegiatan->latitude }};
            const lng = {{ $kegiatan->longitude }};
            
            // Kunci koordinat dan matikan fitur drag/scroll agar peta murni read-only
            const map = L.map('map-view', {
                center: [lat, lng],
                zoom: 13,
                dragging: false,
                scrollWheelZoom: false,
                zoomControl: true
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            L.marker([lat, lng]).addTo(map).bindPopup("Lokasi Kegiatan").openPopup();
        });
    </script>
    @endif
</div>