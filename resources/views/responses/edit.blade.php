<x-app-layout>
    @push('styles')
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap');

            .edit-page * { font-family: 'Outfit', sans-serif; }

            /* Tab nav */
            .tab-btn {
                padding: 0.65rem 1.25rem;
                font-size: 0.8125rem;
                font-weight: 500;
                white-space: nowrap;
                border-bottom: 2px solid transparent;
                color: #6b7280;
                cursor: pointer;
                transition: color .2s, border-color .2s, background .2s;
            }
            .tab-btn:hover  { color: #4338ca; background: #eef2ff; }
            .tab-btn.active { color: #4338ca; border-color: #4338ca; background: #eef2ff50; font-weight: 600; }

            /* Form controls */
            .field-label {
                display: block;
                font-size: 0.75rem;
                font-weight: 600;
                color: #374151;
                margin-bottom: 0.35rem;
                letter-spacing: .02em;
            }
            .ctrl {
                display: block;
                width: 100%;
                border: 1.5px solid #d1d5db;
                border-radius: 0.5rem;
                padding: 0.55rem 0.875rem;
                font-size: 0.875rem;
                color: #111827;
                background: #fff;
                transition: border-color .2s, box-shadow .2s;
                outline: none;
            }
            .ctrl:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }

            /* Option cards */
            .opt-card {
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
                padding: 0.75rem 1rem;
                border: 1.5px solid #e5e7eb;
                border-radius: 0.75rem;
                cursor: pointer;
                transition: border-color .2s, background .2s, box-shadow .2s;
                background: #fff;
            }
            .opt-card:hover { border-color: #a5b4fc; background: #f5f3ff; }
            .opt-card.selected-radio { border-color: #6366f1; background: #eef2ff; box-shadow: 0 2px 8px rgba(99,102,241,.12); }
            .opt-card.selected-check { border-color: #6366f1; background: #eef2ff; box-shadow: 0 2px 8px rgba(99,102,241,.12); }

            /* Scale */
            .scale-btn {
                min-width: 2.5rem;
                height: 2.5rem;
                border: 1.5px solid #d1d5db;
                border-radius: 0.5rem;
                background: #fff;
                font-size: 0.875rem;
                font-weight: 500;
                color: #374151;
                cursor: pointer;
                transition: background .2s, border-color .2s, color .2s;
            }
            .scale-btn:hover  { background: #eef2ff; border-color: #6366f1; color: #4338ca; }
            .scale-btn.active { background: #6366f1; border-color: #6366f1; color: #fff; box-shadow: 0 2px 6px rgba(99,102,241,.3); }

            /* File / location display */
            .asset-pill {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                padding: 0.4rem 0.75rem;
                border-radius: 9999px;
                background: #f0f9ff;
                border: 1px solid #bae6fd;
                font-size: 0.8rem;
                color: #0369a1;
            }

            /* Section header badge */
            .section-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                padding: 0.25rem 0.7rem;
                border-radius: 9999px;
                background: #eef2ff;
                color: #4338ca;
                font-size: 0.75rem;
                font-weight: 600;
            }

            /* Question card */
            .q-card {
                border: 1px solid #e5e7eb;
                border-radius: 0.875rem;
                padding: 1.25rem 1.5rem;
                background: #fff;
                transition: box-shadow .2s;
            }
            .q-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.06); }
            .q-number {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 1.75rem;
                height: 1.75rem;
                border-radius: 50%;
                background: #eef2ff;
                color: #4338ca;
                font-size: 0.7rem;
                font-weight: 700;
                flex-shrink: 0;
            }

            /* Sticky save bar */
            .save-bar {
                position: sticky;
                bottom: 0;
                z-index: 40;
                background: rgba(255,255,255,.95);
                backdrop-filter: blur(8px);
                border-top: 1px solid #e5e7eb;
                padding: 0.875rem 1.5rem;
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 0.75rem;
            }

            /* Householder card */
            .hh-card {
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 1rem;
                overflow: hidden;
                box-shadow: 0 1px 4px rgba(0,0,0,.04);
            }
            .hh-card-header {
                padding: 1rem 1.5rem;
                background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 100%);
                border-bottom: 1px solid #e5e7eb;
                display: flex;
                align-items: center;
                justify-content: space-between;
                cursor: pointer;
                user-select: none;
            }
            .hh-card-header:hover { background: linear-gradient(135deg, #e0e7ff 0%, #ede9fe 100%); }
            .hh-body { padding: 1.5rem; }
            .field-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 1rem 1.5rem;
            }
            .photo-ring {
                width: 5rem; height: 5rem;
                border-radius: 9999px;
                object-fit: cover;
                border: 2px solid #c7d2fe;
                box-shadow: 0 2px 8px rgba(99,102,241,.15);
            }
            .photo-placeholder {
                width: 5rem; height: 5rem;
                border-radius: 9999px;
                background: #eef2ff;
                border: 2px dashed #a5b4fc;
                display: flex; align-items: center; justify-content: center;
            }

            @media (max-width: 640px) {
                .q-card { padding: 1rem; }
                .field-grid { grid-template-columns: 1fr; }
            }
        </style>
    @endpush

    <div class="edit-page py-28">
        <div class="px-4 sm:px-6 lg:px-8 max-w-[100%]">

            {{-- ── Page header ── --}}
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <a href="{{ route('survey-responses.index') }}"
                        class="inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 font-medium mb-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        विवरण सूचीमा फर्कनुहोस्
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">सर्वेक्षण उत्तर सम्पादन</h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        घरधुरी:
                        <span class="font-semibold text-gray-700">
                            {{ $household?->householder_name ?? '—' }}
                        </span>
                        @if($household?->house_number)
                            &nbsp;·&nbsp; घर नं. {{ $household->house_number }}
                        @endif
                        @if($household?->tole?->name)
                            &nbsp;·&nbsp; {{ $household->tole->name }}
                        @endif
                    </p>
                </div>

                {{-- Submission info badge --}}
                @if($response->submitted_at)
                    <div class="inline-flex items-center gap-2 text-xs bg-green-50 text-green-700 border border-green-200 rounded-full px-3 py-1.5 font-medium">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ $response->submitted_at->format('Y-m-d H:i') }} मा पेश गरिएको
                    </div>
                @endif
            </div>

            @if (session('success'))
                <div class="rounded-xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                    <p class="font-semibold mb-1">यी त्रुटिहरू ठीक गर्नुहोस्:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ── Main edit form ── --}}
                <form method="POST" action="{{ route('survey-responses.update', $response->id) }}"
                    id="edit-response-form" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- Hidden field so controller knows this is a full-form submit --}}
                    <input type="hidden" name="response_id" value="{{ $response->id }}">

                    {{-- ══ HOUSEHOLDER INFO CARD ══ --}}
                    <div class="hh-card" x-data="{ open: true }">
                        <div class="hh-card-header" @click="open = !open">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-sm font-bold text-gray-800">घरधुरी विवरण</h2>
                                    <p class="text-xs text-gray-500">घरमुखीको व्यक्तिगत र सम्पर्क जानकारी</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        <div class="hh-body" x-show="open" x-transition>

                            {{-- Profile photo row --}}
                            <div class="flex items-center gap-5 mb-6 pb-5 border-b border-gray-100">
                                <div id="photo-preview-wrap">
                                    @if($household?->profile_photo)
                                        <img id="photo-preview"
                                            src="{{ Storage::url($household->profile_photo) }}"
                                            alt="{{ $household->householder_name }}"
                                            class="photo-ring">
                                    @else
                                        <div class="photo-placeholder" id="photo-preview">
                                            <svg class="w-7 h-7 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <label class="field-label">प्रोफाइल फोटो</label>
                                    <input type="file"
                                        name="householder[profile_photo]"
                                        id="hh-photo-input"
                                        accept="image/jpeg,image/png,image/jpg"
                                        onchange="previewHHPhoto(this)"
                                        class="ctrl py-1.5 text-sm text-gray-600
                                            file:mr-3 file:py-1 file:px-3 file:rounded-full file:border-0
                                            file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600
                                            hover:file:bg-indigo-100"
                                        style="max-width:22rem;">
                                    <p class="text-xs text-gray-400 mt-1">JPG / PNG · अधिकतम 2MB</p>
                                </div>
                            </div>

                            {{-- Main fields grid --}}
                            <div class="field-grid">

                                {{-- Householder Name --}}
                                <div class="col-span-full sm:col-span-2" style="grid-column: span 2;">
                                    <label for="hh-name" class="field-label">
                                        घरमुखीको नाम <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                        id="hh-name"
                                        name="householder[householder_name]"
                                        value="{{ old('householder.householder_name', $household?->householder_name) }}"
                                        required
                                        class="ctrl">
                                </div>

                                {{-- Father's Name --}}
                                <div>
                                    <label for="hh-father" class="field-label">बुवाको नाम</label>
                                    <input type="text"
                                        id="hh-father"
                                        name="householder[father_name]"
                                        value="{{ old('householder.father_name', $household?->father_name) }}"
                                        class="ctrl">
                                </div>

                                {{-- Mother's Name --}}
                                <div>
                                    <label for="hh-mother" class="field-label">आमाको नाम</label>
                                    <input type="text"
                                        id="hh-mother"
                                        name="householder[mother_name]"
                                        value="{{ old('householder.mother_name', $household?->mother_name) }}"
                                        class="ctrl">
                                </div>

                                {{-- Caste --}}
                                <div>
                                    <label for="hh-caste" class="field-label">जाति</label>
                                    <select id="hh-caste" name="householder[caste_id]" class="ctrl">
                                        <option value="">— छान्नुहोस् —</option>
                                        @foreach($castes as $caste)
                                            <option value="{{ $caste->id }}"
                                                {{ old('householder.caste_id', $household?->caste_id) == $caste->id ? 'selected' : '' }}>
                                                {{ $caste->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Mother Tongue --}}
                                <div>
                                    <label for="hh-tongue" class="field-label">पुखौँली भाषा</label>
                                    <select id="hh-tongue" name="householder[mother_tongue_id]" class="ctrl">
                                        <option value="">— छान्नुहोस् —</option>
                                        @foreach($motherTongues as $mt)
                                            <option value="{{ $mt->id }}"
                                                {{ old('householder.mother_tongue_id', $household?->mother_tongue_id) == $mt->id ? 'selected' : '' }}>
                                                {{ $mt->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tole --}}
                                <div>
                                    <label for="hh-tole" class="field-label">टोल</label>
                                    <select id="hh-tole" name="householder[tole_id]" class="ctrl">
                                        <option value="">— छान्नुहोस् —</option>
                                        @foreach($toles as $tole)
                                            <option value="{{ $tole->id }}"
                                                {{ old('householder.tole_id', $household?->tole_id) == $tole->id ? 'selected' : '' }}>
                                                {{ $tole->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Citizenship Permanent Address --}}
                                <div>
                                    <label for="hh-cpa" class="field-label">नागरिकता स्थायी ठेगाना</label>
                                    <select id="hh-cpa" name="householder[citizenship_permanent_address_id]" class="ctrl">
                                        <option value="">— छान्नुहोस् —</option>
                                        @foreach($citizenshipAddresses as $cpa)
                                            <option value="{{ $cpa->id }}"
                                                {{ old('householder.citizenship_permanent_address_id', $household?->citizenship_permanent_address_id) == $cpa->id ? 'selected' : '' }}>
                                                {{ $cpa->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- House Number --}}
                                <div>
                                    <label for="hh-house" class="field-label">घर नं.</label>
                                    <input type="text"
                                        id="hh-house"
                                        name="householder[house_number]"
                                        value="{{ old('householder.house_number', $household?->house_number) }}"
                                        class="ctrl">
                                </div>

                                {{-- Lot Number --}}
                                <div>
                                    <label for="hh-lot" class="field-label">कित्ता नं.</label>
                                    <input type="text"
                                        id="hh-lot"
                                        name="householder[lot_number]"
                                        value="{{ old('householder.lot_number', $household?->lot_number) }}"
                                        class="ctrl">
                                </div>

                                {{-- Phone Number --}}
                                <div>
                                    <label for="hh-phone" class="field-label">फोन नं.</label>
                                    <input type="tel"
                                        id="hh-phone"
                                        name="householder[phone_number]"
                                        value="{{ old('householder.phone_number', $household?->phone_number) }}"
                                        maxlength="10"
                                        class="ctrl">
                                </div>

                            </div>{{-- /.field-grid --}}
                        </div>{{-- /.hh-body --}}
                    </div>{{-- /.hh-card --}}

                    @if($sections->isNotEmpty())

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden"
                        x-data="{ activeTab: 0 }">

                        {{-- ── Tab Bar ── --}}
                        <div class="flex overflow-x-auto border-b border-gray-200 bg-gray-50/60">
                            @foreach ($sections as $si => $section)
                                <button type="button"
                                    class="tab-btn"
                                    :class="activeTab === {{ $si }} ? 'active' : ''"
                                    @click="activeTab = {{ $si }}">
                                    {{ $section->title }}
                                </button>
                            @endforeach
                        </div>

                        {{-- ── Section Panels ── --}}
                        @foreach ($sections as $si => $section)
                            <div x-show="activeTab === {{ $si }}"
                                x-cloak
                                class="p-6 sm:p-8 space-y-5">

                                {{-- Section header --}}
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="section-badge">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        खण्ड {{ $si + 1 }}
                                    </span>
                                    <h2 class="text-lg font-bold text-gray-800">{{ $section->title }}</h2>
                                </div>
                                @if($section->description)
                                    <p class="text-sm text-gray-500 italic -mt-3 mb-4">{{ $section->description }}</p>
                                @endif

                                @if($section->questions->isEmpty())
                                    <p class="text-center text-gray-400 py-10 text-sm">यस खण्डमा कुनै प्रश्न छैन।</p>
                                @else
                                    <div class="space-y-5">
                                        @foreach ($section->questions as $qi => $question)
                                            @php
                                                $typeName   = $question->inputType?->input_type_name ?? 'short_text';
                                                $prefill    = $question->prefill_value;
                                                $customIns  = $question->custom_inputs ?? [];
                                                $fieldName  = "answers[{$question->id}]";
                                            @endphp

                                            <div class="q-card" id="q-{{ $question->id }}">
                                                {{-- Question label --}}
                                                <div class="flex items-start gap-3 mb-4">
                                                    <span class="q-number">{{ $qi + 1 }}</span>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-semibold text-gray-900 leading-snug">
                                                            {{ $question->question_text }}
                                                            @if($question->answer_required)
                                                                <span class="text-red-500 ml-0.5">*</span>
                                                            @endif
                                                        </p>
                                                        @if($question->question_subtext)
                                                            <p class="text-xs text-gray-400 mt-0.5">{{ $question->question_subtext }}</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- ─── Input type rendering ─── --}}

                                                {{-- SHORT TEXT --}}
                                                @if($typeName === 'short_text')
                                                    <input type="text"
                                                        name="{{ $fieldName }}[answer_text]"
                                                        id="q{{ $question->id }}"
                                                        value="{{ old("{$fieldName}.answer_text", $prefill) }}"
                                                        {{ $question->answer_required ? 'required' : '' }}
                                                        placeholder="उत्तर लेख्नुहोस्"
                                                        class="ctrl">

                                                {{-- LONG TEXT --}}
                                                @elseif($typeName === 'long_text')
                                                    <textarea
                                                        name="{{ $fieldName }}[answer_text]"
                                                        id="q{{ $question->id }}"
                                                        rows="4"
                                                        {{ $question->answer_required ? 'required' : '' }}
                                                        placeholder="उत्तर लेख्नुहोस्"
                                                        class="ctrl">{{ old("{$fieldName}.answer_text", $prefill) }}</textarea>

                                                {{-- EMAIL --}}
                                                @elseif($typeName === 'email')
                                                    <input type="email"
                                                        name="{{ $fieldName }}[answer_text]"
                                                        id="q{{ $question->id }}"
                                                        value="{{ old("{$fieldName}.answer_text", $prefill) }}"
                                                        {{ $question->answer_required ? 'required' : '' }}
                                                        placeholder="email@example.com"
                                                        class="ctrl">

                                                {{-- NUMBER --}}
                                                @elseif($typeName === 'number')
                                                    <input type="number"
                                                        name="{{ $fieldName }}[answer_numeric]"
                                                        id="q{{ $question->id }}"
                                                        value="{{ old("{$fieldName}.answer_numeric", $prefill) }}"
                                                        step="0.01"
                                                        {{ $question->answer_required ? 'required' : '' }}
                                                        class="ctrl"
                                                        style="max-width: 20rem;">

                                                {{-- DATE --}}
                                                @elseif($typeName === 'date')
                                                    <input type="date"
                                                        name="{{ $fieldName }}[answer_text]"
                                                        id="q{{ $question->id }}"
                                                        value="{{ old("{$fieldName}.answer_text", $prefill) }}"
                                                        {{ $question->answer_required ? 'required' : '' }}
                                                        class="ctrl"
                                                        style="max-width: 16rem;">

                                                {{-- RADIO --}}
                                                @elseif($typeName === 'radio')
                                                    @php $selectedOptionId = old("{$fieldName}.question_option_id", (string)$prefill); @endphp
                                                    <div class="space-y-2.5" id="radio-group-{{ $question->id }}">
                                                        @foreach ($question->questionOptions as $opt)
                                                            @php
                                                                $isSelected = (string)$opt->id === $selectedOptionId;
                                                                $hasCustom  = $opt->optionChoice?->custom_input_type && $opt->optionChoice->custom_input_type !== 'none';
                                                                $customVal  = $customIns[$opt->id] ?? '';
                                                            @endphp
                                                            <label class="opt-card {{ $isSelected ? 'selected-radio' : '' }}"
                                                                onclick="
                                                                    document.querySelectorAll('#radio-group-{{ $question->id }} .opt-card').forEach(c => c.classList.remove('selected-radio'));
                                                                    this.classList.add('selected-radio');
                                                                    document.querySelectorAll('#radio-group-{{ $question->id }} .custom-inp').forEach(el => el.style.display='none');
                                                                    @if($hasCustom) this.querySelector('.custom-inp')?.style.setProperty('display','block'); @endif
                                                                ">
                                                                <input type="radio"
                                                                    name="{{ $fieldName }}[question_option_id]"
                                                                    value="{{ $opt->id }}"
                                                                    {{ $isSelected ? 'checked' : '' }}
                                                                    {{ $question->answer_required ? 'required' : '' }}
                                                                    class="mt-0.5 h-4 w-4 accent-indigo-600 flex-shrink-0">
                                                                <span class="text-sm font-medium text-gray-800">
                                                                    {{ $opt->optionChoice?->choice_text ?? '—' }}
                                                                </span>
                                                                @if($hasCustom)
                                                                    <input type="{{ $opt->optionChoice->custom_input_type === 'number' ? 'number' : 'text' }}"
                                                                        name="{{ $fieldName }}[custom_inputs][{{ $opt->id }}]"
                                                                        value="{{ old("{$fieldName}.custom_inputs.{$opt->id}", $customVal) }}"
                                                                        placeholder="{{ $opt->optionChoice->custom_input_placeholder ?? 'Specify…' }}"
                                                                        class="custom-inp ctrl mt-2"
                                                                        style="max-width: 200px; {{ $isSelected ? '' : 'display:none;' }}">
                                                                @endif
                                                            </label>
                                                        @endforeach
                                                    </div>

                                                {{-- CHECKBOX --}}
                                                @elseif($typeName === 'checkbox')
                                                    @php
                                                        $checkedIds = old("{$fieldName}.question_option_id",
                                                            is_array($prefill) ? $prefill : ($prefill ? [$prefill] : [])
                                                        );
                                                        $checkedIds = array_map('strval', (array)$checkedIds);
                                                    @endphp
                                                    <div class="space-y-2.5" id="check-group-{{ $question->id }}">
                                                        @foreach ($question->questionOptions as $opt)
                                                            @php
                                                                $isChecked = in_array((string)$opt->id, $checkedIds);
                                                                $hasCustom = $opt->optionChoice?->custom_input_type && $opt->optionChoice->custom_input_type !== 'none';
                                                                $customVal = $customIns[$opt->id] ?? '';
                                                            @endphp
                                                            <label class="opt-card {{ $isChecked ? 'selected-check' : '' }}"
                                                                onclick="this.classList.toggle('selected-check');
                                                                    let cb = this.querySelector('input[type=checkbox]');
                                                                    @if($hasCustom)
                                                                        let ci = this.querySelector('.custom-inp');
                                                                        if(ci) ci.style.display = cb.checked ? 'none' : 'block';
                                                                    @endif
                                                                ">
                                                                <input type="checkbox"
                                                                    name="{{ $fieldName }}[question_option_id][]"
                                                                    value="{{ $opt->id }}"
                                                                    {{ $isChecked ? 'checked' : '' }}
                                                                    class="mt-0.5 h-4 w-4 accent-indigo-600 flex-shrink-0">
                                                                <span class="text-sm font-medium text-gray-800">
                                                                    {{ $opt->optionChoice?->choice_text ?? '—' }}
                                                                </span>
                                                                @if($hasCustom)
                                                                    <input type="{{ $opt->optionChoice->custom_input_type === 'number' ? 'number' : 'text' }}"
                                                                        name="{{ $fieldName }}[custom_inputs][{{ $opt->id }}]"
                                                                        value="{{ old("{$fieldName}.custom_inputs.{$opt->id}", $customVal) }}"
                                                                        placeholder="{{ $opt->optionChoice->custom_input_placeholder ?? 'Specify…' }}"
                                                                        class="custom-inp ctrl mt-2"
                                                                        style="max-width: 200px; {{ $isChecked ? '' : 'display:none;' }}">
                                                                @endif
                                                            </label>
                                                        @endforeach
                                                    </div>

                                                {{-- DROPDOWN --}}
                                                @elseif($typeName === 'dropdown')
                                                    @php $selectedOption = old("{$fieldName}.question_option_id", (string)$prefill); @endphp
                                                    <select
                                                        name="{{ $fieldName }}[question_option_id]"
                                                        id="q{{ $question->id }}"
                                                        {{ $question->answer_required ? 'required' : '' }}
                                                        class="ctrl"
                                                        style="max-width: 28rem;">
                                                        <option value="">— विकल्प छान्नुहोस् —</option>
                                                        @foreach($question->questionOptions as $opt)
                                                            <option value="{{ $opt->id }}"
                                                                {{ (string)$opt->id === $selectedOption ? 'selected' : '' }}>
                                                                {{ $opt->optionChoice?->choice_text ?? '—' }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                {{-- LINEAR SCALE --}}
                                                @elseif($typeName === 'linear_scale')
                                                    @php
                                                        $scaleFrom = (int) ($question->scale_from ?? 1);
                                                        $scaleTo   = (int) ($question->scale_to   ?? 5);
                                                        $scaleSaved = old("{$fieldName}.answer_numeric", $prefill);
                                                    @endphp
                                                    <div class="mt-2" x-data="{ scaleVal: {{ $scaleSaved ?? 'null' }} }">
                                                        <div class="flex items-center gap-1 flex-wrap">
                                                            @if($question->scale_label_low)
                                                                <span class="text-xs text-gray-500 mr-1">{{ $question->scale_label_low }}</span>
                                                            @endif
                                                            @for($n = $scaleFrom; $n <= $scaleTo; $n++)
                                                                <button type="button"
                                                                    class="scale-btn"
                                                                    :class="scaleVal == {{ $n }} ? 'active' : ''"
                                                                    @click="scaleVal = {{ $n }}">
                                                                    {{ $n }}
                                                                </button>
                                                            @endfor
                                                            @if($question->scale_label_high)
                                                                <span class="text-xs text-gray-500 ml-1">{{ $question->scale_label_high }}</span>
                                                            @endif
                                                        </div>
                                                        <input type="hidden"
                                                            name="{{ $fieldName }}[answer_numeric]"
                                                            :value="scaleVal"
                                                            {{ $question->answer_required ? 'required' : '' }}>
                                                    </div>

                                                {{-- FILE UPLOAD --}}
                                                @elseif($typeName === 'file')
                                                    @if($prefill && str_starts_with($prefill, 'survey-responses/'))
                                                        <div class="mb-3">
                                                            <a href="{{ asset('storage/' . $prefill) }}" target="_blank" class="asset-pill">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                                </svg>
                                                                हालको फाइल हेर्नुहोस्
                                                            </a>
                                                            <p class="text-xs text-gray-400 mt-1">नयाँ फाइल छान्नुभयो भने पुरानो बदलिनेछ।</p>
                                                        </div>
                                                        <input type="hidden" name="{{ $fieldName }}[existing_file]" value="{{ $prefill }}">
                                                    @endif
                                                    <input type="file"
                                                        name="{{ $fieldName }}[file]"
                                                        id="q{{ $question->id }}"
                                                        class="ctrl py-2 text-sm text-gray-700 file:mr-3 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">

                                                {{-- LOCATION (read-only display, can't easily re-pick in a simple form) --}}
                                                @elseif($typeName === 'location')
                                                    @php
                                                        $locData = null;
                                                        if ($prefill && str_starts_with($prefill, '{')) {
                                                            $locData = json_decode($prefill, true);
                                                        }
                                                    @endphp
                                                    @if($locData && isset($locData['latitude'], $locData['longitude']))
                                                        <div class="flex items-center gap-4 flex-wrap">
                                                            <div>
                                                                <span class="field-label">Latitude</span>
                                                                <input type="number" step="any"
                                                                    name="{{ $fieldName }}[latitude]"
                                                                    value="{{ old("{$fieldName}.latitude", $locData['latitude']) }}"
                                                                    class="ctrl" style="max-width: 14rem;">
                                                            </div>
                                                            <div>
                                                                <span class="field-label">Longitude</span>
                                                                <input type="number" step="any"
                                                                    name="{{ $fieldName }}[longitude]"
                                                                    value="{{ old("{$fieldName}.longitude", $locData['longitude']) }}"
                                                                    class="ctrl" style="max-width: 14rem;">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="{{ $fieldName }}[answer_text]"
                                                            id="loc-hidden-{{ $question->id }}"
                                                            value="{{ $prefill }}">
                                                        <p class="text-xs text-gray-400 mt-2">
                                                            <svg class="inline w-3.5 h-3.5 mr-0.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                            </svg>
                                                            स्थान परिवर्तन गर्न ल्याटिच्युड/लंगिच्युड म्यानुअली सम्पादन गर्नुहोस्।
                                                        </p>
                                                    @else
                                                        <p class="text-sm text-gray-400 italic">स्थान डेटा उपलब्ध छैन।</p>
                                                    @endif

                                                {{-- FALLBACK: show display value as read-only --}}
                                                @else
                                                    <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-700">
                                                        {!! $question->display_answer ?? '<span class="text-gray-400 italic">उत्तर दिइएको छैन</span>' !!}
                                                    </div>
                                                    <p class="text-xs text-gray-400 mt-1">यो इनपुट प्रकार ({{ $typeName }}) यहाँ सम्पादन गर्न सकिँदैन।</p>
                                                @endif

                                            </div>{{-- /.q-card --}}
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Per-section tab navigation --}}
                                <div class="flex justify-between pt-4 border-t border-gray-100">
                                    @if($si > 0)
                                        <button type="button"
                                            @click="activeTab = {{ $si - 1 }}"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                            अघिल्लो खण्ड
                                        </button>
                                    @else
                                        <span></span>
                                    @endif

                                    @if($si < $sections->count() - 1)
                                        <button type="button"
                                            @click="activeTab = {{ $si + 1 }}"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                                            अर्को खण्ड
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                    @endif
                                </div>

                            </div>{{-- /.panel --}}
                        @endforeach

                    </div>{{-- /.tabbed card --}}

                    {{-- ── Sticky Save Bar ── --}}
                    <div class="save-bar">
                        <a href="{{ route('survey-responses.show', $response->id) }}"
                            class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                            रद्द गर्नुहोस्
                        </a>
                        <button type="submit" form="edit-response-form"
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            परिवर्तनहरू सुरक्षित गर्नुहोस्
                        </button>
                    </div>

                    @else
                        {{-- No survey sections, but still allow householder editing --}}
                        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm text-amber-800 flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            यस वडाको लागि कुनै सर्वेक्षण खण्ड फेला परेन। घरधुरी विवरण मात्र सम्पादन गर्न सकिनेछ।
                        </div>
                    @endif

                    {{-- ── Sticky Save Bar ── --}}
                    <div class="save-bar">
                        <a href="{{ route('survey-responses.show', $response->id) }}"
                            class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                            रद्द गर्नुहोस्
                        </a>
                        <button type="submit" form="edit-response-form"
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            परिवर्तनहरू सुरक्षित गर्नुहोस्
                        </button>
                    </div>

                </form>

        </div>
    </div>

    @push('scripts')
        <script>
            function previewHHPhoto(input) {
                if (!input.files || !input.files[0]) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrap = document.getElementById('photo-preview-wrap');
                    wrap.innerHTML = `<img src="${e.target.result}" alt="Preview" class="photo-ring">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        </script>
    @endpush

</x-app-layout>
