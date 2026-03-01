@props([
    'genders',
    'religions',
    'bloodGroups',
    'disabilities',
    'districts',
    'educationLevels',
    'governmentSupportTypes',
    'healthStatuses',
    'institutionTypes',
    'maritalStatuses',
    'motherTongueProficiencies',
    'relationships',
    'specialSkills',
    'poolingPlaces',
    'householder_id',
    'householder',
    'self_relationship_id',
    'householderExists',
])
@push('styles')
    <link href="{{ asset('nepali.datepicker.v5.0.6/nepali.datepicker.v5.0.6.min.css') }}" rel="stylesheet">
@endpush

<div class="min-h-screen bg-gray-50 py-10 px-4" x-data="{
    isHouseHolder: false,
    householderExists: @json($householderExists),
    householderName: '{{ $householder?->householder_name ?? '' }}',
    selfRelationshipId: '{{ $self_relationship_id ?? '' }}',
    fullName: '',
    relationshipId: '',
    setHouseHolder(checked) {
        if (this.householderExists) return;
        this.isHouseHolder = checked;
        if (checked) {
            this.fullName = this.householderName;
            this.relationshipId = this.selfRelationshipId;
        } else {
            this.fullName = '';
            this.relationshipId = '';
        }
    }
}">
    <div class="fixed top-5 right-5 z-50 space-y-4 max-w-sm">
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.duration.500ms
                class="bg-green-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)" x-transition.duration.500ms
                class="bg-red-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 10000)" x-transition.duration.500ms
                class="bg-red-50 text-red-800 border-l-4 border-red-600 px-6 py-4 rounded-xl shadow-2xl">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-bold">{{ __('Kindly rectify the following errors:') }}</span>
                </div>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <form action="{{ route('house-member.store') }}" class="l shadow-lg px-20 space-y-10" method="POST">
        @csrf
        <input type="hidden" name="house_holder_id" value="{{ $householder_id }}">
        <div class="border-b pb-4 flex items-center justify-between">
            <div>
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-800">
                    {{ __('Fill in the details of family members') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ __('Please fill in all details correctly') }}
                </p>
            </div>


            <div class="flex flex-col items-end gap-1">
                <label
                    class="flex items-center gap-3 cursor-pointer select-none rounded-xl px-4 py-2 hover:bg-blue-100 transition"
                    :class="householderExists ? 'opacity-50 cursor-not-allowed' : ''">
                    <input type="checkbox" class="w-5 h-5 rounded accent-blue-600 cursor-pointer"
                        @change="setHouseHolder($event.target.checked)"
                        :disabled="householderExists">
                    <span class="text-sm font-semibold text-blue-700">{{ __('HouseHolder') }}</span>
                </label>
                <template x-if="householderExists">
                    <span class="text-[10px] text-red-500 font-medium bg-red-50 px-2 py-0.5 rounded border border-red-100">
                        {{ __('Householder information already added') }}
                    </span>
                </template>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">


            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Full Name') }}</label>
                <input type="text" name="full_name" x-model="fullName" :readonly="isHouseHolder"
                    :class="isHouseHolder ? 'bg-gray-100 cursor-not-allowed' : ''"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>


            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Relation') }}</label>

                <input type="hidden" name="relationship_id" x-bind:value="relationshipId" x-show="isHouseHolder">
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    x-bind:name="isHouseHolder ? '' : 'relationship_id'" x-model="relationshipId"
                    :disabled="isHouseHolder" :class="isHouseHolder ? 'bg-gray-100 cursor-not-allowed' : ''">
                    <option value="">{{ __('Select relation') }}</option>
                    @foreach ($relationships as $relationship)
                        <option value="{{ $relationship->id }}">
                            {{ $relationship->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- DOB -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Date of Birth') }}</label>
                <input type="text" name="dob" id="dob-nepali-datepicker" placeholder="YYYY/MM/DD"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Gender -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Gender') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="gender_id">
                    <option value="">{{ __('Select gender') }}</option>
                    @foreach ($genders as $gender)
                        <option value="{{ $gender->id }}">
                            {{ $gender->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Religion -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Religion') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="religion_id">
                    <option value="">{{ __('Select religion') }}</option>
                    @foreach ($religions as $religion)
                        <option value="{{ $religion->id }}">
                            {{ $religion->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Contact -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Contact Number') }}</label>
                <input type="tel" name="contact_number"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Marital Status -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Marital Status') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="marital_status_id">
                    <option value="">{{ __('Select marital status') }}</option>
                    @foreach ($maritalStatuses as $maritalStatus)
                        <option value="{{ $maritalStatus->id }}">
                            {{ $maritalStatus->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Education -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Institution Type') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="institution_type_id">
                    <option value="">{{ __('Select institution type') }}</option>
                    @foreach ($institutionTypes as $institutionType)
                        <option value="{{ $institutionType->id }}">
                            {{ $institutionType->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Education Level') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="education_level_id">
                    <option value="">{{ __('Select education level') }}</option>
                    @foreach ($educationLevels as $educationLevel)
                        <option value="{{ $educationLevel->id }}">
                            {{ $educationLevel->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Special Skill') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="special_skill_id">
                    <option value="">{{ __('Select special skill') }}</option>
                    @foreach ($specialSkills as $specialSkill)
                        <option value="{{ $specialSkill->id }}">
                            {{ $specialSkill->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col lg:col-span-2">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Government Support') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="government_support_type_id">
                    <option value="">{{ __('Select government support') }}</option>
                    @foreach ($governmentSupportTypes as $governmentSupportType)
                        <option value="{{ $governmentSupportType->id }}">
                            {{ $governmentSupportType->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Current Residence') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="district_id">
                    <option value="">{{ __('Select district') }}</option>
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}">
                            {{ $district->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Occupation/Employment') }}</label>
                <input type="text" name="occupation"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="{{ __('Enter occupation') }}">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Health Status') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="health_status_id">
                    <option value="">{{ __('Select health status') }}</option>
                    @foreach ($healthStatuses as $healthStatus)
                        <option value="{{ $healthStatus->id }}">
                            {{ $healthStatus->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Blood Group') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="blood_group_id">
                    <option value="">{{ __('Select blood group') }}</option>
                    @foreach ($bloodGroups as $bloodGroup)
                        <option value="{{ $bloodGroup->id }}">
                            {{ $bloodGroup->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col lg:col-span-2">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Citizenship Number') }}</label>
                <input type="text" name="citizenship_number"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="{{ __('Enter citizenship number') }}">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Citizenship issued district') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="citizenship_district_id">
                    <option value="">{{ __('Select district') }}</option>
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}">
                            {{ $district->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Permanent Account No.') }}</label>
                <input type="number" name="permanent_account_no"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('National Identity Card No.') }}</label>
                <input type="number" name="nid_no"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Disability') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="disability_id">
                    <option value="">{{ __('Select disability') }}</option>
                    @foreach ($disabilities as $disability)
                        <option value="{{ $disability->id }}">
                            {{ $disability->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Voter ID Card') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="has_voterId">
                    <option value=""></option>
                    <option value="1">{{ __('yes') }}</option>
                    <option value="0">{{ __('no') }}</option>

                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Polling Place') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="pooling_place_id">
                    <option value="">{{ __('Select pooling place') }}</option>
                    @foreach ($poolingPlaces as $poolingPlace)
                        <option value="{{ $poolingPlace->id }}">
                            {{ $poolingPlace->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Native speaker') }} ?</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="native_speaking_level">
                    <option value="">{{ __('Native speaker') }}</option>
                    @foreach ($motherTongueProficiencies as $proficiency)
                        <option value="{{ $proficiency->id }}">
                            {{ $proficiency->translations->first()?->name ?? '---' }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>


        <!-- Submit -->
        <div class="flex justify-end border-t pt-6">
            <button
                class="bg-blue-600 hover:bg-blue-700 active:scale-95 transition text-white font-medium px-10 py-3 rounded-xl shadow-md">
                {{ __('Submit') }}
            </button>
        </div>

    </form>
</div>
@push('scripts')
    <script src="{{ asset('nepali.datepicker.v5.0.6/nepali.datepicker.v5.0.6.min.js') }}"></script>
    <script>
        window.onload = function() {
            var mainInput = document.getElementById("dob-nepali-datepicker");
            mainInput.NepaliDatePicker({
                "miniEnglishDates": true,
            });
        };
    </script>
@endpush
