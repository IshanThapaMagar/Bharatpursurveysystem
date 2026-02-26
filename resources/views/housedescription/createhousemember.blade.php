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
])
<x-app-layout>
    <div class="py-24">
        <div class="ml-56 ">
            <div class="">
                <div class="text-gray-900">
                    <x-housemember.addhousememberdetailform :genders="$genders" :religions="$religions" :bloodGroups="$bloodGroups"
                        :disabilities="$disabilities" :districts="$districts" :educationLevels="$educationLevels" :governmentSupportTypes="$governmentSupportTypes" :healthStatuses="$healthStatuses"
                        :institutionTypes="$institutionTypes" :maritalStatuses="$maritalStatuses" :motherTongueProficiencies="$motherTongueProficiencies" :relationships="$relationships" :specialSkills="$specialSkills"
                        :poolingPlaces="$poolingPlaces" :householder_id="$householder_id" :householder="$householder" :self_relationship_id="$self_relationship_id" />

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
