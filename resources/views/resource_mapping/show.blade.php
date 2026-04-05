<x-app-layout>
    <div class="py-12 sm:py-24 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                    {{ __('Resource Mapping Details') }}
                </h2>
                <p class="mt-1 text-xs text-gray-500">
                    {{ __('View physical resource mapping details for the selected ward/tole.') }}
                </p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('resource-mapping.index') }}"
                    class="text-xs font-bold text-gray-500 hover:text-gray-700 uppercase tracking-wider flex items-center pr-4 border-r border-gray-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back to List') }}
                </a>
                <a href="{{ route('resource-mapping.edit', $resourceMapping->id) }}"
                    class="text-xs font-bold text-blue-600 hover:text-blue-800 uppercase tracking-wider flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ __('Edit') }}
                </a>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Location Details -->
            <div class="bg-white shadow-sm border border-gray-100 overflow-hidden rounded-sm">
                <div class="p-5 sm:p-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b pb-2">
                        {{ __('Location Information') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Ward') }}</span>
                            <div class="text-sm font-medium text-gray-900">Ward No. {{ $resourceMapping->ward->ward_no }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Tole') }}</span>
                            <div class="text-sm font-medium text-gray-900">{{ $resourceMapping->tole->name }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General Mapping Details -->
            <div class="bg-white shadow-sm border border-gray-100 overflow-hidden rounded-sm">
                <div class="p-5 sm:p-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b pb-2">
                        {{ __('General Mapping Details') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Electricity Pole Number') }}</span>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $resourceMapping->electricity_pole_number ?: __('N/A') }}
                            </div>
                        </div>

                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Tole Development Office Type') }}</span>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $resourceMapping->toleDevelopmentOfficeType ? $resourceMapping->toleDevelopmentOfficeType->name : __('None') }}
                            </div>
                        </div>

                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Nala Nikash') }}</span>
                            <div class="mt-1">
                                @if($resourceMapping->nala_nikash)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest bg-green-50 text-green-700 border border-green-100">
                                        {{ __('Yes') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest bg-gray-50 text-gray-500 border border-gray-200">
                                        {{ __('No') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Sections for Poles and Roads -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Pole Types -->
                <div class="bg-white shadow-sm border border-gray-100 overflow-hidden rounded-sm">
                    <div class="p-5">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b pb-2">
                            {{ __('Pole Types Quantities') }}
                        </h3>
                        <div class="space-y-3">
                            @forelse ($resourceMapping->poleTypes as $pole)
                                <div class="flex items-center justify-between gap-4 bg-gray-50 p-3 rounded-sm border border-gray-100">
                                    <span class="text-xs font-semibold text-gray-700">{{ $pole->name }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $pole->pivot->quantity }}</span>
                                </div>
                            @empty
                                <div class="text-xs text-gray-400 italic text-center py-4">{{ __('No pole types recorded') }}</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Road Types -->
                <div class="bg-white shadow-sm border border-gray-100 overflow-hidden rounded-sm">
                    <div class="p-5">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b pb-2">
                            {{ __('Road Types Length (m)') }}
                        </h3>
                        <div class="space-y-3">
                            @forelse ($resourceMapping->roadTypes as $road)
                                <div class="flex items-center justify-between gap-4 bg-gray-50 p-3 rounded-sm border border-gray-100">
                                    <span class="text-xs font-semibold text-gray-700">{{ $road->name }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $road->pivot->length }} {{ __('m') }}</span>
                                </div>
                            @empty
                                <div class="text-xs text-gray-400 italic text-center py-4">{{ __('No road types recorded') }}</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
