<x-app-layout>
    <div class="py-28">
        <div class="px-4 sm:px-6 lg:px-8 max-w-[100%]">
            <div class="flex justify-end">
                @if (auth()->user()->isSuperAdmin() || auth()->user()->ward_id == $response->ward_id)
                    <a href="{{ route('house-member.create', ['household_id' => $household->id]) }}"
                        class="inline-block px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow hover:bg-indigo-700 transition">
                        Add Family Member
                    </a>
                @endif
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-200 bg-gray-50 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <h2 class="font-semibold text-sm text-gray-800">घरधुरी विवरण</h2>
                </div>

                <div class="p-5">
                    <div class="flex gap-6">

                        {{-- Profile Photo --}}
                        <div class="flex-shrink-0">
                            @if ($household->profile_photo)
                                <button type="button"
                                    onclick="openPhotoModal('{{ Storage::url($household->profile_photo) }}')"
                                    class="group relative block w-24 h-24 rounded-xl overflow-hidden border border-gray-200 shadow-sm cursor-zoom-in">
                                    <img src="{{ Storage::url($household->profile_photo) }}"
                                        alt="{{ $household->householder_name }}"
                                        class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105" />
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-200 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                                        </svg>
                                    </div>
                                </button>
                            @else
                                <div
                                    class="w-24 h-24 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center shadow-sm">
                                    <svg class="w-10 h-10 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Info Fields --}}
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-4">

                            <x-info-field label="घरमुखीको नाम" :value="$household->householder_name ?? '—'" />
                            <x-info-field label="टोल" :value="$household->tole?->name ?? '—'" />
                            <div class="flex gap-6">
                                <x-info-field label="घर नं." :value="$household->house_number ?? '—'" />
                                <x-info-field label="फोन नं." :value="$household->phone_number ?? '—'" />
                            </div>

                            <x-info-field label="बुवाको नाम" :value="$household->father_name ?? '—'" />
                            <x-info-field label="आमाको नाम" :value="$household->mother_name ?? '—'" />
                            <x-info-field label="जाति" :value="$household->caste?->name ?? '—'" />

                            <x-info-field label="पुखौँली भाषा" :value="$household->motherTongue?->name ?? '—'" />
                            <x-info-field label="स्थायी ठेगाना" :value="$household->citizenship_permanent_address ?? '—'" />
                            <div></div>

                        </div>
                    </div>
                </div>
            </div>


            {{-- Family Members Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <h2 class="font-semibold text-sm text-gray-800">परिवारका सदस्यहरूको विवरण</h2>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    @if($household->members->isEmpty())
                        <div class="p-8 text-center bg-gray-50/50">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600 font-medium">माफ गर्नुहोला | परिवारको कुनै पनि सदस्य भेटीएन वा प्रणालीमा विवरण राखिएको छैन |</p>
                        </div>
                    @else
                        <table class="w-full text-[11px] leading-tight border-collapse">
                            <thead>
                                <tr class="bg-gray-100 border-b border-gray-200 text-gray-600 uppercase tracking-tight font-bold">
                                    <th class="px-2 py-2 border-r">{{ __('S.N') }}</th>
                                    <th class="px-2 py-2 border-r min-w-[120px]">{{ __('Full Name') }}</th>
                                    <th class="px-2 py-2 border-r">{{ __('Relation') }}</th>
                                    <th class="px-2 py-2 border-r">{{ __('Gender') }}</th>
                                    <th class="px-2 py-2 border-r">उमेर</th>
                                    <th class="px-2 py-2 border-r">{{ __('Select marital status') }}</th>
                                    <th class="px-2 py-2 border-r">{{ __('Education Level') }}</th>
                                    <th class="px-2 py-2 border-r">{{ __('Occupation/Employment') }}</th>
                                    <th class="px-2 py-2 border-r">{{ __('Voter ID Card') }}</th>
                                    <th class="px-2 py-2 border-r">{{ __('Health Status') }}</th>
                                    <th class="px-2 py-2 border-r">{{ __('Current Residence') }}</th>
                                    <th class="px-2 py-2 border-r">{{ __('Institution Type') }}</th>
                                    <th class="px-2 py-2 border-r">{{ __('Blood Group') }}</th>
                                    <th class="px-2 py-2 border-r">{{ __('Permanent Account No.') }}</th>
                                    <th class="px-2 py-2 border-r">{{ __('National Identity Card No.') }}</th>
                                    <th class="px-2 py-2 border-r">{{ __('Disability') }}</th>
                                    <th class="px-2 py-2 border-r">{{ __('Citizenship Number') }}</th>
                                    <th class="px-2 py-2 border-r min-w-[150px]">{{ __('Government Support') }}</th>
                                    <th class="px-2 py-2">#</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 italic">
                                @foreach($household->members as $idx => $member)
                                    <tr class="hover:bg-indigo-50/30 transition-colors {{ $member->is_demised ? 'bg-gray-100 grayscale' : '' }}">
                                        <td class="px-2 py-1.5 border-r text-center">{{ $idx + 1 }}</td>
                                        <td class="px-2 py-1.5 border-r font-semibold text-gray-900 not-italic">
                                            {{ $member->full_name }}
                                            @if($member->is_demised)
                                                <span class="block text-[9px] text-red-600 font-bold uppercase">(Demised)</span>
                                            @endif
                                        </td>
                                        <td class="px-2 py-1.5 border-r">{{ $member->relationship?->name ?? '-' }}</td>
                                        <td class="px-2 py-1.5 border-r">{{ $member->gender?->name ?? '-' }}</td>
                                        <td class="px-2 py-1.5 border-r text-center">{{ $member->age ?? '-' }}</td>
                                        <td class="px-2 py-1.5 border-r">{{ $member->maritalStatus?->name ?? '-' }}</td>
                                        <td class="px-2 py-1.5 border-r">{{ $member->educationLevel?->name ?? '-' }}</td>
                                        <td class="px-2 py-1.5 border-r">{{ $member->occupation ?? '-' }}</td>
                                        <td class="px-2 py-1.5 border-r text-center">{{ $member->has_voterId ? 'छ' : 'छैन' }}</td>
                                        <td class="px-2 py-1.5 border-r">{{ $member->healthStatus?->name ?? '-' }}</td>
                                        <td class="px-2 py-1.5 border-r">{{ $member->district?->name ?? '-' }}</td>
                                        <td class="px-2 py-1.5 border-r">{{ $member->institutionType?->name ?? '-' }}</td>
                                        <td class="px-2 py-1.5 border-r text-center">{{ $member->bloodGroup?->name ?? '-' }}</td>
                                        <td class="px-2 py-1.5 border-r">{{ $member->permanent_account_no ?? '-' }}</td>
                                        <td class="px-2 py-1.5 border-r">{{ $member->nid_no ?? '-' }}</td>
                                        <td class="px-2 py-1.5 border-r">{{ $member->disability?->name ?? '-' }}</td>
                                        <td class="px-2 py-1.5 border-r">{{ $member->citizenship_number ?? '-' }}</td>
                                        <td class="px-2 py-1.5 border-r">{{ $member->governmentSupportType?->name ?? '-' }}</td>
                                        <td class="px-2 py-1.5 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                @if (auth()->user()->isSuperAdmin() || (auth()->user()->isWardAdmin() && auth()->user()->ward_id == $response->ward_id))
                                                    {{-- Edit --}}
                                                    <a href="{{ route('house-member.edit', $member->id) }}" title="Edit Details" class="text-blue-500 hover:text-blue-700 transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>

                                                    {{-- Mark Demise --}}
                                                    @if(!$member->is_demised)
                                                        <form action="{{ route('house-member.mark-demise', $member->id) }}" method="POST" class="inline" onsubmit="return confirm('के तपाईं पक्का हुनुहुन्छ कि यो सदस्यलाई दिवंगतको रूपमा चिन्ह लगाउन चाहनुहुन्छ?')">
                                                            @csrf
                                                            <button type="submit" class="text-amber-500 hover:text-amber-700 transition" title="Mark as Demise">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- Delete --}}
                                                    <form action="{{ route('house-member.destroy', $member->id) }}" method="POST" class="inline" onsubmit="return confirm('के तपाईं पक्का हुनुहुन्छ कि यो सदस्यलाई हटाउन चाहनुहुन्छ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-400 hover:text-red-600 transition" title="Delete">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>


            @if ($sections->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
                    x-data="{ activeTab: 0 }">

                    {{-- Tab Bar --}}
                    <div class="flex overflow-x-auto border-b border-gray-200">
                        @foreach ($sections as $i => $section)
                            <button @click="activeTab = {{ $i }}"
                                :class="activeTab === {{ $i }} ?
                                    'border-indigo-600 text-indigo-600 bg-indigo-50/50' :
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                class="px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors border-b-2">
                                {{ $section->title }}
                            </button>
                        @endforeach
                    </div>

                    @foreach ($sections as $i => $section)
                        <div x-show="activeTab === {{ $i }}" x-cloak class="overflow-x-auto">

                            @if ($section->description)
                                <p class="px-5 pt-4 pb-1 text-xs text-gray-400 italic">
                                    {{ $section->description }}
                                </p>
                            @endif

                            @if ($section->questions->isNotEmpty())
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-gray-100/70 border-b border-gray-200">
                                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-500 w-1/2">
                                                प्रश्न
                                            </th>
                                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-500 w-1/2">
                                                उत्तर
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($section->questions as $rowIndex => $question)
                                            <tr class="{{ $rowIndex % 2 === 0 ? 'bg-gray-50/50' : 'bg-white' }}">
                                                <td
                                                    class="px-5 py-2.5 text-gray-600 font-medium text-xs leading-relaxed border-r border-gray-100 align-top">
                                                    {{ $question->question_text }}
                                                    @if ($question->question_subtext)
                                                        <span class="block text-gray-400 font-normal mt-0.5">
                                                            {{ $question->question_subtext }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-5 py-2.5 text-xs align-top
                                                    {{ $question->resolved_answer ? 'text-gray-800' : 'text-gray-300 italic' }}">
                                                    @if ($question->inputType->input_type_name === 'linear_scale' && $question->resolved_answer)
                                                        {{ $question->resolved_answer }} / {{ $question->scale_to }}
                                                    @else
                                                        {!! $question->resolved_answer ?? 'उत्तर दिइएको छैन' !!}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="px-5 py-6 text-center text-sm text-gray-400">
                                    यस खण्डमा कुनै प्रश्न छैन।
                                </p>
                            @endif

                        </div>
                    @endforeach

                </div>
            @else
                <div class="rounded-xl px-5 py-4 flex items-start gap-3 border border-gray-200 bg-gray-50">
                    <p class="text-sm text-gray-500">यस वडाको लागि कुनै सर्वेक्षण खण्ड फेला परेन।</p>
                </div>
            @endif

        </div>
    </div>


    {{-- Photo View Modal --}}
    <div id="photoModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4"
        onclick="closePhotoModal()">
        <div class="relative max-w-2xl w-full" onclick="event.stopPropagation()">

            {{-- Close Button --}}
            <button type="button" onclick="closePhotoModal()"
                class="absolute -top-3 -right-3 z-10 w-8 h-8 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-500 hover:text-gray-800 transition-colors">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Image --}}
            <img id="photoModalImg" src="" alt="Profile Photo"
                class="w-full max-h-[80vh] object-contain rounded-xl shadow-2xl" />

        </div>
    </div>

    @push('scripts')
        <script>
            function openPhotoModal(src) {
                const modal = document.getElementById('photoModal');
                document.getElementById('photoModalImg').src = src;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            function closePhotoModal() {
                const modal = document.getElementById('photoModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closePhotoModal();
            });
        </script>
    @endpush

</x-app-layout>
