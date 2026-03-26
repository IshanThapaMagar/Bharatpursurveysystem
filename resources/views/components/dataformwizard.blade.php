@props(['wards' => null, 'wardInfo' => null, 'sections' => null, 'lookupData' => null])
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700&family=DM+Sans:wght@400;500;600&display=swap');

        * {
            font-family: 'DM Sans', sans-serif;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }

        .step-line {
            position: absolute;
            left: 11px;
            top: 32px;
            bottom: -16px;
            width: 2px;
            background: linear-gradient(180deg, #e2e8f0 0%, #cbd5e1 100%);
        }

        .step-line-completed {
            background: linear-gradient(180deg, #10b981 0%, #059669 100%);
        }

        .input-field {
            transition: all 0.2s ease;
        }

        .input-field:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        }

        .error-message {
            animation: slideInRight 0.3s ease-out;
        }

        .radio-card,
        .checkbox-card {
            transition: all 0.2s ease;
        }

        .radio-card:hover,
        .checkbox-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .required-asterisk {
            color: #ef4444;
            font-weight: 600;
        }

        .progress-bar {
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .loading-spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #6366f1;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .file-upload-area {
            border: 2px dashed #d1d5db;
            transition: all 0.3s ease;
        }

        .file-upload-area:hover {
            border-color: #6366f1;
            background-color: #f9fafb;
        }

        .file-upload-area.drag-over {
            border-color: #6366f1;
            background-color: #eef2ff;
        }

        .file-item {
            transition: all 0.2s ease;
        }

        .file-item:hover {
            background-color: #f9fafb;
        }

        .option-custom-input {
            margin-top: 0.5rem;
            padding-left: 2rem;
        }

        /* Map Styles */
        .location-map-container {
            height: 500px;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 2px solid #e5e7eb;
            margin-top: 0.75rem;
            position: relative;
        }

        .location-coordinates {
            background-color: #f3f4f6;
            border-radius: 0.5rem;
            padding: 0.75rem;
            margin-top: 0.75rem;
        }

        .leaflet-container {
            height: 100%;
            width: 100%;
            z-index: 1;
        }

        /* Enhanced Geocoder Control Styles */
        .leaflet-control-geocoder {
            border-radius: 0.5rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            border: 2px solid #e5e7eb !important;
        }

        .leaflet-control-geocoder-form input {
            border-radius: 0.375rem !important;
            border: 1px solid #d1d5db !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem !important;
            width: 280px !important;
        }

        .leaflet-control-geocoder-form input:focus {
            outline: none !important;
            border-color: #6366f1 !important;
            ring: 2px !important;
            ring-color: #6366f1 !important;
        }

        .leaflet-control-geocoder-alternatives {
            border-radius: 0.375rem !important;
            margin-top: 0.25rem !important;
            max-height: 250px !important;
            overflow-y: auto !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }

        .leaflet-control-geocoder-alternatives a {
            padding: 0.625rem !important;
            border-bottom: 1px solid #e5e7eb !important;
            font-size: 0.875rem !important;
        }

        .leaflet-control-geocoder-alternatives a:hover {
            background-color: #f3f4f6 !important;
        }

        .leaflet-control-geocoder-icon {
            background-color: #6366f1 !important;
            border-radius: 0.375rem !important;
        }

        /* Custom marker popup */
        .leaflet-popup-content-wrapper {
            border-radius: 0.5rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }

        .leaflet-popup-content {
            margin: 0.75rem !important;
            font-size: 0.875rem !important;
        }

        /* Location info badge */
        .location-info-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            font-size: 0.75rem;
            color: #4b5563;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .location-info-badge svg {
            width: 1rem;
            height: 1rem;
            color: #6366f1;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.75);
            z-index: 9998;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-container {
            background: white;
            border-radius: 1rem;
            max-width: 90vw;
            max-height: 90vh;
            width: 1200px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            z-index: 9999;
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-body {
            flex: 1;
            overflow: auto;
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-map-container {
            height: 500px;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 2px solid #e5e7eb;
            position: relative;
        }

        @media (max-width: 768px) {
            .modal-container {
                max-width: 95vw;
                max-height: 95vh;
            }

            .modal-map-container {
                height: 400px;
            }
        }
    </style>
@endpush

<div x-data="surveyWizard()" class="px-12 sm:py-1">
    {{-- Ward selector: only in AJAX mode (no server-side data passed) --}}
    @if(!$wardInfo)
    <div class="mb-8 animate-fade-in-up">
        <div class="w-52">
            <label for="ward-select" class="block text-sm font-semibold text-gray-900 mb-2">
                Select Ward <span class="required-asterisk">*</span>
            </label>
            <select id="ward-select" x-model="selectedWardId" @change="loadWardData()"
                class="block w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm  transition-all">
                <option value="">-- Select a Ward --</option>
                @foreach ($wards as $ward)
                    <option value="{{ $ward->id }}" {{ old('ward_id') == $ward->id ? 'selected' : '' }}>Ward
                        {{ $ward->ward_no }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif

    @if(!$wardInfo)
    <!-- Loading State -->
    <div x-show="isLoading" x-cloak class="flex justify-center items-center py-20">
        <div class="text-center">
            <div class="loading-spinner mx-auto mb-4"></div>
            <p class="text-gray-600 font-medium">Loading survey...</p>
        </div>
    </div>

    <!-- No Ward Selected State -->
    <div x-show="!selectedWardId && !isLoading" x-cloak class="text-center py-20">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Ward Selected</h3>
        <p class="text-gray-600">Please select a ward from the dropdown above to begin the survey.</p>
    </div>

    <!-- Survey Form (AJAX mode) -->
    <div x-show="selectedWardId && !isLoading && sections.length > 0" x-cloak>
    @else
    <!-- Survey Form (server-side mode: always visible) -->
    <div>
    @endif
        <!-- Progress Bar -->
        <div class="mb-6 animate-fade-in-up">
            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100">
                <div class="progress-bar h-full bg-gradient-to-r from-indigo-500 to-purple-600"
                    :style="`width: ${(currentStep / totalSteps) * 100}%`"></div>
            </div>
            <div class="mt-2 text-center text-sm text-gray-600">
                <span class="font-semibold" x-text="currentStep"></span> of <span x-text="totalSteps"></span> sections
            </div>
        </div>

        <div class="animate-fade-in-up overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl">
            <div class="grid lg:grid-cols-[300px_1fr]">
                <!-- Left sidebar with stepper -->
                <div
                    class="border-b border-gray-200 bg-gradient-to-br from-gray-50 to-gray-100 p-6 lg:border-b-0 lg:border-r lg:p-8">
                    <div class="mb-8 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-lg font-bold text-gray-900" x-text="'Ward ' + wardInfo.ward_no"></span>
                            <p class="text-xs text-gray-600">Survey Form</p>
                        </div>
                    </div>

                    <!-- Stepper -->
                    <div class="space-y-6">
                        <template x-for="(step, index) in steps" :key="step.id">
                            <div class="relative">
                                <!-- Connecting line -->
                                <div x-show="index < steps.length - 1" class="step-line"
                                    :class="currentStep > step.id ? 'step-line-completed' : ''"></div>

                                <button @click="navigateToStep(step.id)" type="button"
                                    class="relative flex w-full items-start gap-3 text-left transition-all duration-200 hover:opacity-80"
                                    :class="currentStep >= step.id ? '' : 'opacity-50'">
                                    <!-- Step circle -->
                                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-2 transition-all duration-200"
                                        :class="currentStep > step.id ?
                                            'border-emerald-500 bg-emerald-500 shadow-lg shadow-emerald-500/30' :
                                            currentStep === step.id ?
                                            'border-indigo-600 bg-indigo-600 shadow-lg shadow-indigo-600/30' :
                                            'border-gray-300 bg-white'">
                                        <template x-if="currentStep > step.id">
                                            <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </template>
                                        <template x-if="currentStep <= step.id">
                                            <span class="text-xs font-bold"
                                                :class="currentStep === step.id ? 'text-white' : 'text-gray-400'"
                                                x-text="step.id"></span>
                                        </template>
                                    </div>

                                    <!-- Step content -->
                                    <div class="flex-1 pt-0.5">
                                        <p class="text-sm font-semibold leading-tight"
                                            :class="currentStep === step.id ? 'text-gray-900' : 'text-gray-600'"
                                            x-text="step.title"></p>
                                        <p class="mt-0.5 text-xs text-gray-500" x-text="step.description"
                                            x-show="step.description"></p>
                                    </div>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex flex-col p-4 lg:p-8">
                    <form @submit.prevent="handleSubmit" class="flex flex-1 flex-col">
                        <div class="flex-1">
                            <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform translate-y-4"
                                x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
                                <div class="mb-6">
                                    <h2 class="text-3xl font-bold text-gray-900"
                                        style="font-family: 'Archivo', sans-serif;">Householder Information</h2>
                                    <p class="mt-2 text-base text-gray-600">Please provide the householder's basic
                                        information</p>
                                </div>

                                <x-static-form-fields />
                            </div>

                            <!-- Dynamic Sections -->
                            <template x-for="(section, sIndex) in sections" :key="section.id">
                                <div x-show="currentStep === sIndex + 2"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform translate-y-4"
                                    x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>

                                    <div class="mb-6">
                                        <h2 class="text-3xl font-bold text-gray-900"
                                            style="font-family: 'Archivo', sans-serif;" x-text="section.title"></h2>
                                        <p x-show="section.description" class="mt-2 text-base text-gray-600"
                                            x-text="section.description"></p>
                                    </div>

                                    <div class="grid gap-4 grid-cols-1">
                                        <template x-for="question in section.questions" :key="question.id">
                                            <div
                                                class="rounded-xl border border-gray-200 bg-gray-50 p-4 transition-all hover:border-indigo-200 hover:bg-white">
                                                <div class="block">
                                                    <span class="text-base font-semibold text-gray-900">
                                                        <span x-text="question.question_text"></span>
                                                        <span x-show="question.answer_required"
                                                            class="required-asterisk">*</span>
                                                    </span>
                                                    <span x-show="question.question_subtext"
                                                        class="mt-1 block text-sm text-gray-500"
                                                        x-text="question.question_subtext"></span>

                                                    <div class="mt-4">
                                                        <!-- Short Text Input -->
                                                        <template
                                                            x-if="question.input_type?.input_type_name === 'short_text'">
                                                            <input type="text"
                                                                x-model="formData.answers[question.id].answer_text"
                                                                @input="clearError(question.id)"
                                                                :required="question.answer_required"
                                                                class="input-field mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                                placeholder="Enter your answer">
                                                        </template>

                                                        <!-- Long Text / Textarea -->
                                                        <template
                                                            x-if="question.input_type?.input_type_name === 'long_text'">
                                                            <textarea x-model="formData.answers[question.id].answer_text" @input="clearError(question.id)"
                                                                :required="question.answer_required" rows="4"
                                                                class="input-field mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                                placeholder="Enter your answer"></textarea>
                                                        </template>

                                                        <!-- Email Input -->
                                                        <template
                                                            x-if="question.input_type?.input_type_name === 'email'">
                                                            <input type="email"
                                                                x-model="formData.answers[question.id].answer_text"
                                                                @input="clearError(question.id)"
                                                                :required="question.answer_required"
                                                                class="input-field mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                                placeholder="email@example.com">
                                                        </template>

                                                        <!-- Number Input -->
                                                        <template
                                                            x-if="question.input_type?.input_type_name === 'number'">
                                                            <input type="number" step="0.01"
                                                                x-model="formData.answers[question.id].answer_numeric"
                                                                @input="clearError(question.id)"
                                                                :required="question.answer_required"
                                                                class="input-field mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                                placeholder="">
                                                        </template>

                                                        <!-- Date Input -->
                                                        <template
                                                            x-if="question.input_type?.input_type_name === 'date'">
                                                            <input type="date"
                                                                x-model="formData.answers[question.id].answer_text"
                                                                @input="clearError(question.id)"
                                                                :required="question.answer_required"
                                                                class="input-field mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                        </template>

                                                        <!-- Radio Buttons -->
                                                        <template
                                                            x-if="question.input_type?.input_type_name === 'radio'">
                                                            <div class="mt-3 space-y-3">
                                                                <template x-for="qOption in question.question_options"
                                                                    :key="qOption.id">
                                                                    <label
                                                                        class="radio-card flex items-center gap-3 rounded-xl border-2 border-gray-200 bg-white p-4 transition-all hover:border-indigo-300"
                                                                        :class="formData.answers[question.id]
                                                                            .question_option_id == qOption.id ?
                                                                            'border-indigo-600 bg-indigo-50 shadow-sm' :
                                                                            ''">
                                                                        <input type="radio"
                                                                            :name="'question_' + question.id"
                                                                            x-model="formData.answers[question.id].question_option_id"
                                                                            @change="clearError(question.id)"
                                                                            :value="qOption.id"
                                                                            :required="question.answer_required"
                                                                            class="h-5 w-5 text-indigo-600 border-gray-300 focus:ring-2 focus:ring-indigo-500">
                                                                        <span
                                                                            class="whitespace-nowrap font-medium text-gray-900"
                                                                            x-text="qOption.option_choice?.choice_text">
                                                                        </span>
                                                                        <input
                                                                            x-show="qOption.option_choice?.custom_input_type 
                                                                            && qOption.option_choice.custom_input_type !== 'none'
                                                                            && formData.answers[question.id].question_option_id == qOption.id"
                                                                            x-transition.opacity.duration.200ms
                                                                            :type="qOption.option_choice
                                                                                .custom_input_type === 'number' ?
                                                                                'number' : 'text'"
                                                                            x-model="formData.answers[question.id].custom_inputs[qOption.id]"
                                                                            :placeholder="qOption.option_choice
                                                                                .custom_input_placeholder || 'Specify'"
                                                                            class="ml-3 flex-1 min-w-[180px] rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                                    </label>
                                                                </template>
                                                            </div>
                                                        </template>

                                                        <!-- Linear Scale -->
                                                        <template
                                                            x-if="question.input_type?.input_type_name === 'linear_scale'">
                                                            <div class="mt-6" @click.stop>
                                                                <div class="flex justify-between items-center mt-2">
                                                                    <span class="text-xs text-gray-500 mr-2"
                                                                        x-text="question.scale_label_low || question.scale_from"></span>
                                                                    <template
                                                                        x-for="n in (question.scale_to - question.scale_from + 1)"
                                                                        :key="n">
                                                                        <div class="flex flex-col items-center flex-1">
                                                                            <label
                                                                                class="cursor-pointer flex flex-col items-center">
                                                                                <input type="radio"
                                                                                    :name="'question_' + question.id"
                                                                                    :value="question.scale_from + n - 1"
                                                                                    x-model.number="formData.answers[question.id].answer_numeric"
                                                                                    @change="clearError(question.id)"
                                                                                    :required="question.answer_required"
                                                                                    class="mb-1">
                                                                                <span class="text-xs text-gray-600"
                                                                                    x-text="question.scale_from + n - 1"></span>
                                                                            </label>
                                                                        </div>
                                                                    </template>
                                                                    <span class="text-xs text-gray-500 ml-2"
                                                                        x-text="question.scale_label_high || question.scale_to"></span>
                                                                </div>
                                                            </div>
                                                        </template>

                                                        <!-- Checkboxes -->
                                                        <template
                                                            x-if="question.input_type?.input_type_name === 'checkbox'">
                                                            <div class="mt-3 space-y-3">
                                                                <template x-for="qOption in question.question_options"
                                                                    :key="qOption.id">
                                                                    <label
                                                                        class="checkbox-card flex items-center gap-3 rounded-xl border-2 border-gray-200 bg-white p-4 transition-all hover:border-indigo-300"
                                                                        :class="formData.answers[question.id].question_option_id
                                                                            ?.includes(String(qOption.id)) ?
                                                                            'border-indigo-600 bg-indigo-50 shadow-sm' :
                                                                            ''">
                                                                        <input type="checkbox"
                                                                            x-model="formData.answers[question.id].question_option_id"
                                                                            @change="clearError(question.id)"
                                                                            :value="qOption.id"
                                                                            class="h-5 w-5 text-indigo-600 border-gray-300 focus:ring-2 focus:ring-indigo-500">
                                                                        <span
                                                                            class="whitespace-nowrap font-medium text-gray-900"
                                                                            x-text="qOption.option_choice?.choice_text"></span>
                                                                        <input
                                                                            x-show="qOption.option_choice?.custom_input_type
                                                                            && qOption.option_choice.custom_input_type !== 'none'
                                                                            && formData.answers[question.id].question_option_id?.includes(String(qOption.id))"
                                                                            x-transition.opacity.duration.200ms
                                                                            :type="qOption.option_choice
                                                                                .custom_input_type === 'number' ?
                                                                                'number' : 'text'"
                                                                            x-model="formData.answers[question.id].custom_inputs[qOption.id]"
                                                                            :placeholder="qOption.option_choice
                                                                                .custom_input_placeholder || 'Specify'"
                                                                            class="ml-3 flex-1 min-w-[180px] rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                                    </label>
                                                                </template>
                                                            </div>
                                                        </template>

                                                        <!-- Dropdown -->
                                                        <template
                                                            x-if="question.input_type?.input_type_name === 'dropdown'">
                                                            <select
                                                                x-model="formData.answers[question.id].question_option_id"
                                                                @change="clearError(question.id)"
                                                                :required="question.answer_required"
                                                                class="input-field mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                                <option value="">-- Select an option --</option>
                                                                <template x-for="qOption in question.question_options"
                                                                    :key="qOption.id">
                                                                    <option :value="qOption.id"
                                                                        x-text="qOption.option_choice?.choice_text">
                                                                    </option>
                                                                </template>
                                                            </select>
                                                        </template>

                                                        <!-- Location/GPS Input with Modal Map -->
                                                        <template
                                                            x-if="question.input_type?.input_type_name === 'location'">
                                                            <div>
                                                                <!-- Location Selection Button -->
                                                                <button type="button"
                                                                    @click="openMapModal(question.id)"
                                                                    class="w-full mt-2 flex items-center justify-center gap-3 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-6 text-center transition-all hover:border-indigo-500 hover:bg-indigo-50">
                                                                    <svg class="h-10 w-10 text-gray-400"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    </svg>
                                                                    <div class="text-left">
                                                                        <p
                                                                            class="text-base font-semibold text-gray-900">
                                                                            <span
                                                                                x-show="!formData.answers[question.id].latitude">Select
                                                                                Location on Map</span>
                                                                            <span
                                                                                x-show="formData.answers[question.id].latitude">Change
                                                                                Location</span>
                                                                        </p>
                                                                        <p class="text-sm text-gray-500">Click to open
                                                                            interactive map</p>
                                                                    </div>
                                                                </button>

                                                                <!-- Coordinates Display (Read-only) -->
                                                                <div x-show="formData.answers[question.id].latitude"
                                                                    class="location-coordinates mt-3">
                                                                    <div
                                                                        class="flex items-center justify-between mb-2">
                                                                        <span
                                                                            class="text-xs font-semibold text-gray-700 flex items-center gap-1">
                                                                            <svg class="h-4 w-4 text-green-600"
                                                                                fill="currentColor"
                                                                                viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                                    clip-rule="evenodd" />
                                                                            </svg>
                                                                            Location Selected
                                                                        </span>
                                                                    </div>
                                                                    <div class="grid grid-cols-2 gap-3">
                                                                        <div>
                                                                            <label
                                                                                class="text-xs font-medium text-gray-600 mb-1 block">Latitude</label>
                                                                            <div
                                                                                class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-md px-3 py-2">
                                                                                <svg class="h-4 w-4 text-gray-400"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                                </svg>
                                                                                <input type="text"
                                                                                    :value="formData.answers[question.id]
                                                                                        .latitude"
                                                                                    readonly
                                                                                    class="flex-1 bg-transparent border-0 p-0 text-sm text-gray-700 focus:ring-0 cursor-default">
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <label
                                                                                class="text-xs font-medium text-gray-600 mb-1 block">Longitude</label>
                                                                            <div
                                                                                class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-md px-3 py-2">
                                                                                <svg class="h-4 w-4 text-gray-400"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                                </svg>
                                                                                <input type="text"
                                                                                    :value="formData.answers[question.id]
                                                                                        .longitude"
                                                                                    readonly
                                                                                    class="flex-1 bg-transparent border-0 p-0 text-sm text-gray-700 focus:ring-0 cursor-default">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Hidden inputs for validation -->
                                                                <input type="hidden"
                                                                    x-model="formData.answers[question.id].latitude"
                                                                    :required="question.answer_required">
                                                                <input type="hidden"
                                                                    x-model="formData.answers[question.id].longitude"
                                                                    :required="question.answer_required">

                                                                <!-- Map Modal -->
                                                                <div x-show="mapModal.isOpen && mapModal.questionId === question.id"
                                                                    x-cloak class="modal-overlay"
                                                                    @click.self="closeMapModal()"
                                                                    x-transition:enter="transition ease-out duration-300"
                                                                    x-transition:enter-start="opacity-0"
                                                                    x-transition:enter-end="opacity-100"
                                                                    x-transition:leave="transition ease-in duration-200"
                                                                    x-transition:leave-start="opacity-100"
                                                                    x-transition:leave-end="opacity-0">

                                                                    <div class="modal-container"
                                                                        x-transition:enter="transition ease-out duration-300"
                                                                        x-transition:enter-start="opacity-0 transform scale-95"
                                                                        x-transition:enter-end="opacity-100 transform scale-100"
                                                                        x-transition:leave="transition ease-in duration-200"
                                                                        x-transition:leave-start="opacity-100 transform scale-100"
                                                                        x-transition:leave-end="opacity-0 transform scale-95">

                                                                        <!-- Modal Header -->
                                                                        <div class="modal-header">
                                                                            <div class="flex items-center gap-3">
                                                                                <div
                                                                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100">
                                                                                    <svg class="h-6 w-6 text-indigo-600"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                                    </svg>
                                                                                </div>
                                                                                <div>
                                                                                    <h3
                                                                                        class="text-lg font-bold text-gray-900">
                                                                                        Select Location</h3>
                                                                                    <p class="text-sm text-gray-500">
                                                                                        Search, click, or drag the
                                                                                        marker</p>
                                                                                </div>
                                                                            </div>
                                                                            <button type="button"
                                                                                @click="closeMapModal()"
                                                                                class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                                                                                <svg class="h-6 w-6" fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                                </svg>
                                                                            </button>
                                                                        </div>

                                                                        <!-- Modal Body -->
                                                                        <div class="modal-body">
                                                                            <!-- Map Container -->
                                                                            <div class="modal-map-container"
                                                                                :id="'map-' + question.id">
                                                                                <!-- Info Badge -->
                                                                                <div class="location-info-badge">
                                                                                    <svg fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                    </svg>
                                                                                    <span>Search or click to set
                                                                                        location</span>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Current Coordinates in Modal -->
                                                                            <div class="mt-4 grid grid-cols-2 gap-3">
                                                                                <div>
                                                                                    <label
                                                                                        class="text-xs font-medium text-gray-600 mb-1 block">Latitude</label>
                                                                                    <div
                                                                                        class="flex items-center gap-2 bg-indigo-50 border border-indigo-200 rounded-md px-3 py-2">
                                                                                        <span
                                                                                            class="text-sm font-mono text-indigo-900"
                                                                                            x-text="formData.answers[question.id].latitude || 'Not set'"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div>
                                                                                    <label
                                                                                        class="text-xs font-medium text-gray-600 mb-1 block">Longitude</label>
                                                                                    <div
                                                                                        class="flex items-center gap-2 bg-indigo-50 border border-indigo-200 rounded-md px-3 py-2">
                                                                                        <span
                                                                                            class="text-sm font-mono text-indigo-900"
                                                                                            x-text="formData.answers[question.id].longitude || 'Not set'"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Modal Footer -->
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                @click="getCurrentLocation(question.id)"
                                                                                class="flex items-center gap-2 px-4 py-2 text-sm text-indigo-600 hover:text-indigo-700 font-medium hover:bg-indigo-50 rounded-lg transition-colors">
                                                                                <svg class="h-4 w-4" fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                                </svg>
                                                                                Use My Location
                                                                            </button>

                                                                            <div class="flex gap-3">
                                                                                <button type="button"
                                                                                    @click="closeMapModal()"
                                                                                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                                                                    Cancel
                                                                                </button>
                                                                                <button type="button"
                                                                                    @click="confirmLocation(question.id)"
                                                                                    class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg transition-all shadow-sm hover:shadow-md">
                                                                                    Confirm Location
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </template>

                                                        <!-- File Upload -->
                                                        <template
                                                            x-if="question.input_type?.input_type_name === 'file'">
                                                            <div>
                                                                <div class="file-upload-area mt-2 rounded-lg p-6 text-center"
                                                                    @drop.prevent="handleFileDrop($event, question.id)"
                                                                    @dragover.prevent="$event.currentTarget.classList.add('drag-over')"
                                                                    @dragleave.prevent="$event.currentTarget.classList.remove('drag-over')">
                                                                    <svg class="mx-auto h-12 w-12 text-gray-400"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                                    </svg>
                                                                    <div class="mt-4">
                                                                        <label :for="'file-input-' + question.id"
                                                                            class="cursor-pointer">
                                                                            <span
                                                                                class="text-indigo-600 font-medium hover:text-indigo-500">Upload
                                                                                a file</span>
                                                                            <span class="text-gray-500"> or drag and
                                                                                drop</span>
                                                                        </label>
                                                                        <input :id="'file-input-' + question.id"
                                                                            type="file"
                                                                            @change="handleFileSelect($event, question.id)"
                                                                            :required="question.answer_required && !formData
                                                                                .answers[question.id].files?.length"
                                                                            class="hidden">
                                                                    </div>
                                                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, PDF
                                                                        up to 10MB</p>
                                                                </div>

                                                                <div x-show="formData.answers[question.id].files?.length"
                                                                    class="mt-3 space-y-2">
                                                                    <template
                                                                        x-for="(file, fIndex) in formData.answers[question.id].files"
                                                                        :key="fIndex">
                                                                        <div
                                                                            class="file-item flex items-center justify-between rounded-lg border border-gray-200 bg-white p-3">
                                                                            <div class="flex items-center gap-3">
                                                                                <svg class="h-5 w-5 text-indigo-600"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                                </svg>
                                                                                <div>
                                                                                    <p class="text-sm font-medium text-gray-900"
                                                                                        x-text="file.name"></p>
                                                                                    <p class="text-xs text-gray-500"
                                                                                        x-text="formatFileSize(file.size)">
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                            <button type="button"
                                                                                @click="removeFile(question.id, fIndex)"
                                                                                class="text-red-600 hover:text-red-800">
                                                                                <svg class="h-5 w-5" fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <!-- Error message -->
                                                    <div x-show="errors[question.id]"
                                                        x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 translate-y-1"
                                                        x-transition:enter-end="opacity-100 translate-y-0"
                                                        class="error-message mt-2 flex items-center gap-2 text-sm text-red-600"
                                                        x-cloak>
                                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <span x-text="errors[question.id]"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Navigation buttons -->
                        <div class="mt-6 flex items-center justify-between border-t border-gray-200 pt-6">
                            <button type="button" @click="prevStep()" :disabled="currentStep === 1"
                                class="flex items-center gap-2 rounded-lg px-6 py-3 text-sm font-semibold transition-all duration-200"
                                :class="currentStep === 1 ?
                                    'cursor-not-allowed text-gray-400' :
                                    'text-gray-700 hover:bg-gray-100 hover:text-gray-900'">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Previous
                            </button>

                            <button type="button" @click="currentStep < totalSteps ? nextStep() : handleSubmit()"
                                :disabled="isSubmitting"
                                class="flex items-center gap-2 rounded-lg px-6 py-3 text-sm font-semibold text-white transition-all duration-200 hover:shadow-lg disabled:opacity-50"
                                :class="currentStep < totalSteps ?
                                    'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700' :
                                    'bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700'">
                                <template x-if="!isSubmitting">
                                    <span class="flex items-center gap-2">
                                        <span x-text="currentStep < totalSteps ? 'Continue' : 'Submit Survey'"></span>
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="currentStep < totalSteps ? 'M9 5l7 7-7 7' : 'M5 13l4 4L19 7'" />
                                        </svg>
                                    </span>
                                </template>
                                <template x-if="isSubmitting">
                                    <span class="flex items-center gap-2">
                                        <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Submitting...
                                    </span>
                                </template>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div x-show="showToast" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-6 right-6 z-50 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4 text-white shadow-2xl"
        x-cloak>
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="font-bold">Survey Submitted!</p>
                <p class="text-sm opacity-90">Thank you for completing the survey.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>
        function surveyWizard() {
            // PHP-provided server-side data (null in AJAX mode)
            const phpWardInfo   = @json($wardInfo);
            const phpSections   = @json($sections);
            const phpLookupData = @json($lookupData);
            const isServerSide  = phpWardInfo !== null;

            return {
                selectedWardId: isServerSide ? phpWardInfo.id : '',
                isLoading: false,
                currentStep: 1,
                totalSteps: 0,
                showToast: false,
                isSubmitting: false,
                errors: {},
                householderErrors: {},
                wardInfo: isServerSide ? phpWardInfo : {},
                sections: isServerSide ? phpSections : [],
                steps: [],
                maps: {},
                markers: {},
                geocoders: {},
                mapModal: {
                    isOpen: false,
                    questionId: null
                },
                lookupData: isServerSide ? phpLookupData : {
                    mother_tongues: [],
                    castes: [],
                    toles: [],
                    citizenship_permanent_addresses: []
                },
                formData: {
                    ward_id: isServerSide ? phpWardInfo.id : null,
                    householder: {
                        householder_name: '',
                        father_name: '',
                        mother_name: '',
                        mother_tongue_id: '',
                        caste_id: '',
                        tole_id: '',
                        ward_no: isServerSide ? phpWardInfo.ward_no : '',
                        lot_number: '',
                        house_number: '',
                        phone_number: '',
                        citizenship_permanent_address_id: '',
                        profile_photo: null,
                        profile_photo_preview: ''
                    },
                    answers: {}
                },

                init() {
                    if (isServerSide) {
                        // Server-side mode: build steps & answers directly from PHP data
                        this._buildFromSections();
                    } else {
                        // AJAX mode: auto-select if only one ward available
                        this.$nextTick(() => {
                            const wardSelect = document.getElementById('ward-select');
                            if (wardSelect && wardSelect.options.length === 2 && wardSelect.options[1].value) {
                                this.selectedWardId = wardSelect.options[1].value;
                                this.loadWardData();
                            }
                        });
                    }
                },

                _buildFromSections() {
                    this.totalSteps = 1 + this.sections.length;
                    this.steps = [
                        { id: 1, title: 'Householder Information', description: 'Basic householder details' },
                        ...this.sections.map((section, index) => ({
                            id: index + 2,
                            title: section.title,
                            description: section.description || ''
                        }))
                    ];
                    this.formData.answers = {};
                    this.sections.forEach(section => {
                        section.questions.forEach(question => {
                            const inputType = question.input_type?.input_type_name;
                            if (inputType === 'checkbox') {
                                this.formData.answers[question.id] = { question_option_id: [], custom_inputs: {} };
                            } else if (['radio', 'dropdown'].includes(inputType)) {
                                this.formData.answers[question.id] = { question_option_id: '', custom_inputs: {} };
                            } else if (inputType === 'number') {
                                this.formData.answers[question.id] = { answer_numeric: '', unit_of_measure_id: '' };
                            } else if (inputType === 'linear_scale') {
                                this.formData.answers[question.id] = { answer_numeric: '' };
                            } else if (inputType === 'file') {
                                this.formData.answers[question.id] = { files: [] };
                            } else if (inputType === 'location') {
                                this.formData.answers[question.id] = { latitude: '', longitude: '' };
                            } else {
                                this.formData.answers[question.id] = { answer_text: '' };
                            }
                        });
                    });
                },

                openMapModal(questionId) {
                    this.mapModal.isOpen = true;
                    this.mapModal.questionId = questionId;


                    this.$nextTick(() => {
                        setTimeout(() => {
                            if (!this.maps[questionId]) {
                                this.initMap(questionId);
                            } else {

                                this.maps[questionId].invalidateSize();


                                const lat = this.formData.answers[questionId].latitude;
                                const lng = this.formData.answers[questionId].longitude;
                                if (lat && lng) {
                                    const marker = this.markers[questionId];
                                    const map = this.maps[questionId];
                                    marker.setLatLng([parseFloat(lat), parseFloat(lng)]);
                                    map.setView([parseFloat(lat), parseFloat(lng)], 15);
                                }
                            }
                        }, 100);
                    });
                },

                closeMapModal() {
                    this.mapModal.isOpen = false;
                    this.mapModal.questionId = null;
                },

                confirmLocation(questionId) {

                    if (!this.formData.answers[questionId].latitude || !this.formData.answers[questionId].longitude) {
                        alert('Please select a location on the map first.');
                        return;
                    }

                    this.clearError(questionId);
                    this.closeMapModal();
                },

                async loadWardData() {
                    if (!this.selectedWardId) {
                        this.resetForm();
                        return;
                    }

                    this.isLoading = true;
                    this.currentStep = 1;
                    this.errors = {};
                    this.householderErrors = {};

                    try {
                        const [sectionsResponse, lookupResponse] = await Promise.all([
                            fetch(`/admin/survey/ward/${this.selectedWardId}/sections`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            }),
                            fetch(`/admin/survey/ward/${this.selectedWardId}/lookup-data`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                        ]);

                        const sectionsData = await sectionsResponse.json();
                        const lookupData = await lookupResponse.json();

                        if (sectionsData.success && lookupData.success) {
                            this.wardInfo = sectionsData.ward;
                            this.sections = sectionsData.sections;
                            this.lookupData = {
                                mother_tongues: lookupData.mother_tongues || [],
                                castes: lookupData.castes || [],
                                toles: lookupData.toles || [],
                                citizenship_permanent_addresses: lookupData.citizenship_permanent_addresses || []
                            };

                            this.totalSteps = 1 + this.sections.length;
                            this.formData.ward_id = this.selectedWardId;


                            if (this.wardInfo && this.wardInfo.ward_no) {
                                this.formData.householder.ward_no = this.wardInfo.ward_no;
                            }

                            this.steps = [{
                                    id: 1,
                                    title: 'Householder Information',
                                    description: 'Basic householder details'
                                },
                                ...this.sections.map((section, index) => ({
                                    id: index + 2,
                                    title: section.title,
                                    description: section.description || ''
                                }))
                            ];

                            this.formData.answers = {};
                            this.sections.forEach(section => {
                                section.questions.forEach(question => {
                                    const inputType = question.input_type?.input_type_name;

                                    if (inputType === 'checkbox') {
                                        this.formData.answers[question.id] = {
                                            question_option_id: [],
                                            custom_inputs: {}
                                        };
                                    } else if (['radio', 'dropdown'].includes(inputType)) {
                                        this.formData.answers[question.id] = {
                                            question_option_id: '',
                                            custom_inputs: {}
                                        };
                                    } else if (inputType === 'number') {
                                        this.formData.answers[question.id] = {
                                            answer_numeric: '',
                                            unit_of_measure_id: ''
                                        };
                                    } else if (inputType === 'linear_scale') {
                                        this.formData.answers[question.id] = {
                                            answer_numeric: ''
                                        };
                                    } else if (inputType === 'file') {
                                        this.formData.answers[question.id] = {
                                            files: []
                                        };
                                    } else if (inputType === 'location') {
                                        this.formData.answers[question.id] = {
                                            latitude: '',
                                            longitude: ''
                                        };
                                    } else {
                                        this.formData.answers[question.id] = {
                                            answer_text: ''
                                        };
                                    }
                                });
                            });

                            this.$nextTick(() => {

                            });

                        } else {
                            alert('Failed to load survey data');
                        }
                    } catch (error) {
                        console.error('Error loading ward data:', error);
                        alert('An error occurred while loading the survey');
                    } finally {
                        this.isLoading = false;
                    }
                },

                initMap(questionId) {
                    const mapId = 'map-' + questionId;
                    const mapElement = document.getElementById(mapId);

                    if (!mapElement || this.maps[questionId]) {
                        return;
                    }


                    const existingLat = this.formData.answers[questionId].latitude;
                    const existingLng = this.formData.answers[questionId].longitude;

                    const defaultLat = existingLat ? parseFloat(existingLat) : 27.7172;
                    const defaultLng = existingLng ? parseFloat(existingLng) : 85.3240;
                    const defaultZoom = existingLat ? 15 : 13;


                    const map = L.map(mapId).setView([defaultLat, defaultLng], defaultZoom);


                    L.tileLayer(
                        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                            attribution: 'Tiles © Esri — Source: Esri, Maxar, Earthstar Geographics',
                            maxZoom: 19
                        }).addTo(map);


                    const marker = L.marker([defaultLat, defaultLng], {
                        draggable: true
                    }).addTo(map);


                    const popupText = existingLat ? '<b>Selected Location</b><br>Drag to adjust' :
                        '<b>Drag me!</b><br>Or search for a location';
                    marker.bindPopup(popupText).openPopup();


                    const geocoder = L.Control.geocoder({
                            defaultMarkGeocode: false,
                            placeholder: 'Search for a place...',
                            errorMessage: 'Nothing found.',
                            collapsed: false,
                            position: 'topleft',
                            geocoder: L.Control.Geocoder.nominatim({
                                geocodingQueryParams: {
                                    countrycodes: 'np',
                                    limit: 5
                                }
                            })
                        })
                        .on('markgeocode', (e) => {
                            const latlng = e.geocode.center;
                            map.setView(latlng, 15);
                            marker.setLatLng(latlng);
                            this.formData.answers[questionId].latitude = latlng.lat.toFixed(6);
                            this.formData.answers[questionId].longitude = latlng.lng.toFixed(6);
                            marker.bindPopup(e.geocode.name).openPopup();
                            this.clearError(questionId);
                        })
                        .addTo(map);


                    marker.on('dragend', (e) => {
                        const position = e.target.getLatLng();
                        this.formData.answers[questionId].latitude = position.lat.toFixed(6);
                        this.formData.answers[questionId].longitude = position.lng.toFixed(6);
                        this.clearError(questionId);
                    });


                    map.on('click', (e) => {
                        const {
                            lat,
                            lng
                        } = e.latlng;
                        marker.setLatLng([lat, lng]);
                        this.formData.answers[questionId].latitude = lat.toFixed(6);
                        this.formData.answers[questionId].longitude = lng.toFixed(6);
                        this.clearError(questionId);
                    });


                    this.maps[questionId] = map;
                    this.markers[questionId] = marker;
                    this.geocoders[questionId] = geocoder;


                    setTimeout(() => {
                        map.invalidateSize();
                    }, 100);
                },

                getCurrentLocation(questionId) {
                    if (!navigator.geolocation) {
                        alert('Geolocation is not supported by your browser');
                        return;
                    }

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            this.formData.answers[questionId].latitude = lat.toFixed(6);
                            this.formData.answers[questionId].longitude = lng.toFixed(6);

                            const marker = this.markers[questionId];
                            const map = this.maps[questionId];

                            if (marker && map) {
                                marker.setLatLng([lat, lng]);
                                map.setView([lat, lng], 15);
                                marker.bindPopup('Your current location').openPopup();
                            }

                            this.clearError(questionId);
                        },
                        (error) => {
                            alert('Unable to retrieve your location: ' + error.message);
                        }
                    );
                },

                resetForm() {
                    this.sections = [];
                    this.steps = [];
                    this.totalSteps = 0;
                    this.currentStep = 1;
                    this.formData = {
                        ward_id: null,
                        householder: {
                            householder_name: '',
                            father_name: '',
                            mother_name: '',
                            mother_tongue_id: '',
                            caste_id: '',
                            tole_id: '',
                            ward_no: '',
                            house_number: '',
                            phone_number: '',
                            citizenship_permanent_address_id: '',
                            profile_photo: null,
                            profile_photo_preview: '',
                            lot_number: ''
                        },
                        answers: {}
                    };
                    this.wardInfo = {};
                    this.lookupData = {
                        mother_tongues: [],
                        castes: [],
                        toles: [],
                        citizenship_permanent_addresses: []
                    };
                    this.householderErrors = {};
                    this.maps = {};
                    this.markers = {};
                    this.geocoders = {};
                },

                handleHouseholderFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        if (file.size > 2 * 1024 * 1024) {
                            alert('File size must be less than 2MB');
                            return;
                        }
                        if (!file.type.startsWith('image/')) {
                            alert('Please select an image file');
                            return;
                        }
                        this.formData.householder.profile_photo = file;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.formData.householder.profile_photo_preview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                        this.clearHouseholderError('profile_photo');
                    }
                    event.target.value = '';
                },

                handleHouseholderFileDrop(event) {
                    event.currentTarget.classList.remove('drag-over');
                    const file = event.dataTransfer.files[0];
                    if (file) {
                        if (file.size > 2 * 1024 * 1024) {
                            alert('File size must be less than 2MB');
                            return;
                        }
                        if (!file.type.startsWith('image/')) {
                            alert('Please select an image file');
                            return;
                        }
                        this.formData.householder.profile_photo = file;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.formData.householder.profile_photo_preview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                        this.clearHouseholderError('profile_photo');
                    }
                },

                removeHouseholderPhoto() {
                    this.formData.householder.profile_photo = null;
                    this.formData.householder.profile_photo_preview = '';
                },

                clearHouseholderError(field) {
                    delete this.householderErrors[field];
                },

                validateHouseholderStep() {
                    let isValid = true;
                    this.householderErrors = {};

                    const h = this.formData.householder;

                    if (!h.householder_name || h.householder_name.trim() === '') {
                        this.householderErrors.householder_name = 'Householder name is required';
                        isValid = false;
                    }

                    if (!h.father_name || h.father_name.trim() === '') {
                        this.householderErrors.father_name = 'Father name is required';
                        isValid = false;
                    }

                    if (!h.mother_name || h.mother_name.trim() === '') {
                        this.householderErrors.mother_name = 'Mother name is required';
                        isValid = false;
                    }

                    if (!h.mother_tongue_id || h.mother_tongue_id === '') {
                        this.householderErrors.mother_tongue_id = 'Mother tongue is required';
                        isValid = false;
                    }

                    if (!h.caste_id || h.caste_id === '') {
                        this.householderErrors.caste_id = 'Caste is required';
                        isValid = false;
                    }

                    if (!h.tole_id || h.tole_id === '') {
                        this.householderErrors.tole_id = 'Tole is required';
                        isValid = false;
                    }

                    if (!h.ward_no || h.ward_no === '') {
                        this.householderErrors.ward_no = 'Ward number is required';
                        isValid = false;
                    }

                    if (!h.lot_number || h.lot_number === '') {
                        this.householderErrors.lot_number = 'This field is required';
                        isValid = false;
                    }

                    if (!h.house_number || h.house_number.trim() === '') {
                        this.householderErrors.house_number = 'House number is required';
                        isValid = false;
                    }

                    if (!h.phone_number || h.phone_number.trim() === '') {
                        this.householderErrors.phone_number = 'Phone number is required';
                        isValid = false;
                    } else if (!/^\d{10}$/.test(h.phone_number)) {
                        this.householderErrors.phone_number = 'Phone number must be exactly 10 digits';
                        isValid = false;
                    }

                    if (!h.citizenship_permanent_address_id || h.citizenship_permanent_address_id === '') {
                        this.householderErrors.citizenship_permanent_address_id =
                            'Citizenship permanent address is required';
                        isValid = false;
                    }

                    return isValid;
                },

                handleFileSelect(event, questionId) {
                    const files = Array.from(event.target.files);
                    if (!this.formData.answers[questionId].files) {
                        this.formData.answers[questionId].files = [];
                    }
                    this.formData.answers[questionId].files.push(...files);
                    this.clearError(questionId);
                    event.target.value = '';
                },

                handleFileDrop(event, questionId) {
                    event.currentTarget.classList.remove('drag-over');
                    const files = Array.from(event.dataTransfer.files);
                    if (!this.formData.answers[questionId].files) {
                        this.formData.answers[questionId].files = [];
                    }
                    this.formData.answers[questionId].files.push(...files);
                    this.clearError(questionId);
                },

                removeFile(questionId, fileIndex) {
                    this.formData.answers[questionId].files.splice(fileIndex, 1);
                },

                formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
                },

                validateCurrentStep() {

                    if (this.currentStep === 1) {
                        return this.validateHouseholderStep();
                    }


                    const sectionIndex = this.currentStep - 2;
                    if (!this.sections[sectionIndex]) return true;

                    const currentSection = this.sections[sectionIndex];
                    let isValid = true;
                    this.errors = {};

                    currentSection.questions.forEach(question => {
                        const answer = this.formData.answers[question.id];

                        if (question.answer_required) {
                            let isEmpty = false;
                            const inputType = question.input_type?.input_type_name;

                            if (inputType === 'checkbox') {
                                isEmpty = !answer.question_option_id || answer.question_option_id.length === 0;
                            } else if (['radio', 'dropdown'].includes(inputType)) {
                                isEmpty = !answer.question_option_id || answer.question_option_id === '';
                            } else if (['number', 'linear_scale'].includes(inputType)) {
                                isEmpty = answer.answer_numeric === '' || answer.answer_numeric === null ||
                                    answer.answer_numeric === undefined;
                            } else if (inputType === 'file') {
                                isEmpty = !answer.files || answer.files.length === 0;
                            } else if (inputType === 'location') {
                                isEmpty = !answer.latitude || !answer.longitude ||
                                    answer.latitude === '' || answer.longitude === '';
                            } else {
                                isEmpty = !answer.answer_text || answer.answer_text.trim() === '';
                            }

                            if (isEmpty) {
                                this.errors[question.id] = 'This field is required';
                                isValid = false;
                            }
                        }
                    });

                    return isValid;
                },

                clearError(questionId) {
                    delete this.errors[questionId];
                },

                nextStep() {
                    if (this.validateCurrentStep()) {
                        if (this.currentStep < this.totalSteps) {
                            this.currentStep++;
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });

                            this.$nextTick(() => {
                                Object.values(this.maps).forEach(map => {
                                    map.invalidateSize();
                                });
                            });
                        }
                    } else {

                        const firstErrorElement = document.querySelector('.error-message');
                        if (firstErrorElement) {
                            firstErrorElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    }
                },

                prevStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });

                        this.$nextTick(() => {
                            Object.values(this.maps).forEach(map => {
                                map.invalidateSize();
                            });
                        });
                    }
                },

                navigateToStep(stepId) {
                    if (stepId > this.currentStep) {
                        for (let i = this.currentStep; i < stepId; i++) {
                            this.currentStep = i;
                            if (!this.validateCurrentStep()) {
                                return;
                            }
                        }
                    }
                    this.currentStep = stepId;
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });

                    this.$nextTick(() => {
                        Object.values(this.maps).forEach(map => {
                            map.invalidateSize();
                        });
                    });
                },

                async handleSubmit() {
                    if (!this.validateCurrentStep()) {
                        return;
                    }

                    this.isSubmitting = true;

                    try {
                        const formData = new FormData();
                        formData.append('ward_id', this.formData.ward_id);


                        const h = this.formData.householder;
                        formData.append('householder_name', h.householder_name);
                        formData.append('father_name', h.father_name);
                        formData.append('mother_name', h.mother_name);
                        formData.append('mother_tongue_id', h.mother_tongue_id);
                        formData.append('caste_id', h.caste_id);
                        formData.append('tole_id', h.tole_id);
                        formData.append('ward_no', h.ward_no);
                        formData.append('lot_number', h.lot_number);
                        formData.append('house_number', h.house_number);
                        formData.append('phone_number', h.phone_number);
                        formData.append('citizenship_permanent_address_id', h.citizenship_permanent_address_id);

                        if (h.profile_photo) {
                            formData.append('profile_photo', h.profile_photo);
                        }

                        Object.keys(this.formData.answers).forEach(questionId => {
                            const answer = this.formData.answers[questionId];

                            if (answer.files && answer.files.length > 0) {
                                answer.files.forEach((file, index) => {
                                    formData.append(`answers[${questionId}][files][${index}]`, file);
                                });
                            } else if (answer.latitude !== undefined && answer.longitude !== undefined) {
                                if (answer.latitude !== '' && answer.longitude !== '') {
                                    formData.append(`answers[${questionId}][latitude]`, answer.latitude);
                                    formData.append(`answers[${questionId}][longitude]`, answer.longitude);
                                }
                            } else if (answer.question_option_id !== undefined) {
                                if (answer.question_option_id === '' || answer.question_option_id === null) {
                                    return;
                                }

                                if (Array.isArray(answer.question_option_id)) {
                                    if (answer.question_option_id.length > 0) {
                                        answer.question_option_id.forEach((optionId, index) => {
                                            formData.append(
                                                `answers[${questionId}][question_option_id][${index}]`,
                                                optionId);

                                            if (answer.custom_inputs && answer.custom_inputs[
                                                    optionId]) {
                                                formData.append(
                                                    `answers[${questionId}][custom_inputs][${optionId}]`,
                                                    answer.custom_inputs[optionId]);
                                            }
                                        });
                                    }
                                } else {
                                    formData.append(`answers[${questionId}][question_option_id]`, answer
                                        .question_option_id);

                                    if (answer.custom_inputs && answer.custom_inputs[answer
                                            .question_option_id]) {
                                        formData.append(`answers[${questionId}][custom_input]`, answer
                                            .custom_inputs[answer.question_option_id]);
                                    }
                                }
                            } else if (answer.answer_text !== undefined && answer.answer_text !== '') {
                                formData.append(`answers[${questionId}][answer_text]`, answer.answer_text);
                            } else if (answer.answer_numeric !== undefined && answer.answer_numeric !== '' &&
                                answer.answer_numeric !== null) {
                                formData.append(`answers[${questionId}][answer_numeric]`, answer
                                    .answer_numeric);
                                if (answer.unit_of_measure_id && answer.unit_of_measure_id !== '') {
                                    formData.append(`answers[${questionId}][unit_of_measure_id]`, answer
                                        .unit_of_measure_id);
                                }
                            }
                        });

                        const response = await fetch('{{ route('house-description.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.showToast = true;
                            setTimeout(() => {
                                this.showToast = false;
                                this.selectedWardId = '';
                                this.resetForm();
                            }, 3000);
                        } else {
                            alert(data.message || 'An error occurred while submitting the survey.');
                        }
                    } catch (error) {
                        console.error('Submission error:', error);
                        alert('An error occurred while submitting the survey. Please try again.');
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            }
        }
    </script>
@endpush
