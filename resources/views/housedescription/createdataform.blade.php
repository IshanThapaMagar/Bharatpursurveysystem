<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 to-indigo-50 py-16">
        <div x-data="{ selectedWardId: '' }" class="w-full max-w-md mx-auto px-4">

    
            <div class="bg-white rounded-2xl shadow-2xl p-10 text-center">


                <div class="flex justify-center mb-6">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-600 shadow-lg">
                        <svg class="h-9 w-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-2xl font-bold text-gray-900 mb-1" style="font-family: 'Archivo', sans-serif;">
                    Start a Survey
                </h1>
                <p class="text-sm text-gray-500 mb-8">Select a ward below to begin the house description survey.</p>

                <div class="text-left mb-6">
                    <label for="ward-select" class="block text-sm font-semibold text-gray-700 mb-2">
                        Select Ward <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="ward-select"
                        x-model="selectedWardId"
                        class="block w-full rounded-xl border-2 border-gray-200 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer"
                    >
                        <option value="">-- Select a Ward --</option>
                        @foreach ($wards as $ward)
                            <option value="{{ $ward->id }}">Ward {{ $ward->ward_no }}</option>
                        @endforeach
                    </select>
                </div>

               
                <div x-show="selectedWardId" x-cloak
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-3"
                    x-transition:enter-end="opacity-100 transform translate-y-0">
                    <a
                        :href="`{{ url('/admin/house-description/create') }}/${selectedWardId}`"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:from-indigo-700 hover:to-purple-700 hover:shadow-xl hover:-translate-y-0.5"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Start Survey
                    </a>
                </div>

              
                <div x-show="!selectedWardId" class="mt-2 text-xs text-gray-400">
                    Select a ward to continue
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
