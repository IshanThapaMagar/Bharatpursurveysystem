<x-app-layout>
    <div class="py-8">
        <div class="px-4 sm:px-6 lg:px-8 max-w-[100%]">
            <div class="text-gray-900">
                <x-dataformwizard
                    :wards="$wards"
                    :wardInfo="$wardInfo"
                    :sections="$sections"
                    :lookupData="$lookupData"
                />
            </div>
        </div>
    </div>
</x-app-layout>
