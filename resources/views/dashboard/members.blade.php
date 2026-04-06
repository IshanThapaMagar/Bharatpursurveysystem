<x-app-layout>
    <div class="py-24">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ $label ?? 'सदस्यहरूको सूची' }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1" id="result-count">
                        जम्मा <span id="visible-count" class="font-semibold text-indigo-600">{{ count($members) }}</span>
                        / {{ count($members) }} सदस्यहरू
                    </p>
                </div>
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors duration-150 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    ड्यासबोर्डमा फर्कनुहोस्
                </a>
            </div>

            {{-- Filter Panel --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
                <div class="flex flex-wrap gap-6 items-end">

                    {{-- Search Box --}}
                    <div class="flex-1 min-w-[220px]">

                        <div class="relative">
                            <input id="search-input" type="text" placeholder="नाम, लिङ्ग, शैक्षिक स्तर..."
                                class="w-full border border-gray-200 rounded-lg pl-10 pr-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent shadow-sm transition" />
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Age Group Selector --}}
                    <div class="flex-1 min-w-[220px]">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            उमेर समूह
                        </label>
                        <select id="age-group-selector"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent shadow-sm transition bg-white">
                            <option value="all" {{ request('range_min') === null ? 'selected' : '' }}>सबै उमेर समूह
                            </option>
                            <option value="0-5"
                                {{ request('range_min') === '0' && request('range_max') === '5' ? 'selected' : '' }}>
                                शिशु (०-५)</option>
                            <option value="6-16"
                                {{ request('range_min') === '6' && request('range_max') === '16' ? 'selected' : '' }}>
                                बालक (६-१६)</option>
                            <option value="17-32"
                                {{ request('range_min') === '17' && request('range_max') === '32' ? 'selected' : '' }}>
                                युवा (१७-३२)</option>
                            <option value="33-54"
                                {{ request('range_min') === '33' && request('range_max') === '54' ? 'selected' : '' }}>
                                वयस्क (३३-५४)</option>
                            <option value="55-65"
                                {{ request('range_min') === '55' && request('range_max') === '65' ? 'selected' : '' }}>
                                वृद्ध (५५-६५)</option>
                            <option value="65+"
                                {{ request('range_min') === '65' && request('range_max') === '120' ? 'selected' : '' }}>
                                ज्येष्ठ नागरिक (६५ - माथि)</option>
                        </select>
                    </div>

                    <div class="flex-1 min-w-[280px]">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            उमेर सीमा:
                            <span id="age-min-display" class="text-indigo-600">0</span>
                            –
                            <span id="age-max-display" class="text-indigo-600">120</span>
                            वर्ष
                        </label>
                        <div class="relative h-6 flex items-center" id="age-slider-container">

                            <div class="absolute w-full h-2 bg-gray-200 rounded-full" id="slider-track"></div>

                            <div class="absolute h-2 bg-indigo-500 rounded-full" id="slider-range"></div>

                            <input id="age-min" type="range" min="0" max="120" value="0"
                                step="1"
                                class="age-thumb absolute w-full appearance-none bg-transparent pointer-events-none" />

                            <input id="age-max" type="range" min="0" max="120" value="120"
                                step="1"
                                class="age-thumb absolute w-full appearance-none bg-transparent pointer-events-none" />
                        </div>
                        <div id="slider-labels" class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>0</span><span>30</span><span>60</span><span>90</span><span>120</span>
                        </div>
                    </div>


                    <div class="flex-shrink-0">
                        <button id="reset-filters"
                            class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors duration-150 border border-gray-200">
                            ↺ रिसेट गर्नुहोस्
                        </button>
                    </div>

                </div>
            </div>


            <div class="bg-white overflow-hidden shadow-sm border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-sm text-left" id="members-table">
                        <thead class="bg-gray-50 border-b border-gray-200 sticky top-0 z-10">
                            <tr>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase text-xs tracking-wider">
                                    सि.नं.</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase text-xs tracking-wider">पूरा
                                    नाम</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase text-xs tracking-wider">उमेर
                                </th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase text-xs tracking-wider">लिङ्ग
                                </th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase text-xs tracking-wider">
                                    वैवाहिक स्थिति</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase text-xs tracking-wider">
                                    शैक्षिक स्तर</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase text-xs tracking-wider">
                                    स्वास्थ्य अवस्था</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase text-xs tracking-wider">
                                    संस्था प्रकार</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase text-xs tracking-wider">
                                    अपाङ्गता</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase text-xs tracking-wider">जन्म
                                    मिति (बि.सं.)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50" id="members-tbody">
                            @forelse($members as $index => $m)
                                <tr class="member-row hover:bg-indigo-50/40 transition-colors duration-100"
                                    data-age="{{ $m->age ?? '' }}"
                                    data-search="{{ strtolower(
                                        implode(
                                            ' ',
                                            array_filter([
                                                $m->full_name ?? '',
                                                $m->gender ?? '',
                                                $m->marital_status ?? '',
                                                $m->education_level ?? '',
                                                $m->health_status ?? '',
                                                $m->institution_type ?? '',
                                                $m->disability ?? '',
                                            ]),
                                        ),
                                    ) }}">
                                    <td class="py-3 px-4 text-gray-400 text-xs row-index">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4 font-medium text-gray-800">{{ $m->full_name ?? '—' }}</td>
                                    <td class="py-3 px-4">
                                        @if ($m->age)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                                {{ $m->age }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">{{ $m->gender ?? '—' }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $m->marital_status ?? '—' }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $m->education_level ?? '—' }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $m->health_status ?? '—' }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $m->institution_type ?? '—' }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $m->disability ?? '—' }}</td>
                                    <td class="py-3 px-4 text-gray-500 text-xs">{{ $m->dob_bs ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-16 text-center text-gray-400">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="text-sm">यस समूहमा कुनै सदस्य फेला परेन।</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- No results message (shown by JS) --}}
                    <div id="no-results" class="hidden py-16 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-medium">कुनै सदस्य भेटिएन। फिल्टर परिवर्तन गर्नुहोस्।</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Dual-Thumb Slider CSS + Filter JS (inline so AJAX navigation picks it up) --}}
    <style>
        /* Dual-thumb range slider — hide default track, style thumbs only */
        .age-thumb {
            height: 0;
            z-index: 3;
        }

        .age-thumb::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #4f46e5;
            border: 3px solid #ffffff;
            box-shadow: 0 1px 4px rgba(79, 70, 229, 0.4);
            cursor: pointer;
            pointer-events: all;
            transition: transform 0.15s, box-shadow 0.15s;
        }

        .age-thumb::-webkit-slider-thumb:hover {
            transform: scale(1.15);
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.5);
        }

        .age-thumb::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #4f46e5;
            border: 3px solid #ffffff;
            box-shadow: 0 1px 4px rgba(79, 70, 229, 0.4);
            cursor: pointer;
            pointer-events: all;
        }

        /* Make the min thumb sit on top when dragging past max */
        #age-min {
            z-index: 4;
        }

        #age-max {
            z-index: 5;
        }

        /* Styling for disabled age group selector */
        #age-group-selector:disabled {
            background-color: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
            opacity: 0.7;
        }
    </style>

    <script>
        (function() {
            const totalCount = {{ count($members) }};
            const minInput = document.getElementById('age-min');
            const maxInput = document.getElementById('age-max');
            const minDisplay = document.getElementById('age-min-display');
            const maxDisplay = document.getElementById('age-max-display');
            const rangeBar = document.getElementById('slider-range');
            const searchInput = document.getElementById('search-input');
            const ageGroupSelector = document.getElementById('age-group-selector');
            const noResults = document.getElementById('no-results');
            const visibleSpan = document.getElementById('visible-count');
            const resetBtn = document.getElementById('reset-filters');
            const sliderLabels = document.getElementById('slider-labels');
            const rows = Array.from(document.querySelectorAll('.member-row'));

            // ── Age Group Definitions ────────────────────────────────────────────
            const ageGroups = {
                'all': {
                    label: 'सबै',
                    min: 0,
                    max: 120
                },
                '0-5': {
                    label: 'शिशु (०-५)',
                    min: 0,
                    max: 5
                },
                '6-16': {
                    label: 'बालक (६-१६)',
                    min: 6,
                    max: 16
                },
                '17-32': {
                    label: 'युवा (१७-३२)',
                    min: 17,
                    max: 32
                },
                '33-54': {
                    label: 'वयस्क (३३-५४)',
                    min: 33,
                    max: 54
                },
                '55-65': {
                    label: 'वृद्ध (५५-६५)',
                    min: 55,
                    max: 65
                },
                '65+': {
                    label: 'ज्येष्ठ नागरिक (६५ - माथि)',
                    min: 65,
                    max: 120
                }
            };

            // ── Generate slider labels based on range ────────────────────────────
            function generateSliderLabels(min, max) {
                const range = max - min;
                const step = range / 4;
                let labels = [min.toString()];
                for (let i = 1; i < 4; i++) {
                    labels.push(Math.round(min + (step * i)).toString());
                }
                labels.push(max.toString());
                return labels;
            }

            // ── Update slider range based on age group ───────────────────────────
            function updateAgeGroupRange() {
                const selectedGroup = ageGroupSelector.value;
                const group = ageGroups[selectedGroup];

                minInput.min = group.min;
                minInput.max = group.max;
                maxInput.min = group.min;
                maxInput.max = group.max;

                minInput.value = group.min;
                maxInput.value = group.max;

                // Update slider labels
                const labels = generateSliderLabels(group.min, group.max);
                sliderLabels.innerHTML = labels.map(label => `<span>${label}</span>`).join('');

                updateSliderUI();
                applyFilters();
            }

            // ── Age group selector change ────────────────────────────────────────
            ageGroupSelector.addEventListener('change', updateAgeGroupRange);

            // ── Slider range highlight ──────────────────────────────────────────
            function updateSliderUI() {
                const min = parseInt(minInput.value);
                const max = parseInt(maxInput.value);
                const sliderMin = parseInt(minInput.min);
                const sliderMax = parseInt(minInput.max);
                const range = sliderMax - sliderMin;
                const leftPct = ((min - sliderMin) / range) * 100;
                const rightPct = ((max - sliderMin) / range) * 100;
                rangeBar.style.left = leftPct + '%';
                rangeBar.style.width = (rightPct - leftPct) + '%';
                minDisplay.textContent = min;
                maxDisplay.textContent = max;
            }

            // ── Prevent thumbs crossing ─────────────────────────────────────────
            minInput.addEventListener('input', function() {
                if (parseInt(this.value) > parseInt(maxInput.value)) {
                    this.value = maxInput.value;
                }
                updateSliderUI();
                applyFilters();
            });

            maxInput.addEventListener('input', function() {
                if (parseInt(this.value) < parseInt(minInput.value)) {
                    this.value = minInput.value;
                }
                updateSliderUI();
                applyFilters();
            });

            // ── Search ──────────────────────────────────────────────────────────
            searchInput.addEventListener('input', applyFilters);

            // ── Core filter logic ───────────────────────────────────────────────
            function applyFilters() {
                const ageMin = parseInt(minInput.value);
                const ageMax = parseInt(maxInput.value);
                const searchVal = searchInput.value.trim().toLowerCase();
                let visible = 0;
                let serialNo = 1;

                rows.forEach(function(row) {
                    const ageRaw = row.dataset.age;
                    const age = ageRaw === '' ? null : parseInt(ageRaw);
                    const searchStr = row.dataset.search || '';

                    // Age filter: skip rows with no age only if slider is non-zero
                    const ageOk = (age === null) ?
                        (ageMin === 0) // show unknowns only at default
                        :
                        (age >= ageMin && age <= ageMax);

                    // Search filter
                    const searchOk = searchVal === '' || searchStr.includes(searchVal);

                    if (ageOk && searchOk) {
                        row.style.display = '';
                        // Re-number serial
                        const idx = row.querySelector('.row-index');
                        if (idx) idx.textContent = serialNo++;
                        visible++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                visibleSpan.textContent = visible;

                // Toggle empty state
                if (noResults) {
                    noResults.classList.toggle('hidden', visible > 0);
                }
            }

            // ── Reset ───────────────────────────────────────────────────────────
            resetBtn.addEventListener('click', function() {
                const urlParams = new URLSearchParams(window.location.search);
                const filterType = urlParams.get('filter_type');
                const rangeMin = urlParams.get('range_min');
                const rangeMax = urlParams.get('range_max');

                // Validate if range_min and range_max match a valid age group
                let isValidAgeGroup = false;
                if (filterType === 'age_group' && rangeMin !== null && rangeMax !== null) {
                    for (const [key, group] of Object.entries(ageGroups)) {
                        if (group.min === parseInt(rangeMin) && group.max === parseInt(rangeMax)) {
                            isValidAgeGroup = true;
                            break;
                        }
                    }
                }

                // If filtering by valid age_group, only reset search and slider within group range
                if (filterType === 'age_group' && isValidAgeGroup) {
                    searchInput.value = '';
                    const selectedGroup = ageGroupSelector.value;
                    const group = ageGroups[selectedGroup];
                    minInput.value = group.min;
                    maxInput.value = group.max;
                    updateSliderUI();
                    applyFilters();
                } else {
                    // Full reset: all filters
                    ageGroupSelector.value = 'all';
                    searchInput.value = '';
                    updateAgeGroupRange();
                }
            });

            // ── Boot ────────────────────────────────────────────────────────────
            // Initialize from pre-selected age group and disable if filter_type=age_group
            function initializeSlider() {
                const urlParams = new URLSearchParams(window.location.search);
                const filterType = urlParams.get('filter_type');
                const rangeMin = urlParams.get('range_min');
                const rangeMax = urlParams.get('range_max');

                const selectedValue = ageGroupSelector.value;

                // Validate if range_min and range_max match a valid age group
                let isValidAgeGroup = false;
                if (filterType === 'age_group' && rangeMin !== null && rangeMax !== null) {
                    for (const [key, group] of Object.entries(ageGroups)) {
                        if (group.min === parseInt(rangeMin) && group.max === parseInt(rangeMax)) {
                            isValidAgeGroup = true;
                            break;
                        }
                    }
                }

                // Only disable the dropdown if it's a valid age group filter
                if (filterType === 'age_group' && isValidAgeGroup) {
                    ageGroupSelector.disabled = true;
                    ageGroupSelector.classList.add('opacity-50', 'cursor-not-allowed');
                }

                if (selectedValue !== 'all') {
                    updateAgeGroupRange();
                } else {
                    updateSliderUI();
                    applyFilters();
                }
            }

            initializeSlider();

            // Handle AJAX re-init
            window.addEventListener('reinitialize-forms', function() {
                updateSliderUI();
                applyFilters();
            });
        })();
    </script>
</x-app-layout>
