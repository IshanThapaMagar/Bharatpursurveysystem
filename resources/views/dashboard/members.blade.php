<x-app-layout>
    <div class="py-24">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">
                    {{ $label ?? 'सदस्यहरूको सूची' }}
                </h2>
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    &larr; ड्यासबोर्डमा फर्कनुहोस्
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    
                    <table class="w-full border-collapse text-sm text-left">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase">सि.नं.</th>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase">पूरा नाम</th>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase">उमेर</th>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase">लिङ्ग</th>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase">वैवाहिक स्थिति</th>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase">शैक्षिक स्तर</th>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase">स्वास्थ्य अवस्था</th>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase">संस्था प्रकार</th>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase">अपाङ्गता</th>
                                <th class="py-3 px-4 font-semibold text-gray-600 uppercase">जन्म मिति (बि.सं.)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($members as $index => $m)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4 font-medium text-gray-800">{{ $m->full_name ?? '—' }}</td>
                                    <td class="py-3 px-4">{{ $m->age ?? '—' }}</td>
                                    <td class="py-3 px-4">{{ $m->gender ?? '—' }}</td>
                                    <td class="py-3 px-4">{{ $m->marital_status ?? '—' }}</td>
                                    <td class="py-3 px-4">{{ $m->education_level ?? '—' }}</td>
                                    <td class="py-3 px-4">{{ $m->health_status ?? '—' }}</td>
                                    <td class="py-3 px-4">{{ $m->institution_type ?? '—' }}</td>
                                    <td class="py-3 px-4">{{ $m->disability ?? '—' }}</td>
                                    <td class="py-3 px-4">{{ $m->dob_bs ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-8 text-center text-gray-500">यस समूहमा कुनै सदस्य फेला परेन।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
