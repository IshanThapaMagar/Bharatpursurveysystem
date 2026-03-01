@props([
    'member',
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
    'self_relationship_id',
    'householderExists',
])
<x-app-layout>
    <div class="py-24">
        <div class="px-4 sm:px-6 lg:px-8 max-w-[100%]">
            <div class="">
                <div class="text-gray-900">
                    <x-housemember.edithousememberdetailform
                        :member="$member"
                        :genders="$genders"
                        :religions="$religions"
                        :bloodGroups="$bloodGroups"
                        :disabilities="$disabilities"
                        :districts="$districts"
                        :educationLevels="$educationLevels"
                        :governmentSupportTypes="$governmentSupportTypes"
                        :healthStatuses="$healthStatuses"
                        :institutionTypes="$institutionTypes"
                        :maritalStatuses="$maritalStatuses"
                        :motherTongueProficiencies="$motherTongueProficiencies"
                        :relationships="$relationships"
                        :specialSkills="$specialSkills"
                        :poolingPlaces="$poolingPlaces"
                        :self_relationship_id="$self_relationship_id"
                        :householderExists="$householderExists" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
