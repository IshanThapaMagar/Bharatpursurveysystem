<x-app-layout>
    <div class="py-12 sm:py-24 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                    {{ __('Ward Details') }}: {{ $ward->name }}
                </h2>
                <p class="mt-1 text-xs text-gray-500">
                    {{ __('Ward No. ') }} {{ $ward->ward_no }}
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('wards.edit', $ward) }}"
                    class="inline-flex justify-center px-4 py-2 bg-blue-600 border border-transparent shadow-lg text-sm font-bold rounded-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('palika.index') }}"
                    class="inline-flex justify-center px-4 py-2 bg-gray-200 border border-transparent shadow-lg text-sm font-bold rounded-sm text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                    {{ __('Back') }}
                </a>
            </div>
        </div>

        <!-- Ward Building Photo and Info Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 items-stretch">
            <!-- Building Photo -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-sm border border-gray-100 overflow-hidden rounded-lg h-full">
                    <div class="h-full overflow-hidden bg-gray-100">
                        @if ($ward->building_photo)
                            <img src="{{ Storage::disk('public')->url($ward->building_photo) }}"
                                alt="{{ $ward->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                <svg class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ward Information -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white shadow-sm border border-gray-100 overflow-hidden rounded-lg p-6 h-full">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Ward Information') }}</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                                {{ __('Location') }}
                            </label>
                            <p class="text-gray-900">{{ $ward->location }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                                {{ __('Contact Number') }}
                            </label>
                            <p class="text-gray-900">
                                @if ($ward->contact_number)
                                    <a href="tel:{{ $ward->contact_number }}" class="text-blue-600 hover:underline">
                                        {{ $ward->contact_number }}
                                    </a>
                                @else
                                    <span class="text-gray-400">{{ __('Not provided') }}</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                                {{ __('Email Address') }}
                            </label>
                            <p class="text-gray-900">
                                @if ($ward->email)
                                    <a href="mailto:{{ $ward->email }}" class="text-blue-600 hover:underline">
                                        {{ $ward->email }}
                                    </a>
                                @else
                                    <span class="text-gray-400">{{ __('Not provided') }}</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                                {{ __('Coordinates') }}
                            </label>
                            <div class="flex items-center gap-2">
                                <p class="text-gray-900">
                                    @if ($ward->latitude && $ward->longitude)
                                        <span class="font-mono text-sm">
                                            {{ number_format($ward->latitude, 6) }},
                                            {{ number_format($ward->longitude, 6) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">{{ __('Not set') }}</span>
                                    @endif
                                </p>

                                @if ($ward->latitude && $ward->longitude)
                                    <button type="button" id="viewMapBtn"
                                        class="inline-flex items-center justify-center p-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full transition-colors"
                                        title="{{ __('View on Map') }}">
                                        <i class="fa-solid fa-location-arrow"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($ward->description)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                {{ __('Description') }}
                            </label>
                            <p class="text-gray-900">{{ $ward->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Officials Directory -->
        <div class="bg-white shadow-sm border border-gray-100 overflow-hidden rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">{{ __('Officials Directory') }}</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach ($ward->members as $member)
                        @php
                            $designation =
                                $member->designation->translations->where('locale', app()->getLocale())->first() ??
                                $member->designation->translations->first();
                        @endphp

                        <div
                            class="border border-gray-200 rounded-lg p-3 bg-gray-50 hover:shadow-md transition max-w-sm w-full mx-auto">
                            <div class="flex items-center gap-3">

                                <!-- Photo -->
                                <div class="flex-shrink-0">
                                    @if ($member->photo)
                                        <img src="{{ Storage::disk('public')->url($member->photo) }}"
                                            alt="{{ $member->name }}"
                                            class="h-16 w-16 rounded-full object-cover border border-gray-200">
                                    @else
                                        <div
                                            class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center border border-gray-200">
                                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Details -->
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-gray-900">{{ $member->name }}</h4>
                                    <p class="text-xs text-gray-600">
                                        {{ $designation->name ?? 'N/A' }}
                                    </p>

                                    <div class="text-xs text-gray-600">
                                        @if ($member->phone_number)
                                            <div>{{ $member->phone_number }}</div>
                                        @endif

                                        @if ($member->email)
                                            <div>{{ $member->email }}</div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- Coordinate Map Modal -->
    @if ($ward->latitude && $ward->longitude)
        <div id="coordMapModal"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-end md:items-center justify-center p-0 md:p-4">
            <div
                class="bg-white rounded-t-lg md:rounded-lg shadow-2xl w-full h-[95vh] md:h-[85vh] md:max-w-5xl flex flex-col">
                <!-- Modal Header -->
                <div class="flex justify-between items-center p-4 border-b">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('Ward Location') }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($ward->latitude, 8) }},
                            {{ number_format($ward->longitude, 8) }}</p>
                    </div>
                    <button type="button" id="closeCoordMapBtn" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 overflow-hidden">
                    <div id="coordMapContainer" class="h-full"></div>
                </div>
            </div>
        </div>
    @endif

    <!-- Leaflet Map Libraries -->
    @if ($ward->latitude && $ward->longitude)
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

        <script>
            const coordMapModal = document.getElementById('coordMapModal');
            const viewMapBtn = document.getElementById('viewMapBtn');
            const closeCoordMapBtn = document.getElementById('closeCoordMapBtn');

            let coordMap;

            viewMapBtn.addEventListener('click', function(e) {
                e.preventDefault();
                coordMapModal.classList.remove('hidden');
                setTimeout(initCoordMap, 100);
            });

            closeCoordMapBtn.addEventListener('click', closeCoordModal);

            function closeCoordModal() {
                coordMapModal.classList.add('hidden');
                if (coordMap) {
                    coordMap.remove();
                    coordMap = null;
                }
            }

            function initCoordMap() {
                if (coordMap) return;

                const wardLat = {{ $ward->latitude }};
                const wardLon = {{ $ward->longitude }};

                coordMap = L.map('coordMapContainer').setView([wardLat, wardLon], 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(coordMap);

                L.marker([wardLat, wardLon])
                    .bindPopup('<strong>{{ addslashes($ward->name) }}</strong><br>{{ addslashes($ward->location) }}')
                    .addTo(coordMap)
                    .openPopup();
            }

            // Close modal on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !coordMapModal.classList.contains('hidden')) {
                    closeCoordModal();
                }
            });

            // Close modal on background click
            coordMapModal.addEventListener('click', function(e) {
                if (e.target === coordMapModal) {
                    closeCoordModal();
                }
            });

            // Initialize main map
            document.addEventListener('DOMContentLoaded', function() {
                const wardLat = {{ $ward->latitude }};
                const wardLon = {{ $ward->longitude }};

                // Initialize map
                const map = L.map('wardMapContainer').setView([wardLat, wardLon], 14);

                // Add tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                // Add marker
                L.marker([wardLat, wardLon])
                    .bindPopup('<strong>{{ addslashes($ward->name) }}</strong><br>{{ addslashes($ward->location) }}')
                    .addTo(map)
                    .openPopup();
            });
        </script>
    @endif
</x-app-layout>
