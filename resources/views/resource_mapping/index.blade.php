<x-app-layout>
    <div class="py-12 sm:py-24 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">{{ __('Resource Mapping Directory') }}</h2>
                <p class="mt-1 text-xs text-gray-500 uppercase tracking-widest font-semibold">{{ __('Physical infrastructure management') }}</p>
            </div>
            <a href="{{ route('resource-mapping.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-sm font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition ease-in-out duration-150 shadow-md">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Add Mapping') }}
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-sm shadow-sm flex items-center">
                <svg class="h-5 w-5 text-green-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-bold text-green-800 uppercase tracking-wider">{{ session('success') }}</span>
            </div>
        @endif

        <!-- List Table -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-sm overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ __('Ward/Tole') }}</th>
                            <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ __('Poles No.') }}</th>
                            <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ __('Nala Nikash') }}</th>
                            <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($resourceMappings as $mapping)
                            <tr class="hover:bg-gray-50/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-gray-900">Ward {{ $mapping->ward->ward_no }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $mapping->tole->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-mono text-gray-700">{{ $mapping->electricity_pole_number ?: 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($mapping->nala_nikash)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[8px] font-bold uppercase tracking-widest bg-green-50 text-green-700 border border-green-100">
                                            {{ __('Yes') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[8px] font-bold uppercase tracking-widest bg-gray-50 text-gray-400 border border-gray-100">
                                            {{ __('No') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-3 transition-opacity">
                                        <a href="{{ route('resource-mapping.show', $mapping->id) }}" class="p-1.5 text-gray-400 hover:text-green-600 transition-colors" title="{{ __('View') }}">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('resource-mapping.edit', $mapping->id) }}" class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors" title="{{ __('Edit') }}">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('resource-mapping.destroy', $mapping->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition-colors">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
@empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="h-12 w-12 text-gray-100 mb-2">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <p class="text-xs font-bold text-gray-300 uppercase tracking-widest">{{ __('No resource mappings found') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($resourceMappings->hasPages())
                <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                    {{ $resourceMappings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
