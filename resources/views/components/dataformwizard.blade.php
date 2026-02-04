@props(['wards' => null])
@push('styles')
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
    </style>
@endpush

<div x-data="surveyWizard()" class="px-12 sm:py-1">
    <div class="mb-8 animate-fade-in-up">
        <div class="w-52">
            <label for="ward-select" class="block text-sm font-semibold text-gray-900 mb-2">
                Select Ward <span class="required-asterisk">*</span>
            </label>
            <select id="ward-select" x-model="selectedWardId" @change="loadWardData()"
                class="block w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm  transition-all">
                <option value="">-- Select a Ward --</option>
                @foreach ($wards as $ward)
                    <option value="{{ $ward->id }} {{ old('ward_id') == $ward->id ? 'selected' : '' }}">Ward
                        {{ $ward->ward_no }}</option>
                @endforeach
            </select>
        </div>
    </div>

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

    <!-- Survey Form -->
    <div x-show="selectedWardId && !isLoading && sections.length > 0" x-cloak>
        <!-- Progress Bar -->
        <div class="mb-8 animate-fade-in-up">
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

                <!-- Right content area -->
                <div class="flex flex-col overflow-y-auto max-h-screen p-6 lg:p-10">
                    <form @submit.prevent="handleSubmit" class="flex flex-1 flex-col">
                        <div class="flex-1">
                            <!-- Dynamic Sections -->
                            <template x-for="(section, sIndex) in sections" :key="section.id">
                                <div x-show="currentStep === sIndex + 1"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform translate-y-4"
                                    x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>

                                    <div class="mb-8">
                                        <h2 class="text-3xl font-bold text-gray-900"
                                            style="font-family: 'Archivo', sans-serif;" x-text="section.title"></h2>
                                        <p x-show="section.description" class="mt-2 text-base text-gray-600"
                                            x-text="section.description"></p>
                                    </div>

                                    <div class="grid gap-6 grid-cols-1">
                                        <template x-for="question in section.questions" :key="question.id">
                                            <div
                                                class="rounded-xl border border-gray-200 bg-gray-50 p-6 transition-all hover:border-indigo-200 hover:bg-white">
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
                                                            <div class="mt-3 space-y-2">
                                                                <template x-for="qOption in question.question_options"
                                                                    :key="qOption.id">
                                                                    <label
                                                                        class="radio-card flex cursor-pointer items-center gap-3 rounded-lg border-2 border-gray-200 bg-white p-4 transition-all hover:border-indigo-300"
                                                                        :class="formData.answers[question.id]
                                                                            .question_option_id == qOption.id ?
                                                                            'border-indigo-600 bg-indigo-50' : ''">
                                                                        <input type="radio"
                                                                            x-model="formData.answers[question.id].question_option_id"
                                                                            @change="clearError(question.id)"
                                                                            :value="qOption.id"
                                                                            :required="question.answer_required"
                                                                            class="h-5 w-5 border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500">
                                                                        <span class="flex-1 font-medium text-gray-900"
                                                                            x-text="qOption.option_choice?.choice_text"></span>
                                                                    </label>
                                                                </template>
                                                            </div>
                                                        </template>
                                                        <!-- Linear Scale (Google Form style) -->
                                                        <template
                                                            x-if="question.input_type?.input_type_name === 'linear_scale'">
                                                            <div class="mt-6" @click.stop>
                                                                <div class="flex justify-between items-center mt-2">
                                                                    <!-- Low label -->
                                                                    <span class="text-xs text-gray-500 mr-2"
                                                                        x-text="question.scale_label_low || question.scale_from"></span>

                                                                    <!-- Radio buttons for scale -->
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
                                                                                    class="mb-1">

                                                                                <!-- Number below radio -->
                                                                                <span class="text-xs text-gray-600"
                                                                                    x-text="question.scale_from + n - 1"></span>
                                                                            </label>
                                                                        </div>
                                                                    </template>

                                                                    <!-- High label -->
                                                                    <span class="text-xs text-gray-500 ml-2"
                                                                        x-text="question.scale_label_high || question.scale_to"></span>
                                                                </div>
                                                            </div>
                                                        </template>
                                                        <!-- Checkboxes -->
                                                        <template
                                                            x-if="question.input_type?.input_type_name === 'checkbox'">
                                                            <div class="mt-3 space-y-2">
                                                                <template x-for="qOption in question.question_options"
                                                                    :key="qOption.id">
                                                                    <label
                                                                        class="checkbox-card flex cursor-pointer items-center gap-3 rounded-lg border-2 border-gray-200 bg-white p-4 transition-all hover:border-indigo-300"
                                                                        :class="formData.answers[question.id].question_option_id
                                                                            ?.includes(qOption.id) ?
                                                                            'border-indigo-600 bg-indigo-50' : ''">
                                                                        <input type="checkbox"
                                                                            x-model="formData.answers[question.id].question_option_id"
                                                                            @change="clearError(question.id)"
                                                                            :value="qOption.id"
                                                                            class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500">
                                                                        <span class="flex-1 font-medium text-gray-900"
                                                                            x-text="qOption.option_choice?.choice_text"></span>
                                                                    </label>
                                                                </template>
                                                            </div>
                                                        </template>

                                                        <!-- Dropdown / Select -->
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

                                                                <!-- File List -->
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
                        <div class="mt-8 flex items-center justify-between border-t border-gray-200 pt-6">
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
    <script>
        function surveyWizard() {
            return {
                selectedWardId: '',
                isLoading: false,
                currentStep: 1,
                totalSteps: 0,
                showToast: false,
                isSubmitting: false,
                errors: {},
                wardInfo: {},
                sections: [],
                steps: [],
                formData: {
                    ward_id: null,
                    answers: {}
                },

                init() {

                },

                async loadWardData() {
                    if (!this.selectedWardId) {
                        this.resetForm();
                        return;
                    }

                    this.isLoading = true;
                    this.currentStep = 1;
                    this.errors = {};

                    try {
                        const response = await fetch(`/survey/ward/${this.selectedWardId}/sections`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.wardInfo = data.ward;
                            this.sections = data.sections;
                            this.totalSteps = this.sections.length;
                            this.formData.ward_id = this.selectedWardId;


                            this.steps = this.sections.map((section, index) => ({
                                id: index + 1,
                                title: section.title,
                                description: section.description || ''
                            }));


                            this.formData.answers = {};
                            this.sections.forEach(section => {
                                section.questions.forEach(question => {
                                    const inputType = question.input_type?.input_type_name;

                                    if (inputType === 'checkbox') {
                                        this.formData.answers[question.id] = {
                                            question_option_id: []
                                        };
                                    } else if (['radio', 'dropdown'].includes(inputType)) {
                                        this.formData.answers[question.id] = {
                                            question_option_id: null
                                        };
                                    } else if (inputType === 'number') {
                                        this.formData.answers[question.id] = {
                                            answer_numeric: null,
                                            unit_of_measure_id: null
                                        };
                                    } else if (inputType === 'file') {
                                        this.formData.answers[question.id] = {
                                            files: []
                                        };
                                    } else {
                                        this.formData.answers[question.id] = {
                                            answer_text: ''
                                        };
                                    }
                                });
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

                resetForm() {
                    this.sections = [];
                    this.steps = [];
                    this.totalSteps = 0;
                    this.currentStep = 1;
                    this.formData = {
                        ward_id: null,
                        answers: {}
                    };
                    this.wardInfo = {};
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
                    if (!this.sections[this.currentStep - 1]) return true;

                    const currentSection = this.sections[this.currentStep - 1];
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
                                isEmpty = !answer.question_option_id;
                            } else if (['number', 'linear_scale'].includes(inputType)) {
                                this.formData.answers[question.id] = {
                                    answer_numeric: null
                                };
                            } else if (inputType === 'file') {
                                isEmpty = !answer.files || answer.files.length === 0;
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
                },

                async handleSubmit() {
                    if (!this.validateCurrentStep()) {
                        return;
                    }

                    this.isSubmitting = true;

                    try {
                        const formData = new FormData();
                        formData.append('ward_id', this.formData.ward_id);

                        Object.keys(this.formData.answers).forEach(questionId => {
                            const answer = this.formData.answers[questionId];

                            if (answer.files && answer.files.length > 0) {
                                answer.files.forEach((file, index) => {
                                    formData.append(`answers[${questionId}][files][${index}]`, file);
                                });
                            } else if (answer.question_option_id !== undefined) {
                                if (Array.isArray(answer.question_option_id)) {
                                    answer.question_option_id.forEach((optionId, index) => {
                                        formData.append(
                                            `answers[${questionId}][question_option_id][${index}]`,
                                            optionId);
                                    });
                                } else {
                                    formData.append(`answers[${questionId}][question_option_id]`, answer
                                        .question_option_id);
                                }
                            } else if (answer.answer_text !== undefined) {
                                formData.append(`answers[${questionId}][answer_text]`, answer.answer_text);
                            } else if (answer.answer_numeric !== undefined) {
                                formData.append(`answers[${questionId}][answer_numeric]`, answer
                                    .answer_numeric);
                                if (answer.unit_of_measure_id) {
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
                        console.log('Response from server:', data);

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
