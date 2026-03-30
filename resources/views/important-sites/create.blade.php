<x-app-layout>
    <div class="py-24">
        <div class="px-4 sm:px-6 lg:px-8 max-w-[100%]">
            <div class="bg-white overflow-hidden shadow-sm rounded-sm">
                <div class="p-6 text-gray-900">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">{{ __('Add Important Site') }}</h2>

                    <form action="{{ route('important-site.store') }}" method="POST" enctype="multipart/form-data"
                        class="bg-white rounded-lg p-6">
                        @csrf

                        <div class="mb-4">
                            <label for="place_name" class="block text-gray-700 font-semibold mb-2">{{ __('Place Name') }}
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="place_name" id="place_name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('place_name') border-red-500 @enderror"
                                value="{{ old('place_name') }}" required>
                            @error('place_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="ward_id" class="block text-gray-700 font-semibold mb-2">{{ __('Ward') }} <span
                                    class="text-red-500">*</span></label>
                            <select name="ward_id" id="ward_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ward_id') border-red-500 @enderror"
                                required>
                                <option value="">{{ __('Select Ward') }}</option>
                                @foreach ($wards as $ward)
                                    <option value="{{ $ward->id }}" {{ old('ward_id') == $ward->id ? 'selected' : '' }}>
                                        {{ __('Ward') }} {{ $ward->ward_no ?? __('N/A') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ward_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="place_type_id"
                                class="block text-gray-700 font-semibold mb-2">{{ __('Place Type') }} <span
                                    class="text-red-500">*</span></label>
                            <select name="place_type_id" id="place_type_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('place_type_id') border-red-500 @enderror"
                                required>
                                <option value="">{{ __('Select Place Type') }}</option>
                                @foreach ($placeTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('place_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('place_type_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="place_description"
                                class="block text-gray-700 font-semibold mb-2">{{ __('Place Description') }}</label>
                            <textarea name="place_description" id="place_description" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('place_description') border-red-500 @enderror">{{ old('place_description') }}</textarea>
                            @error('place_description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="photo"
                                class="block text-gray-700 font-semibold mb-2">{{ __('Photo') }}</label>
                            <input type="file" name="photo" id="photo"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('photo') border-red-500 @enderror"
                                accept="image/*" onchange="validatePhoto(this)">
                            @error('photo')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">{{ __('Location') }}</label>
                            <button type="button" id="openMapBtn"
                                class="w-full px-4 py-3 border-2 border-dashed border-indigo-300 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition-colors text-indigo-700 font-medium flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ __('Select Location on Map') }}
                            </button>

                            <!-- Coordinates Display -->
                            <div class="mt-3 grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs font-medium text-gray-600 mb-1 block">{{ __('Latitude') }}</label>
                                    <div
                                        class="flex items-center gap-2 bg-indigo-50 border border-indigo-200 rounded-md px-3 py-2">
                                        <span class="text-sm font-mono text-indigo-900"
                                            id="latDisplay">{{ __('Not set') }}</span>
                                    </div>
                                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                                </div>
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-600 mb-1 block">{{ __('Longitude') }}</label>
                                    <div
                                        class="flex items-center gap-2 bg-indigo-50 border border-indigo-200 rounded-md px-3 py-2">
                                        <span class="text-sm font-mono text-indigo-900"
                                            id="lonDisplay">{{ __('Not set') }}</span>
                                    </div>
                                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Map Modal -->
                        <div id="mapModal"
                            class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-end md:items-center justify-center p-0 md:p-4">
                            <div
                                class="bg-white rounded-t-lg md:rounded-lg shadow-2xl w-full h-[95vh] md:h-[85vh] md:max-w-5xl flex flex-col">
                                <!-- Modal Header -->
                                <div class="flex justify-between items-center p-4 border-b">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Select Location') }}</h3>
                                    <button type="button" id="closeMapBtn" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Modal Body -->
                                <div class="flex-1 overflow-hidden">
                                    <!-- Map Container -->
                                    <div class="relative h-full">
                                        <div id="mapContainer" class="h-full"></div>
                                        <!-- Info Badge -->
                                        <div
                                            class="absolute top-3 left-3 bg-white rounded-lg shadow-md px-3 py-2 flex items-center gap-2 z-10">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span
                                                class="text-sm font-medium text-gray-700">{{ __('Search or click to set location') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="border-t p-4 flex justify-between items-center gap-3">
                                    <button type="button" id="useMyLocationBtn"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-indigo-600 hover:text-indigo-700 font-medium hover:bg-indigo-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ __('Use My Location') }}
                                    </button>
                                    <div class="flex gap-3">
                                        <button type="button" id="cancelMapBtn"
                                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                            {{ __('Cancel') }}
                                        </button>
                                        <button type="button" id="confirmLocationBtn"
                                            class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg transition-all shadow-sm hover:shadow-md">
                                            {{ __('Confirm Location') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                                {{ __('Save') }}
                            </button>
                            <a href="{{ route('important-site.index') }}"
                                class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        const mapModal = document.getElementById('mapModal');
        const openMapBtn = document.getElementById('openMapBtn');
        const closeMapBtn = document.getElementById('closeMapBtn');
        const cancelMapBtn = document.getElementById('cancelMapBtn');
        const confirmLocationBtn = document.getElementById('confirmLocationBtn');
        const useMyLocationBtn = document.getElementById('useMyLocationBtn');
        const latInput = document.getElementById('latitude');
        const lonInput = document.getElementById('longitude');
        const latDisplay = document.getElementById('latDisplay');
        const lonDisplay = document.getElementById('lonDisplay');

        let map;
        let marker;
        let tempLat, tempLon;

        // Update display when page loads
        window.addEventListener('load', function() {
            updateDisplay();
        });

        // Open map modal
        openMapBtn.addEventListener('click', function(e) {
            e.preventDefault();
            mapModal.classList.remove('hidden');
            setTimeout(initMap, 100);
            tempLat = latInput.value ? parseFloat(latInput.value) : null;
            tempLon = lonInput.value ? parseFloat(lonInput.value) : null;
        });

        // Close map modal
        closeMapBtn.addEventListener('click', closeModal);
        cancelMapBtn.addEventListener('click', closeModal);

        function closeModal() {
            mapModal.classList.add('hidden');
            if (map) {
                map.remove();
                map = null;
            }
        }

        // Initialize map
        function initMap() {
            if (map) return;

            const initialLat = tempLat || 27.7172;
            const initialLon = tempLon || 85.3240;

            map = L.map('mapContainer').setView([initialLat, initialLon], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Add existing marker if coordinates available
            if (tempLat && tempLon) {
                marker = L.marker([tempLat, tempLon]).addTo(map);
            }

            // Click on map to set marker
            map.on('click', function(e) {
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker(e.latlng).addTo(map);
                tempLat = e.latlng.lat;
                tempLon = e.latlng.lng;
                // Update display in real-time
                latDisplay.textContent = tempLat.toFixed(8);
                lonDisplay.textContent = tempLon.toFixed(8);
            });

            // Search functionality
            const geocoder = L.Control.geocoder({
                defaultMarkGeocode: false
            })
            .on('markgeocode', function(e) {
                const latlng = e.geocode.center;
                if (marker) {
                    map.removeLayer(marker);
                }
                tempLat = latlng.lat;
                tempLon = latlng.lng;
                marker = L.marker(latlng).addTo(map);
                map.setView(latlng, 16);
                // Update display in real-time
                latDisplay.textContent = tempLat.toFixed(8);
                lonDisplay.textContent = tempLon.toFixed(8);
            })
            .addTo(map);
        }

        // Confirm location
        confirmLocationBtn.addEventListener('click', function() {
            if (tempLat && tempLon) {
                latInput.value = tempLat.toFixed(8);
                lonInput.value = tempLon.toFixed(8);
                updateDisplay();
                closeModal();
            } else {
                alert('{{ __('Please select a location on the map') }}');
            }
        });

        // Use my location
        useMyLocationBtn.addEventListener('click', function() {
            if (navigator.geolocation) {
                useMyLocationBtn.disabled = true;
                useMyLocationBtn.textContent = '{{ __('Getting location...') }}';
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        tempLat = position.coords.latitude;
                        tempLon = position.coords.longitude;
                        if (marker) {
                            map.removeLayer(marker);
                        }
                        marker = L.marker([tempLat, tempLon]).addTo(map);
                        map.setView([tempLat, tempLon], 13);
                        useMyLocationBtn.disabled = false;
                        useMyLocationBtn.innerHTML =
                            '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>{{ __('Use My Location') }}';
                    },
                    function(error) {
                        alert('{{ __('Error getting location:') }} ' + error.message);
                        useMyLocationBtn.disabled = false;
                        useMyLocationBtn.innerHTML =
                            '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>{{ __('Use My Location') }}';
                    }
                );
            } else {
                alert('{{ __('Geolocation is not supported by your browser') }}');
            }
        });

        // Update display
        function updateDisplay() {
            latDisplay.textContent = latInput.value ? parseFloat(latInput.value).toFixed(8) : '{{ __('Not set') }}';
            lonDisplay.textContent = lonInput.value ? parseFloat(lonInput.value).toFixed(8) : '{{ __('Not set') }}';
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !mapModal.classList.contains('hidden')) {
                closeModal();
            }
        });

        // Close modal on background click
        mapModal.addEventListener('click', function(e) {
            if (e.target === mapModal) {
                closeModal();
            }
        });

        // Photo validation
        function validatePhoto(input) {
            const file = input.files[0];
            if (!file) return;

            // Check if file is an image
            if (!file.type.startsWith('image/')) {
                alert('{{ __('Please select an image file') }}');
                input.value = '';
                return;
            }

            // Check file size (2 MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('{{ __('File size must be less than 2 MB') }}');
                input.value = '';
                return;
            }
        }
    </script>
</x-app-layout>
