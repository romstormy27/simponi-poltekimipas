<div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">Borang Pengajuan Kegiatan Dosen</h2>

    @if (session()->has('sukses'))
        <div class="p-4 mb-4 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300">
            {{ session('sukses') }}
        </div>
    @endif

    <form wire:submit.prevent="simpanKegiatan" class="space-y-4">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Kegiatan</label>
                <input type="text" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Contoh: Penerapan AI di Perbatasan">
                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Kegiatan</label>
                <select wire:model="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="penelitian">Penelitian</option>
                    <option value="pengabdian">Pengabdian Masyarakat (Abdimas)</option>
                </select>
                @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Target Luaran</label>
                <input type="text" wire:model="target_output" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Contoh: Jurnal Sinta 4">
                @error('target_output') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mitra Sasaran (Jika Ada)</label>
                <input type="text" wire:model="partner" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Contoh: Kanim Kelas I Tangerang / Masyarakat Desa">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat / Lokasi Kegiatan</label>
                    <input type="text" wire:model="location_or_target" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Contoh: Jl. Liang Lahat No. 45">
                    @error('location_or_target') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Latitude</label>
                    <input type="text" wire:model="latitude" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-not-allowed" placeholder="Klik pada peta">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Longitude</label>
                    <input type="text" wire:model="longitude" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-not-allowed" placeholder="Klik pada peta">
                </div>
            </div>
            
            <div class="md:col-span-2" wire:ignore>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pilih Titik Lokasi pada Peta</label>
                <div id="map" class="w-full h-64 rounded-md border border-gray-300 dark:border-gray-700 z-0"></div>
            </div>
        </div>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        
        <script>
            document.addEventListener('livewire:navigated', () => {
                const mapContainer = document.getElementById('map');
                if (!mapContainer) return;

                // 1. Inisialisasi Peta (Default pusat ke area Jabodetabek/Indonesia)
                const defaultLat = -6.229722;
                const defaultLng = 106.653889;
                const map = L.map('map').setView([defaultLat, defaultLng], 10);

                // 2. Pasang Layer Peta OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // 3. Buat Penanda (Marker) yang bisa digeser
                let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

                function updateCoordinates(lat, lng) {
                    marker.setLatLng([lat, lng]);
                    // Kirim koordinat langsung ke variabel Livewire backend
                    @this.set('latitude', lat.toFixed(7));
                    @this.set('longitude', lng.toFixed(7));
                }

                // Jika Peta diklik
                map.on('click', function(e) {
                    updateCoordinates(e.latlng.lat, e.latlng.lng);
                });

                // Jika Penanda digeser manual
                marker.on('dragend', function(e) {
                    const position = marker.getLatLng();
                    updateCoordinates(position.lat, position.lng);
                });
            });
        </script>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                <input type="date" wire:model="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Selesai</label>
                <input type="date" wire:model="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi / Latar Belakang Singkat</label>
            <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Jelaskan secara singkat urgensi kegiatan ini..."></textarea>
        </div>

        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
            <h3 class="text-md font-bold text-gray-800 dark:text-gray-200 mb-2">Susunan Tim (Opsional)</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Anda otomatis menjadi Ketua. Cari dosen lain untuk ditambahkan sebagai Anggota.</p>
            
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Nama Dosen</label>
                <input type="text" wire:model.live.debounce.300ms="keywordDosen" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Ketik nama dosen...">
                
                @if(!empty($keywordDosen) && count($hasilPencarian) > 0)
                    <div class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 max-h-60 overflow-auto">
                        <ul class="py-1">
                            @foreach($hasilPencarian as $dosen)
                                <li>
                                    <button type="button" wire:click="tambahAnggota({{ $dosen->id }}, '{{ $dosen->name }}')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 dark:text-gray-200 dark:hover:bg-gray-700">
                                        {{ $dosen->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @elseif(!empty($keywordDosen))
                    <div class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm text-gray-500">
                        Tidak ditemukan dosen dengan nama tersebut.
                    </div>
                @endif
            </div>

            @if(count($anggotaTerpilih) > 0)
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($anggotaTerpilih as $index => $anggota)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                            {{ $anggota['name'] }}
                            <button type="button" wire:click="hapusAnggota({{ $index }})" class="ml-2 inline-flex items-center text-blue-400 hover:text-blue-600 dark:hover:text-blue-200 focus:outline-none">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
            Ajukan Kegiatan
        </button>
    </form>
</div>