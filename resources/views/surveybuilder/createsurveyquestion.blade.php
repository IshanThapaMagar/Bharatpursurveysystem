<x-app-layout>
    <div class="py-24">
        <div class="px-4 sm:px-6 lg:px-8 max-w-[100%]">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h1 class="text-3xl font-bold mb-8">{{ __('Survey Section Editor') }}</h1>

                    <form x-data="surveyBuilder()" @submit.prevent="submitForm" action="{{ route('surveyform.store') }}"
                        class="space-y-4" method="POST">
                        @csrf

                        {{-- Hidden inputs for JSON submission --}}
                        <input type="hidden" name="survey_data" x-ref="surveyDataInput">
                        <input type="hidden" name="ward_id" x-ref="wardIdInput">

                        <div class="fixed top-20 right-4 flex flex-col gap-2 z-50">

                            {{-- Success Message --}}
                            @if (session('success'))
                                <div class="max-w-xs w-full p-4 bg-layer border border-layer-line rounded-xl shadow-lg"
                                    role="alert" tabindex="-1" aria-labelledby="hs-toast-success-label">
                                    <div class="flex gap-x-3">
                                        <svg class="shrink-0 size-4 text-teal-500 mt-0.5"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                        </svg>
                                        <div class="grow">
                                            <p id="hs-toast-success-label" class="text-sm text-layer-foreground">
                                                {{ session('success') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Error Message --}}
                            @if (session('error'))
                                <div class="max-w-xs w-full p-4 bg-layer border border-layer-line rounded-xl shadow-lg"
                                    role="alert" tabindex="-1" aria-labelledby="hs-toast-error-label">
                                    <div class="flex gap-x-3">
                                        <svg class="shrink-0 size-4 text-red-500 mt-0.5"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                                        </svg>
                                        <div class="grow">
                                            <p id="hs-toast-error-label" class="text-sm text-layer-foreground">
                                                {{ session('error') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Validation Errors --}}
                            @if ($errors->any())
                                <div class="max-w-xs w-full p-4 bg-layer border border-layer-line rounded-xl shadow-lg"
                                    role="alert" tabindex="-1" aria-labelledby="hs-toast-validation-label">
                                    <div class="flex gap-x-3">
                                        <svg class="shrink-0 size-4 text-red-500 mt-0.5"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                                        </svg>
                                        <div class="grow">
                                            <ul id="hs-toast-validation-label"
                                                class="text-sm text-layer-foreground list-disc pl-5">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>



                        <div class="mb-4">
                            <label for="ward_id" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('For which ward is this survey?') }}
                            </label>
                            <select name="ward_id" id="ward_id"
                                class="block w-64 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-3 py-2 text-gray-900">
                                @if(auth()->user()->isSuperAdmin())
                                    <option value="all" {{ old('ward_id', 'all') == 'all' ? 'selected' : '' }}>
                                        {{ __('All Wards') }}</option>
                                @endif
                                @foreach ($wards as $ward)
                                    <option value="{{ $ward->id }}"
                                        {{ old('ward_id') == $ward->id ? 'selected' : '' }}>
                                        {{ $ward->ward_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="sections-container">
                            <template x-for="(section, sectionIndex) in sections" :key="section.id">
                                <div :data-section-id="section.id" class="bg-white rounded-lg shadow mb-4 section-item">
                                    <!-- Section Header -->
                                    <div class="p-4 border-b">
                                        <div class="flex items-center gap-2">
                                            <!-- Drag Handle -->
                                            <button class="cursor-grab hover:bg-gray-100 p-1 rounded section-handle"
                                                type="button">
                                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M4 8h16M4 16h16" />
                                                </svg>
                                            </button>

                                            <!-- Collapse Toggle -->
                                            <button @click="toggleSection(section.id)"
                                                class="hover:bg-gray-100 p-1 rounded" type="button">
                                                <svg class="h-4 w-4 transition-transform"
                                                    :class="section.isOpen ? '' : '-rotate-90'" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <!-- Section Title Input -->
                                            <div class="flex-1 min-w-0">
                                                <input type="text" :value="section.title"
                                                    @input="updateSection(section.id, 'title', $event.target.value)"
                                                    class="w-full font-semibold border-none shadow-none px-0 focus:outline-none focus:ring-0"
                                                    placeholder="{{ __('Section title') }}" required />
                                            </div>

                                            <!-- Delete Section Button -->
                                            <button @click="deleteSection(section.id)"
                                                class="hover:bg-gray-100 p-2 rounded text-red-600 hover:text-red-700"
                                                type="button">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Section Content (Collapsible) -->
                                    <div x-show="section.isOpen" x-collapse>
                                        <div class="p-4">
                                            <!-- Section Description -->
                                            <div class="mb-4">
                                                <label
                                                    class="text-sm text-gray-600 block mb-1">{{ __('Section description') }}</label>
                                                <textarea :value="section.description || ''" @input="updateSection(section.id, 'description', $event.target.value)"
                                                    placeholder="{{ __('Optional description for this section') }}" rows="2"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                            </div>

                                            <!-- Questions -->
                                            <div class="space-y-3">
                                                <div :id="'questions-' + section.id"
                                                    class="questions-container space-y-3">
                                                    <template
                                                        x-for="(question, questionIndex) in getQuestions(section.id)"
                                                        :key="question.id">
                                                        <div :data-question-id="question.id"
                                                            class="border border-gray-200 rounded-lg p-4 bg-gray-50 question-item">
                                                            <div class="flex items-start gap-2">
                                                                <!-- Question Drag Handle -->
                                                                <button
                                                                    class="cursor-grab hover:bg-gray-200 p-1 rounded mt-1 question-handle"
                                                                    type="button">
                                                                    <svg class="h-4 w-4 text-gray-400" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 8h16M4 16h16" />
                                                                    </svg>
                                                                </button>

                                                                <!-- Question Content -->
                                                                <div class="flex-1 min-w-0">
                                                                    <div class="flex items-center gap-2 mb-2">
                                                                        <span
                                                                            class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded"
                                                                            x-text="getTypeLabel(question.type)">
                                                                        </span>
                                                                        <div x-show="question.type !== 'text'" x-cloak>
                                                                            <input type="checkbox"
                                                                                :checked="question.required"
                                                                                @change="updateQuestion(question.id, 'required', $event.target.checked)"
                                                                                class="rounded"
                                                                                :id="'required-' + question.id" />
                                                                            <label :for="'required-' + question.id"
                                                                                class="text-sm text-gray-600">{{ __('Required') }}</label>
                                                                        </div>
                                                                    </div>

                                                                    <input type="text" :value="question.label"
                                                                        @input="updateQuestion(question.id, 'label', $event.target.value)"
                                                                        :placeholder="question.type === 'text' ?
                                                                            '{{ __('Title') }}' :
                                                                            '{{ __('Question text') }}'"
                                                                        class="w-full mb-2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                                        required />

                                                                    <textarea :value="question.description || ''" @input="updateQuestion(question.id, 'description', $event.target.value)"
                                                                        :placeholder="question.type === 'text' ?
                                                                            '{{ __('Description') }}' :
                                                                            '{{ __('Optional description') }}'"
                                                                        rows="2"
                                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

                                                                    <!-- Options for select-type questions -->
                                                                    <div x-show="['radio', 'checkbox', 'dropdown', 'multiple_choice'].includes(question.type)"
                                                                        class="mt-3" x-cloak>
                                                                        <div class="text-sm font-medium mb-2">Options
                                                                        </div>
                                                                        <div class="space-y-2">
                                                                            <template
                                                                                x-for="(option, optionIndex) in (question.options || [])"
                                                                                :key="option.id">
                                                                                <div class="flex gap-2 items-center">
                                                                                    <!-- Option Label Input -->
                                                                                    <input type="text"
                                                                                        :value="option.label"
                                                                                        @input="updateQuestionOption(question.id, option.id, 'label', $event.target.value)"
                                                                                        placeholder="Option label"
                                                                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm"
                                                                                        required />

                                                                                    <!-- Custom Input Field (visible only for radio and checkbox) -->
                                                                                    <div x-show="['radio', 'checkbox'].includes(question.type)"
                                                                                        class="flex-1 flex items-center gap-2">
                                                                                        <select
                                                                                            :value="option.input_type || 'none'"
                                                                                            @change="updateQuestionOption(question.id, option.id, 'input_type', $event.target.value)"
                                                                                            class="px-3 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                                                                            <option value="none">No
                                                                                                Input
                                                                                            </option>
                                                                                            <option value="text">Text
                                                                                                Input</option>
                                                                                            <option value="number">
                                                                                                Number
                                                                                                Input</option>
                                                                                        </select>

                                                                                        <input type="text"
                                                                                            x-show="option.input_type && option.input_type !== 'none'"
                                                                                            :value="option.input_placeholder ||
                                                                                                ''"
                                                                                            @input="updateQuestionOption(question.id, option.id, 'input_placeholder', $event.target.value)"
                                                                                            placeholder="Input placeholder"
                                                                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm" />
                                                                                    </div>

                                                                                    <!-- Delete Button -->
                                                                                    <button
                                                                                        @click="removeQuestionOption(question.id, option.id)"
                                                                                        class="text-red-600 hover:text-red-700 p-1 hover:bg-red-50 rounded flex-shrink-0"
                                                                                        type="button">
                                                                                        <svg class="h-4 w-4"
                                                                                            fill="none"
                                                                                            viewBox="0 0 24 24"
                                                                                            stroke="currentColor">
                                                                                            <path
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round"
                                                                                                stroke-width="2"
                                                                                                d="M6 18L18 6M6 6l12 12" />
                                                                                        </svg>
                                                                                    </button>
                                                                                </div>
                                                                            </template>
                                                                            <button
                                                                                @click="addQuestionOption(question.id)"
                                                                                class="text-sm text-blue-600 hover:text-blue-700"
                                                                                type="button">
                                                                                + Add option
                                                                            </button>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Linear Scale Settings -->
                                                                    <div x-show="question.type === 'linear_scale'"
                                                                        class="mt-3 space-y-3" x-cloak>
                                                                        <div class="text-sm font-medium mb-2">Scale
                                                                            Settings</div>

                                                                        <div class="grid grid-cols-2 gap-3">
                                                                            <div>
                                                                                <label
                                                                                    class="text-sm text-gray-600 block mb-1">Scale
                                                                                    From</label>
                                                                                <select :value="question.scale_from"
                                                                                    @change="updateQuestion(question.id, 'scale_from', parseInt($event.target.value))"
                                                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                                                                    <template x-for="num in [0, 1]"
                                                                                        :key="num">
                                                                                        <option :value="num"
                                                                                            :selected="(question.scale_from ||
                                                                                                1) === num"
                                                                                            x-text="num"></option>
                                                                                    </template>
                                                                                </select>
                                                                            </div>

                                                                            <div>
                                                                                <label
                                                                                    class="text-sm text-gray-600 block mb-1">Scale
                                                                                    To</label>
                                                                                <select :value="question.scale_to"
                                                                                    @change="updateQuestion(question.id, 'scale_to', parseInt($event.target.value))"
                                                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                                                                    <template
                                                                                        x-for="num in [2,3,4,5,6,7,8,9,10]"
                                                                                        :key="num">
                                                                                        <option :value="num"
                                                                                            :selected="(question.scale_to || 5) ===
                                                                                            num"
                                                                                            x-text="num"></option>
                                                                                    </template>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div>
                                                                            <label
                                                                                class="text-sm text-gray-600 block mb-1">Label
                                                                                for lowest value (optional)</label>
                                                                            <input type="text"
                                                                                :value="question.scale_label_low || ''"
                                                                                @input="updateQuestion(question.id, 'scale_label_low', $event.target.value)"
                                                                                placeholder=""
                                                                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                                                                        </div>

                                                                        <div>
                                                                            <label
                                                                                class="text-sm text-gray-600 block mb-1">Label
                                                                                for highest value (optional)</label>
                                                                            <input type="text"
                                                                                :value="question.scale_label_high || ''"
                                                                                @input="updateQuestion(question.id, 'scale_label_high', $event.target.value)"
                                                                                placeholder=""
                                                                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Delete Question Button -->
                                                                <button @click.prevent="deleteQuestion(question.id)"
                                                                    class="text-red-600 hover:text-red-700 p-1 hover:bg-red-50 rounded"
                                                                    type="button" title="Delete question">
                                                                    <svg class="h-4 w-4" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>

                                                <!-- Add Question Button -->
                                                <button @click="openQuestionTypeDialog(section.id)"
                                                    class="w-full border-2 border-dashed border-gray-300 rounded-lg py-3 text-gray-600 hover:border-gray-400 hover:text-gray-700 transition-colors"
                                                    type="button">
                                                    <svg class="h-4 w-4 inline mr-2" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    {{ __('Add Question') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Add Section Button -->
                        <button @click="addSection()"
                            class="w-full border-2 border-dashed border-gray-300 rounded-lg py-4 text-gray-600 hover:border-gray-400 hover:text-gray-700 transition-colors bg-white"
                            type="button">
                            <svg class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Add Section') }}
                        </button>

                        <!-- Submit Button -->
                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                {{ __('Save Survey') }}
                            </button>
                        </div>

                        <!-- Question Type Selector Modal -->
                        <div x-show="showQuestionTypeDialog" x-cloak
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                            @click.self="showQuestionTypeDialog = false">
                            <div
                                class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-auto">
                                <div class="p-6 border-b">
                                    <h2 class="text-xl font-bold">{{ __('Choose Question Type') }}</h2>
                                </div>
                                <div class="p-6 grid grid-cols-2 gap-3">
                                    <template x-for="type in questionTypes" :key="type.value">
                                        <button @click="addQuestion(type.value)"
                                            class="p-4 border border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors text-left"
                                            type="button">
                                            <div class="font-medium" x-text="type.label"></div>
                                            <div class="text-sm text-gray-600 mt-1" x-text="type.description"></div>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            window.t = @json(trans('messages'));

            function surveyBuilder() {
                const t = window.t;

                return {
                    sections: [],
                    questions: [],
                    showQuestionTypeDialog: false,
                    currentSectionId: null,
                    sectionSortable: null,
                    questionSortables: {},
                    nextSectionId: 1,
                    nextQuestionId: 1,
                    questionTypes: [{
                            value: 'text',
                            label: t.text || 'Text',
                            description: t.text_description || 'Add heading or description'
                        },
                        {
                            value: 'short_text',
                            label: t.short_text || 'Short Text',
                            description: t.single_line || 'Single line response'
                        },
                        {
                            value: 'long_text',
                            label: t.long_text || 'Long Text',
                            description: t.multi_line || 'Multi-line response'
                        },
                        {
                            value: 'email',
                            label: t.email || 'Email',
                            description: t.email_input || 'Email input'
                        },
                        {
                            value: 'number',
                            label: t.number || 'Number',
                            description: t.numeric_input || 'Numeric input'
                        },
                        {
                            value: 'date',
                            label: t.date || 'Date',
                            description: t.date_picker || 'Date picker'
                        },
                        {
                            value: 'radio',
                            label: t.radio || 'Multiple Choice',
                            description: t.select_one || 'Select one option'
                        },
                        {
                            value: 'checkbox',
                            label: t.checkboxes || 'Checkboxes',
                            description: t.select_multiple || 'Select multiple options'
                        },
                        {
                            value: 'dropdown',
                            label: t.dropdown || 'Dropdown',
                            description: t.select_dropdown || 'Select from dropdown'
                        },
                        {
                            value: 'file',
                            label: t.file_upload || 'File Upload',
                            description: t.file_upload || 'Upload a file'
                        },
                        {
                            value: 'linear_scale',
                            label: t.linear_scale || 'Linear Scale',
                            description: t.linear_scale_description || 'Scale from 1 to a number'
                        },
                        {
                            value: 'location',
                            label: t.location || 'Location/GPS',
                            description: t.location_description || 'Interactive map with coordinates'
                        }
                    ],

                    init() {
                        this.loadOldValues();

                        this.$nextTick(() => {
                            this.initializeSortable();
                        });
                    },
                    loadOldValues() {
                        @if (old('sections'))
                            const oldSections = @json(old('sections'));
                            const oldQuestions = @json(old('questions'));


                            if (oldSections) {
                                Object.values(oldSections).forEach(section => {
                                    this.sections.push({
                                        id: section.id,
                                        title: section.title || '',
                                        description: section.description || '',
                                        isOpen: true
                                    });


                                    const numericId = parseInt(section.id.replace('s', ''));
                                    if (!isNaN(numericId) && numericId >= this.nextSectionId) {
                                        this.nextSectionId = numericId + 1;
                                    }
                                });
                            }


                            if (oldQuestions) {
                                Object.entries(oldQuestions).forEach(([questionId, question]) => {
                                    const newQuestion = {
                                        id: questionId,
                                        sectionId: question.section_id,
                                        type: question.type,
                                        label: question.label || '',
                                        description: question.description || '',
                                        required: question.required == '1' || question.required === true
                                    };

                                    // Load linear scale settings
                                    if (question.type === 'linear_scale') {
                                        newQuestion.scale_from = parseInt(question.scale_from) || 1;
                                        newQuestion.scale_to = parseInt(question.scale_to) || 5;
                                        newQuestion.scale_label_low = question.scale_label_low || '';
                                        newQuestion.scale_label_high = question.scale_label_high || '';
                                    }

                                    if (question.options) {
                                        newQuestion.options = Object.values(question.options).map(opt => ({
                                            id: opt.id,
                                            value: opt.value || '',
                                            label: opt.label || '',
                                            input_type: opt.input_type || 'none',
                                            input_placeholder: opt.input_placeholder || ''
                                        }));
                                    }

                                    this.questions.push(newQuestion);


                                    const numericId = parseInt(questionId.replace('q', ''));
                                    if (!isNaN(numericId) && numericId >= this.nextQuestionId) {
                                        this.nextQuestionId = numericId + 1;
                                    }
                                });
                            }
                        @endif


                        @if (old('ward_id'))
                            this.$nextTick(() => {
                                const wardSelect = document.getElementById('ward_id');
                                if (wardSelect) {
                                    wardSelect.value = "{{ old('ward_id') }}";
                                }
                            });
                        @endif
                    },

                    initializeSortable() {
                        const sectionsContainer = document.getElementById('sections-container');
                        if (sectionsContainer && !this.sectionSortable) {
                            this.sectionSortable = new Sortable(sectionsContainer, {
                                animation: 150,
                                handle: '.section-handle',
                                ghostClass: 'sortable-ghost',
                                dragClass: 'sortable-drag',
                                onEnd: (evt) => {
                                    const newOrder = Array.from(sectionsContainer.children).map((
                                        el) => {
                                        const sectionId = el.getAttribute('data-section-id');
                                        return this.sections.find(s => s.id === sectionId);
                                    });
                                    this.sections = newOrder.filter(Boolean);
                                }
                            });
                        }

                        this.sections.forEach(section => {
                            const questionsContainer = document.getElementById(`questions-${section.id}`);
                            if (questionsContainer && !this.questionSortables[section.id]) {
                                this.questionSortables[section.id] = new Sortable(questionsContainer, {
                                    animation: 150,
                                    handle: '.question-handle',
                                    ghostClass: 'sortable-ghost',
                                    dragClass: 'sortable-drag',
                                    onEnd: (evt) => {
                                        const newOrder = Array.from(questionsContainer.children)
                                            .map((
                                                el) => {
                                                const questionId = el.getAttribute(
                                                    'data-question-id');
                                                return this.questions.find(q => q.id ===
                                                    questionId);
                                            });

                                        const otherQuestions = this.questions.filter(q => q
                                            .sectionId !==
                                            section.id);
                                        this.questions = [...otherQuestions, ...newOrder.filter(
                                            Boolean)];
                                    }
                                });
                            }
                        });
                    },

                    getQuestions(sectionId) {
                        return this.questions.filter(q => q.sectionId === sectionId);
                    },

                    toggleSection(sectionId) {
                        const section = this.sections.find(s => s.id === sectionId);
                        if (section) {
                            section.isOpen = !section.isOpen;
                        }
                    },

                    updateSection(sectionId, field, value) {
                        const section = this.sections.find(s => s.id === sectionId);
                        if (section) {
                            section[field] = value;
                        }
                    },

                    deleteSection(sectionId) {
                        if (confirm(t.delete_confirm)) {
                            this.sections = this.sections.filter(s => s.id !== sectionId);
                            this.questions = this.questions.filter(q => q.sectionId !== sectionId);

                            if (this.questionSortables[sectionId]) {
                                this.questionSortables[sectionId].destroy();
                                delete this.questionSortables[sectionId];
                            }
                        }
                    },

                    addSection() {
                        const newId = 's' + this.nextSectionId++;
                        this.sections.push({
                            id: newId,
                            title: '',
                            description: '',
                            isOpen: true
                        });

                        this.$nextTick(() => {
                            this.initializeSortable();
                        });
                    },

                    openQuestionTypeDialog(sectionId) {
                        this.currentSectionId = sectionId;
                        this.showQuestionTypeDialog = true;
                    },

                    addQuestion(type) {
                        const newId = 'q' + this.nextQuestionId++;

                        const newQuestion = {
                            id: newId,
                            sectionId: this.currentSectionId,
                            type: type,
                            label: '',
                            description: '',
                            required: false

                        };

                        if (['radio', 'checkbox', 'dropdown', 'multiple_choice'].includes(type)) {
                            newQuestion.options = [{
                                    id: 'opt1',
                                    value: '',
                                    label: '',

                                    input_type: 'none',
                                    input_placeholder: ''
                                },
                                {
                                    id: 'opt2',
                                    value: '',
                                    label: '',

                                    input_type: 'none',
                                    input_placeholder: ''
                                }
                            ];
                        }

                        // Initialize linear scale defaults
                        if (type === 'linear_scale') {
                            newQuestion.scale_from = 1;
                            newQuestion.scale_to = 5;
                            newQuestion.scale_label_low = '';
                            newQuestion.scale_label_high = '';
                        }

                        this.questions.push(newQuestion);
                        this.showQuestionTypeDialog = false;
                        this.currentSectionId = null;

                        this.$nextTick(() => {
                            this.initializeSortable();
                        });
                    },

                    updateQuestion(questionId, field, value) {
                        const question = this.questions.find(q => q.id === questionId);
                        if (question) {
                            question[field] = value;
                        }
                    },

                    deleteQuestion(questionId) {

                        const question = this.questions.find(q => q.id === questionId);
                        const sectionId = question?.sectionId;

                        const newQuestions = this.questions.filter(q => q.id !== questionId);
                        this.questions = newQuestions;


                        if (sectionId && this.questionSortables[sectionId]) {
                            this.$nextTick(() => {
                                this.questionSortables[sectionId].destroy();
                                delete this.questionSortables[sectionId];
                                this.initializeSortable();
                            });
                        }

                    },

                    addQuestionOption(questionId) {
                        const question = this.questions.find(q => q.id === questionId);
                        if (question && question.options) {
                            const newId = 'opt' + (question.options.length + 1);
                            question.options.push({
                                id: newId,
                                value: 'option' + (question.options.length + 1),
                                label: '',

                                input_type: 'none',
                                input_placeholder: ''
                            });
                        }
                    },

                    updateQuestionOption(questionId, optionId, field, value) {
                        const question = this.questions.find(q => q.id === questionId);
                        if (question && question.options) {
                            const option = question.options.find(o => o.id === optionId);
                            if (option) {
                                option[field] = value;
                                if (field === 'label') {
                                    option.value = value.toLowerCase().replace(/\s+/g, '_');
                                }
                            }
                        }
                    },

                    removeQuestionOption(questionId, optionId) {
                        const question = this.questions.find(q => q.id === questionId);
                        if (question && question.options) {
                            question.options = question.options.filter(o => o.id !== optionId);
                        }
                    },
                    getTypeLabel(type) {
                        return t[type] || type;
                    },
                    collectFormData() {
                        return {
                            sections: this.sections.map(section => ({
                                id: section.id,
                                title: section.title,
                                description: section.description,
                                questions: this.getQuestions(section.id).map(question => ({
                                    id: question.id,
                                    type: question.type,
                                    label: question.label,
                                    description: question.description,
                                    required: question.required,
                                    options: question.options || [],
                                    scale_from: question.scale_from,
                                    scale_to: question.scale_to,
                                    scale_label_low: question.scale_label_low,
                                    scale_label_high: question.scale_label_high
                                }))
                            }))
                        };
                    },
                    submitForm(event) {
                        const surveyData = {
                            sections: this.sections,
                            questions: this.questions
                        };
                        this.$refs.surveyDataInput.value = JSON.stringify(surveyData);

                        const wardSelect = document.getElementById('ward_id');
                        this.$refs.wardIdInput.value = wardSelect ? wardSelect.value : '';

                        event.target.submit();
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
