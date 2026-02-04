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
])

<div class="min-h-screen bg-gray-50 py-10 px-4">

    <form class="l shadow-lg px-20 space-y-10">

        <!-- Header -->
        <div class="border-b pb-4">
            <h2 class="text-2xl md:text-3xl font-semibold text-gray-800">
                {{ __('Fill in the details of family members') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ __('Please fill in all details correctly') }}
            </p>
        </div>


        <!-- Form Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">

            <!-- Name -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Full Name') }}</label>
                <input type="text"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>

            <!-- Relation -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('Relation') }}</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    name="relationship_id">
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
                <input type="date"
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
                <input type="tel"
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
                <input type="text"
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
                <input type="text"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="{{ __('Enter citizenship number') }}">
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
