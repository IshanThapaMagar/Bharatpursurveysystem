<x-app-layout>
    <div class="py-12 sm:py-24 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                    {{ isset($resourceMapping) ? __('Edit Resource Mapping') : __('Create Resource Mapping') }}
                </h2>
                <p class="mt-1 text-xs text-gray-500">
                    {{ __('Manage physical resource mapping for wards and toles.') }}
                </p>
            </div>
            <a href="{{ route('resource-mapping.index') }}"
                class="text-xs font-bold text-gray-500 hover:text-gray-700 uppercase tracking-wider flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Back to List') }}
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-sm bg-red-50 p-4 border border-red-100 shadow-sm ">
                <div class="flex">
                    <div class="shrink-0 text-red-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-xs font-bold text-red-800 uppercase tracking-wider">
                            {{ __('Submission Errors') }}</h3>
                        <div class="mt-1 text-xs text-red-700">
                            <ul role="list" class="list-disc pl-5 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form
            action="{{ isset($resourceMapping) ? route('resource-mapping.update', $resourceMapping->id) : route('resource-mapping.store') }}"
            method="POST" class="space-y-6">
            @csrf
            @if (isset($resourceMapping))
                @method('PUT')
            @endif

            <!-- Location Details -->
            <div class="bg-white shadow-sm border border-gray-100 overflow-hidden rounded-sm">
                <div class="p-5 sm:p-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b pb-2">
                        {{ __('Location Information') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Ward') }}
                                <span class="text-red-500">*</span></label>
                            <select name="ward_id" id="ward_id" required onchange="fetchToles(this.value)"
                                class="block w-full rounded-sm border-gray-200 bg-gray-50/50 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-sm">
                                <option value="">{{ __('Select Ward') }}</option>
                                @foreach ($wards as $ward)
                                    <option value="{{ $ward->id }}"
                                        {{ old('ward_id', $resourceMapping->ward_id ?? '') == $ward->id ? 'selected' : '' }}>
                                        Ward No. {{ $ward->ward_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Tole') }}
                                <span class="text-red-500">*</span></label>
                            <select name="tole_id" id="tole_id" required
                                class="block w-full rounded-sm border-gray-200 bg-gray-50/50 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-sm">
                                <option value="">{{ __('Select Tole') }}</option>
                                @php
                                    $currentWardId = old('ward_id', $resourceMapping->ward_id ?? null);
                                    $toles = $currentWardId ? \App\Models\Tole::where('ward_id', $currentWardId)->get() : [];
                                    $currentToleId = old('tole_id', $resourceMapping->tole_id ?? null);
                                @endphp
                                @foreach($toles as $tole)
                                    <option value="{{ $tole->id }}" {{ $currentToleId == $tole->id ? 'selected' : '' }}>
                                        {{ $tole->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resource Mapping Details -->
            <div class="bg-white shadow-sm border border-gray-100 overflow-hidden rounded-sm">
                <div class="p-5 sm:p-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b pb-2">
                        {{ __('General Mapping Details') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Electricity Pole Number') }}</label>
                            <input type="text" name="electricity_pole_number"
                                value="{{ old('electricity_pole_number', $resourceMapping->electricity_pole_number ?? '') }}"
                                class="block w-full rounded-sm border-gray-200 bg-gray-50/50 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-sm">
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Tole Development Office Type') }}</label>
                            <select name="tole_dev_office_type_id"
                                class="block w-full rounded-sm border-gray-200 bg-gray-50/50 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-sm">
                                <option value="">{{ __('None') }}</option>
                                @foreach ($toleDevOfficeTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('tole_dev_office_type_id', $resourceMapping->tole_dev_office_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center mt-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="nala_nikash" value="1"
                                    {{ old('nala_nikash', $resourceMapping->nala_nikash ?? false) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div
                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                                <span
                                    class="ms-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">{{ __('Nala Nikash') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center mt-6">
                        </div>
                    </div>
                </div>

                <!-- Dynamic Sections for Poles and Roads -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Pole Types -->
                    <div class="bg-white shadow-sm border border-gray-100 overflow-hidden rounded-sm">
                        <div class="p-5">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b pb-2">
                                {{ __('Pole Types Quantities') }}</h3>
                            <div class="space-y-4">
                                @foreach ($poleTypes as $index => $pole)
                                    @php
                                        $existingQuantity = isset($resourceMapping)
                                            ? $resourceMapping->poleTypes->where('id', $pole->id)->first()?->pivot
                                                ->quantity
                                            : null;
                                    @endphp
                                    <div class="flex items-center justify-between gap-4 bg-gray-50/50 p-2 rounded-sm">
                                        <span class="text-xs font-semibold text-gray-700">{{ $pole->name }}</span>
                                        <input type="hidden" name="pole_types[{{ $index }}][id]"
                                            value="{{ $pole->id }}">
                                        <input type="number" name="pole_types[{{ $index }}][quantity]"
                                            value="{{ old("pole_types.$index.quantity", $existingQuantity) }}"
                                            placeholder="0"
                                            class="w-24 rounded-sm border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-1 px-2 text-xs">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Road Types -->
                    <div class="bg-white shadow-sm border border-gray-100 overflow-hidden rounded-sm">
                        <div class="p-5">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b pb-2">
                                {{ __('Road Types Length (m)') }}</h3>
                            <div class="space-y-4">
                                @foreach ($roadTypes as $index => $road)
                                    @php
                                        $existingLength = isset($resourceMapping)
                                            ? $resourceMapping->roadTypes->where('id', $road->id)->first()?->pivot
                                                ->length
                                            : null;
                                    @endphp
                                    <div class="flex items-center justify-between gap-4 bg-gray-50/50 p-2 rounded-sm">
                                        <span class="text-xs font-semibold text-gray-700">{{ $road->name }}</span>
                                        <input type="hidden" name="road_types[{{ $index }}][id]"
                                            value="{{ $road->id }}">
                                        <input type="number" step="0.01"
                                            name="road_types[{{ $index }}][length]"
                                            value="{{ old("road_types.$index.length", $existingLength) }}"
                                            placeholder="0.00"
                                            class="w-24 rounded-sm border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-1 px-2 text-xs">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-4 pt-4">
                    <a href="{{ route('resource-mapping.index') }}"
                        class="text-xs font-bold text-gray-400 hover:text-gray-600 uppercase tracking-widest">{{ __('Discard') }}</a>
                    <button type="submit"
                        class="px-8 py-3 bg-blue-600 text-white rounded-sm font-bold text-xs uppercase tracking-widest hover:bg-blue-700 shadow-md transition-all">
                        {{ isset($resourceMapping) ? __('Update Settings') : __('Save Mapping') }}
                    </button>
                </div>
        </form>

        @push('scripts')
            <script>
                async function fetchToles(wardId) {
                    const toleSelect = document.getElementById('tole_id');
                    toleSelect.innerHTML = '<option value="">{{ __('Loading...') }}</option>';

                    if (!wardId) {
                        toleSelect.innerHTML = '<option value="">{{ __('Select Tole') }}</option>';
                        return;
                    }

                    try {
                        const response = await fetch(`{{ route('toles.by.ward') }}?ward_id=${wardId}`);
                        const data = await response.json();

                        toleSelect.innerHTML = '<option value="">{{ __('Select Tole') }}</option>';
                        data.forEach(tole => {
                            const option = document.createElement('option');
                            option.value = tole.id;
                            option.textContent = tole.name;
                            @if (isset($resourceMapping))
                                if (tole.id == "{{ $resourceMapping->tole_id }}") option.selected = true;
                            @endif
                            toleSelect.appendChild(option);
                        });
                    } catch (error) {
                        console.error('Error fetching toles:', error);
                        toleSelect.innerHTML = '<option value="">{{ __('Error loading toles') }}</option>';
                    }
                }

                // Initial fetch if editing or old input exists
                @if (isset($resourceMapping) || old('ward_id'))
                    fetchToles("{{ old('ward_id', $resourceMapping->ward_id ?? '') }}");
                @endif
            </script>
        @endpush
    </div>
</x-app-layout>
