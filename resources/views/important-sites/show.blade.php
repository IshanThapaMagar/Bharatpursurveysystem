<x-app-layout>
    <div class="py-24">
        <div class="px-4 sm:px-6 lg:px-8 max-w-[100%]">
            <div class="bg-white overflow-hidden shadow-sm rounded-sm">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-3xl font-bold text-gray-800">{{ $importantSite->place_name }}</h2>
                        <div class="flex gap-2">
                            <a href="{{ route('important-site.edit', $importantSite) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                                {{ __('Edit') }}
                            </a>
                            <a href="{{ route('important-site.index') }}"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                                {{ __('Back') }}
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-6">
                        @if ($importantSite->photo)
                            <div class="mb-6">
                                <img src="{{ Storage::url($importantSite->photo) }}" alt="{{ $importantSite->place_name }}"
                                    class="w-full h-auto rounded-lg">
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <h3 class="text-gray-700 font-semibold">{{ __('Ward') }}</h3>
                                <p class="text-gray-900">{{ __('Ward') }} {{ $importantSite->ward?->ward_no ?? __('N/A') }}</p>
                            </div>
                            <div>
                                <h3 class="text-gray-700 font-semibold">{{ __('Place Type') }}</h3>
                                <p class="text-gray-900">{{ $importantSite->placeType?->name ?? __('N/A') }}</p>
                            </div>
                        </div>

                        @if ($importantSite->place_description)
                            <div class="mb-6">
                                <h3 class="text-gray-700 font-semibold">{{ __('Description') }}</h3>
                                <p class="text-gray-900">{{ $importantSite->place_description }}</p>
                            </div>
                        @endif

                        @if ($importantSite->latitude && $importantSite->longitude)
                            <div class="mb-6">
                                <h3 class="text-gray-700 font-semibold mb-3">{{ __('Location') }}</h3>
                                <div id="map" style="height: 400px; border-radius: 8px;"></div>
                                <p class="text-gray-600 mt-2">
                                    <strong>{{ __('Coordinates') }}</strong>: {{ $importantSite->latitude }},
                                    {{ $importantSite->longitude }}
                                </p>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-gray-700 font-semibold">{{ __('Created') }}</h3>
                                <p class="text-gray-900">{{ $importantSite->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                            <div>
                                <h3 class="text-gray-700 font-semibold">{{ __('Updated') }}</h3>
                                <p class="text-gray-900">{{ $importantSite->updated_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <script>
        @if ($importantSite->latitude && $importantSite->longitude)
            const map = L.map('map').setView([{{ $importantSite->latitude }}, {{ $importantSite->longitude }}], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            L.marker([{{ $importantSite->latitude }}, {{ $importantSite->longitude }}])
                .bindPopup('{{ $importantSite->place_name }}')
                .addTo(map)
                .openPopup();
        @endif
    </script>
</x-app-layout>
