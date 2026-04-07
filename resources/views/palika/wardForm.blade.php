<x-app-layout>
    <div class="py-12 sm:py-24 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 ">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                    {{ isset($ward) ? __('Edit Ward') . ': ' . $ward->name : __('Add New Ward') }}
                </h2>
                <p class="mt-1 text-xs text-gray-500">
                    {{ __('Manage ward information and officials efficiently.') }}
                </p>
            </div>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-6 rounded-sm bg-red-50 p-4 border border-red-100 shadow-sm ">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-xs font-bold text-red-800 uppercase tracking-wider">
                            {{ __('Submission Errors') }}
                        </h3>
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

        <form action="{{ isset($ward) ? route('wards.update', $ward->id) : route('wards.store') }}" method="POST"
            enctype="multipart/form-data" class="space-y-6 ">
            @csrf
            @if (isset($ward))
                @method('PUT')
            @endif

            <!-- Core Details Grid -->
            <div class="bg-white  shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Ward Number') }}
                                <span class="text-red-500">*</span></label>
                            <input type="number" name="ward_no" value="{{ old('ward_no', $ward->ward_no ?? '') }}"
                                required
                                class="block w-full rounded- border-gray-200 bg-gray-50/50 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-sm transition-all">
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Ward Name') }}
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="ward_name" value="{{ old('name', $ward->name ?? '') }}" required
                                class="block w-full rounded- border-gray-200 bg-gray-50/50 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-sm transition-all">
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Location') }}
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="location" id="ward_location" value="{{ old('location', $ward->location ?? '') }}"
                                required
                                class="block w-full rounded-sm border-gray-200 bg-gray-50/50 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-sm transition-all">
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Contact Number') }}</label>
                            <input type="text" name="contact_number"
                                value="{{ old('contact_number', $ward->contact_number ?? '') }}"
                                class="block w-full rounded- border-gray-200 bg-gray-50/50 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-sm transition-all">
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Email Address') }}</label>
                            <input type="email" name="email" value="{{ old('email', $ward->email ?? '') }}"
                                class="block w-full rounded- border-gray-200 bg-gray-50/50 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-sm transition-all"
                                placeholder="ward@example.com">
                        </div>

                        <div class="lg:col-span-2">
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Description') }}</label>
                            <textarea name="description" id="ward_description" rows="2"
                                class="block w-full rounded- border-gray-200 bg-gray-50/50 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-sm transition-all placeholder:text-gray-300">{{ old('description', $ward->description ?? '') }}</textarea>
                        </div>

                        <!-- Ward Location Map -->
                        <div class="sm:col-span-2 lg:col-span-3">
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">{{ __('Ward Location') }}</label>
                            <button type="button" id="openMapBtn"
                                class="w-full px-4 py-3 border-2 border-dashed border-blue-300 rounded-sm bg-blue-50 hover:bg-blue-100 transition-colors text-blue-700 font-medium flex items-center justify-center gap-2">
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
                                    <label
                                        class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 block">{{ __('Latitude') }}</label>
                                    <div
                                        class="flex items-center gap-2 bg-blue-50 border border-blue-200 rounded-sm px-3 py-2">
                                        <span class="text-xs font-mono text-blue-900"
                                            id="latDisplay">{{ __('Not set') }}</span>
                                    </div>
                                    <input type="hidden" name="latitude" id="latitude"
                                        value="{{ old('latitude', $ward->latitude ?? '') }}">
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 block">{{ __('Longitude') }}</label>
                                    <div
                                        class="flex items-center gap-2 bg-blue-50 border border-blue-200 rounded-sm px-3 py-2">
                                        <span class="text-xs font-mono text-blue-900"
                                            id="lonDisplay">{{ __('Not set') }}</span>
                                    </div>
                                    <input type="hidden" name="longitude" id="longitude"
                                        value="{{ old('longitude', $ward->longitude ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Building Photo (Ultra Hub) -->
                        <div class="sm:col-span-2 lg:col-span-3">
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">{{ __('Ward Building Photo') }}
                                <span class="text-red-500">*</span></label>
                            <div
                                class="flex items-center gap-5 bg-gray-50/50 p-3 rounded-sm border border-dashed border-gray-200">
                                <div
                                    class="relative h-16 w-32 rounded- overflow-hidden bg-white border border-gray-100 shadow-inner flex items-center justify-center group shrink-0">
                                    <img id="photo-preview"
                                        src="{{ isset($ward) && $ward->building_photo ? Storage::disk('public')->url($ward->building_photo) : '' }}"
                                        class="{{ isset($ward) && $ward->building_photo ? '' : 'hidden' }} h-full w-full object-cover">
                                    <div id="photo-placeholder"
                                        class="{{ isset($ward) && $ward->building_photo ? 'hidden' : '' }} text-gray-300">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label
                                        class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded- font-bold text-[10px] uppercase cursor-pointer hover:bg-blue-100 transition-all border border-blue-100">
                                        <svg class="mr-1.5 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        {{ __('Change') }}
                                        <input type="file" name="building_photo" id="building_photo"
                                            accept="image/*" class="hidden" onchange="previewPhoto(this)"
                                            {{ isset($ward) ? '' : 'required' }}>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Members Section (Responsive Two-Column Grid) -->
            <div class="bg-white shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 sm:p-6">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-50">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('Officials Directory') }}</h3>
                        </div>
                        <button type="button" onclick="addMemberRow()"
                            class="inline-flex items-center px-3 py-1.5 bg-gray-900 text-white rounded- font-bold text-[11px] uppercase hover:bg-gray-800 transition-all shadow-sm">
                            <svg class="mr-1.5 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Add Staff') }}
                        </button>
                    </div>

                    <!-- Responsive Grid Container -->
                    <div id="memberContainer" class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-left">
                        <!-- Individual official cards via JS -->
                    </div>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="flex items-center justify-end gap-5 pt-2">
                <a href="{{ route('palika.index') }}"
                    class="text-[11px] font-bold text-gray-400 hover:text-gray-600 transition-colors uppercase tracking-widest">
                    {{ __('Discard Changes') }}
                </a>
                <button type="submit"
                    class="inline-flex justify-center px-8 py-3 bg-blue-600 border border-transparent shadow-lg text-sm font-bold rounded-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:-translate-y-0.5 active:translate-y-0 uppercase tracking-wider">
                    {{ isset($ward) ? __('Update Settings') : __('Save Ward') }}
                </button>
            </div>
        </form>

        <!-- Map Modal -->
        <div id="mapModal"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-end md:items-center justify-center p-0 md:p-4">
            <div
                class="bg-white rounded-t-lg md:rounded-lg shadow-2xl w-full h-[95vh] md:h-[85vh] md:max-w-5xl flex flex-col">
                <!-- Modal Header -->
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Select Ward Location') }}</h3>
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
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
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
                        class="flex items-center gap-2 px-4 py-2 text-sm text-blue-600 hover:text-blue-700 font-medium hover:bg-blue-50 rounded-sm transition-colors">
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
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-sm transition-colors">
                            {{ __('Cancel') }}
                        </button>
                        <button type="button" id="confirmLocationBtn"
                            class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 rounded-sm transition-all shadow-sm hover:shadow-md">
                            {{ __('Confirm Location') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <!-- Custom Transliteration using Google Input Tools API -->
            <script src="{{ asset('js/transliteration.js') }}"></script>
            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    const appLocale = '{{ app()->getLocale() }}';
                    const isNpLocale = (appLocale === 'np');
                    
                    // Initialize the global transliteration logic
                    if (typeof window.initTransliteration === 'function') {
                        window.initTransliteration(isNpLocale);
                    }
                    
                    // Initialize static fields
                    const staticIds = ['ward_name', 'ward_location', 'ward_description'];
                    staticIds.forEach(id => {
                        if (typeof window.enableTransliteration === 'function') {
                            window.enableTransliteration(id);
                        }
                    });
                });
            </script>
            <script>
                const wardDesignations = @json($wardDesignations);
                const chairpersonId = wardDesignations.find(d =>
                    d.translations.some(t => t.name === 'Ward Chairperson' || t.name === 'वडा अध्यक्ष')
                )?.id;
                const memberDesignationId = wardDesignations.find(d =>
                    d.translations.some(t => t.name === 'Ward Member' || t.name === 'वडा सदस्य')
                )?.id;

                let memberIndex = 0;

                function previewPhoto(input) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('photo-preview').src = e.target.result;
                            document.getElementById('photo-preview').classList.remove('hidden');
                            document.getElementById('photo-placeholder').classList.add('hidden');
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                function previewMemberPhoto(input, index) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const preview = document.getElementById(`member-photo-preview-${index}`);
                            const placeholder = document.getElementById(`member-photo-placeholder-${index}`);
                            preview.src = e.target.result;
                            preview.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                function addMemberRow(selectedDesignationId = '', isLocked = false, name = '', photoUrl = '', id = '', email = '',
                    phoneNumber = '') {
                    const container = document.getElementById('memberContainer');
                    const row = document.createElement('div');
                    const currentIndex = memberIndex;
                    row.className =
                        'group flex items-center gap-3 p-3 bg-gray-50/30 rounded-2xl border border-gray-100 transition-all hover:bg-white hover:shadow-sm';

                    const desigOptions = wardDesignations.map(d => {
                        const translation = d.translations.find(t => t.locale === '{{ app()->getLocale() }}') || d
                            .translations[0];

                        const isPrimary = (d.id == chairpersonId || d.id == memberDesignationId);
                        const isDisabled = !isLocked && isPrimary ? 'disabled' : '';
                        const style = !isLocked && isPrimary ? 'style="display:none"' : '';

                        return `<option value="${d.id}" ${selectedDesignationId == d.id ? 'selected' : ''} ${isDisabled} ${style}>${translation.name}</option>`;
                    }).join('');

                    row.innerHTML = `
                        <input type="hidden" name="members[${currentIndex}][id]" value="${id}">
                        <input type="hidden" name="members[${currentIndex}][existing_photo]" value="${photoUrl ? photoUrl.replace('/storage/', '') : ''}">
                        
                    
                        <div class="relative shrink-0">
                            <label for="member_photo_${currentIndex}" class="block h-12 w-12 rounded-full border border-gray-200 bg-white shadow-sm flex items-center justify-center overflow-hidden cursor-pointer hover:border-blue-400 transition-all group/photo">
                                <img id="member-photo-preview-${currentIndex}" 
                                     src="${photoUrl ? photoUrl : ''}" 
                                     class="${photoUrl ? '' : 'hidden'} h-full w-full object-cover">
                                <div id="member-photo-placeholder-${currentIndex}" class="${photoUrl ? 'hidden' : ''} text-gray-200 group-hover/photo:text-blue-300 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover/photo:opacity-100 transition-opacity">
                                    <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    </svg>
                                </div>
                            </label>
                            <input type="file" name="members[${currentIndex}][photo]" id="member_photo_${currentIndex}" 
                                   accept="image/*" class="hidden" onchange="previewMemberPhoto(this, ${currentIndex})">
                        </div>

                       
                        <div class="flex-1 flex flex-col gap-1.5 min-w-0">
                            <div class="w-full">
                                <select name="members[${currentIndex}][ward_designation_id]" required ${isLocked ? 'disabled' : ''}
                                    class="block w-full rounded- border-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-[11px] font-bold text-blue-600 bg-white py-1 px-2.5 transition-all truncate">
                                    <option value="">{{ __('Select Designation') }}</option>
                                    ${desigOptions}
                                </select>
                                ${isLocked ? `<input type="hidden" name="members[${currentIndex}][ward_designation_id]" value="${selectedDesignationId}">` : ''}
                            </div>
                            <div class="w-full relative">
                                <input type="text" id="member_name_${currentIndex}" name="members[${currentIndex}][name]" value="${name}" required
                                    class="block w-full rounded- border-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs bg-white py-1 px-2.5 transition-all"
                                    placeholder="{{ __('Full Name...') }}">
                            </div>
                            <div class="w-full flex gap-2">
                                <input type="email" name="members[${currentIndex}][email]" value="${email}"
                                    class="flex-1 rounded- border-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs bg-white py-1 px-2.5 transition-all"
                                    placeholder="{{ __('Email (optional)') }}">
                                <input type="tel" name="members[${currentIndex}][phone_number]" value="${phoneNumber}"
                                    class="w-32 rounded- border-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs bg-white py-1 px-2.5 transition-all"
                                    placeholder="{{ __('Phone (optional)') }}">
                            </div>
                        </div>

                        <div class="shrink-0 flex items-center justify-end">
                            ${!isLocked ? `
                                                        <button type="button" onclick="this.closest('.group').remove()" 
                                                            class="p-2 text-gray-300 hover:text-red-500 transition-all group-hover:bg-red-50 rounded-">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    ` : `
                                                        <div class="p-2 opacity-0 pointer-events-none">
                                                            <div class="h-4 w-4"></div>
                                                        </div>
                                                    `}
                        </div>
                    `;
                    container.appendChild(row);
                    if (typeof window.enableTransliteration === 'function') {
                        window.enableTransliteration(`member_name_${currentIndex}`);
                    }
                    memberIndex++;
                }

                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.querySelector('form');
                    form.addEventListener('submit', function(e) {
                        const inputs = form.querySelectorAll('input[name$="[ward_designation_id]"]');
                        const selects = form.querySelectorAll(
                            'select[name$="[ward_designation_id]"]:not(:disabled)');

                        let chairCount = 0;
                        let memberCount = 0;

                        // Check hidden inputs (locked rows)
                        inputs.forEach(input => {
                            if (input.value == chairpersonId) chairCount++;
                            if (input.value == memberDesignationId) memberCount++;
                        });

                        // Check active selects (staff rows)
                        selects.forEach(select => {
                            if (select.value == chairpersonId) chairCount++;
                            if (select.value == memberDesignationId) memberCount++;
                        });

                        if (chairCount !== 1) {
                            e.preventDefault();
                            alert("{{ __('Error: There must be exactly one Ward Chairperson.') }}");
                            return false;
                        }

                        if (memberCount !== 4) {
                            e.preventDefault();
                            alert("{{ __('Error: There must be exactly four Ward Members.') }}");
                            return false;
                        }
                    });

                    const existingMembers = @json(isset($ward) ? $ward->members : []);

                    if (existingMembers.length === 0) {
                        // Default setup for new ward
                        addMemberRow(chairpersonId, true);
                        for (let i = 0; i < 4; i++) {
                            addMemberRow(memberDesignationId, true);
                        }
                    } else {
                        // Setup for editing existing ward
                        existingMembers.forEach(m => {
                            const isLocked = (m.ward_designation_id == chairpersonId || m.ward_designation_id ==
                                memberDesignationId);
                            const photoUrl = m.photo ? `/storage/${m.photo}` : '';
                            addMemberRow(m.ward_designation_id, isLocked, m.name, photoUrl, m.id, m.email || '', m
                                .phone_number || '');
                        });
                    }
                });
            </script>

            <!-- Leaflet Map Libraries -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
            <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
            <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

            <!-- Map Management Script -->
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
            </script>
        @endpush
    </div>
</x-app-layout>
