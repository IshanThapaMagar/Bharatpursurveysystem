<x-app-layout>
    @push('styles')
        <style>
            @media (max-width: 768px) {

                #responses-table,
                #responses-table thead,
                #responses-table tbody,
                #responses-table th,
                #responses-table td,
                #responses-table tr {
                    display: block;
                }

                #responses-table thead tr {
                    position: absolute;
                    top: -9999px;
                    left: -9999px;
                }

                #responses-table tr {
                    margin-bottom: 1rem;
                    border: 1px solid #e5e7eb;
                    border-radius: 0.75rem;
                    background: white;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }

                #responses-table td {
                    border: none;
                    border-bottom: 1px solid #f3f4f6;
                    position: relative;
                    padding-left: 50%;
                    text-align: right;
                    min-height: 2.5rem;
                    display: flex;
                    align-items: center;
                    justify-content: flex-end;
                }

                #responses-table td:last-child {
                    border-bottom: 0;
                }

                #responses-table td::before {
                    content: attr(data-label);
                    position: absolute;
                    left: 1rem;
                    width: 45%;
                    white-space: nowrap;
                    font-weight: 600;
                    text-align: left;
                    color: #4b5563;
                }
            }
        </style>
    @endpush

    <div class="py-12 sm:py-24 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Details of residents in the computer system') }}</h2>
                <p class="text-gray-500 text-sm mt-1">{{ __('All registered household survey details') }}</p>
            </div>

            <div class="mb-6 flex flex-wrap gap-4 items-end bg-gray-50 border border-gray-200 rounded-xl p-4">

                {{-- Ward Filter --}}
                <div class="flex flex-col gap-1 min-w-[160px]">
                    <label for="filter-ward"
                        class="text-xs font-semibold text-gray-600 uppercase tracking-wide">{{ __('Ward') }}</label>
                    <select id="filter-ward"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 bg-white"
                        {{ count($wards) === 1 ? 'disabled' : '' }}>
                        @if (count($wards) > 1)
                            <option value="">— {{ __('All Wards') }} —</option>
                        @endif
                        @foreach ($wards as $ward)
                            <option value="{{ $ward->ward_no }}" data-id="{{ $ward->id }}"
                                {{ count($wards) === 1 ? 'selected' : '' }}>
                                {{ __('Ward') }} {{ $ward->ward_no }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tole Filter --}}
                <div class="flex flex-col gap-1 min-w-[180px]">
                    <label for="filter-tole"
                        class="text-xs font-semibold text-gray-600 uppercase tracking-wide">{{ __('Tole') }}</label>
                    <select id="filter-tole"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 bg-white">
                        <option value="">— {{ __('All Toles') }} —</option>
                        @foreach ($toles as $tole)
                            <option value="{{ $tole->name }}">{{ $tole->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Reset --}}
                <div class="flex gap-2">
                    <button id="btn-reset"
                        class="px-5 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg shadow hover:bg-gray-300 transition">
                        {{ __('Reset') }}
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="responses-table" class="min-w-full divide-y divide-gray-200 table-auto text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                ऋ.स</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                घरमुलीको नाम</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                घर नं</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                टोल</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                फोन नं</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                कित्ता नं</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                फोटो</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                सदस्य संख्या</th>
                            {{-- Hidden column: ward_no for filtering --}}
                            <th class="hidden">वडा</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($responses ?? [] as $index => $response)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td data-label="ऋ.स" class="px-4 py-3 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td data-label="घरमुलीको नाम" class="px-4 py-3 font-medium text-gray-900">
                                    {{ $response->householder?->householder_name ?? '-' }}</td>
                                <td data-label="घर नं" class="px-4 py-3">
                                    {{ $response->householder?->house_number ?? '-' }}</td>
                                <td data-label="टोल" class="px-4 py-3">
                                    {{ $response->householder?->tole?->name ?? '-' }}</td>
                                <td data-label="फोन नं" class="px-4 py-3">
                                    {{ $response->householder?->phone_number ?? '-' }}</td>
                                <td data-label="कित्ता नं" class="px-4 py-3">
                                    {{ $response->householder?->lot_number }}</td>
                                <td data-label="फोटो" class="px-4 py-3">
                                    @if ($response->householder?->profile_photo)
                                        <img src="{{ Storage::url($response->householder->profile_photo) }}"
                                            alt="Profile" class="h-10 w-10 rounded-full object-cover shadow-sm">
                                    @else
                                        <span class="text-gray-400">उपलब्ध छैन</span>
                                    @endif
                                </td>
                                <td data-label="सदस्य संख्या" class="px-4 py-3 text-center">
                                    {{ $response->householder?->members_count ?? 0 }}
                                </td>
                                {{-- Hidden ward_no cell for DataTables column filtering --}}
                                <td class="hidden">{{ $response->householder?->ward_no ?? '' }}</td>
                                <td data-label="कार्यहरू" class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('survey-responses.show', $response->id) }}"
                                            class="px-4 py-2 bg-green-500 text-white font-semibold rounded-md shadow hover:bg-green-600 transition">
                                            {{ __('Full details') }}
                                        </a>
                                        @if (auth()->user()->isSuperAdmin() || (auth()->user()->isWardAdmin() && auth()->user()->ward_id == $response->ward_id))
                                            <a href="{{ route('survey-responses.edit', $response->id) }}"
                                                class="px-4 py-2 bg-yellow-500 text-white font-semibold rounded-md shadow hover:bg-yellow-600 transition">
                                                {{ __('Edit') }}
                                            </a>
                                            <button type="button" onclick="deleteResponse({{ $response->id }})"
                                                class="px-4 py-2 bg-red-500 text-white font-semibold rounded-md shadow hover:bg-red-600 transition">
                                                {{ __('Delete') }}
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script type="module">
            $(document).ready(function() {
                const table = new DataTable('#responses-table', {
                    language: {
                        search: "{{ __('Search') }}:",
                        lengthMenu: "{{ __('Show') }} _MENU_ {{ __('entries') }}",
                        info: "{{ __('Showing') }} _START_ {{ __('to') }} _END_ {{ __('of') }} _TOTAL_ {{ __('entries') }}",
                        zeroRecords: "{{ __('No matching records found') }}",
                        emptyTable: "{{ __('No data available in table') }}",
                    },

                    columnDefs: [{
                            targets: [8],
                            visible: false,
                            searchable: true
                        },
                        {
                            targets: [0, 6, 9],
                            orderable: false
                        },
                    ],
                    order: [
                        [1, 'asc']
                    ],
                });

                // --- Ward filter ---
                const wardSelect = document.getElementById('filter-ward');
                const toleSelect = document.getElementById('filter-tole');
                const tolesByWardUrl = "{{ route('toles.by.ward') }}";

                wardSelect.addEventListener('change', function() {
                    const wardNo = this.value;
                    table.column(8).search(wardNo, false, false).draw();
                    toleSelect.innerHTML = '<option value="">— सबै टोल —</option>';
                    table.column(3).search('', false, false).draw();

                    const wardId = wardSelect.options[wardSelect.selectedIndex].dataset.id;
                    const url = tolesByWardUrl + (wardId ? '?ward_id=' + wardId : '');

                    fetch(url)
                        .then(r => r.json())
                        .then(toles => {
                            toles.forEach(t => {
                                const opt = document.createElement('option');
                                opt.value = t.name;
                                opt.textContent = t.name;
                                toleSelect.appendChild(opt);
                            });
                        });
                });

                toleSelect.addEventListener('change', function() {

                    const val = this.value ?
                        '^' + this.value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '$' :
                        '';
                    table.column(3).search(val, true, false).draw();
                });


                document.getElementById('btn-reset').addEventListener('click', function() {
                    wardSelect.value = '';
                    toleSelect.innerHTML = '<option value="">— सबै टोल —</option>';
                    @foreach ($toles as $tole)
                        (function() {
                            const o = document.createElement('option');
                            o.value = "{{ $tole->name }}";
                            o.textContent = "{{ $tole->name }}";
                            toleSelect.appendChild(o);
                        })();
                    @endforeach
                    table.column(8).search('', false, false);
                    table.column(3).search('', false, false).draw();
                });

                window.deleteResponse = function(id) {
                    if (!confirm('के तपाईं पक्का हुनुहुन्छ? यो रेकर्ड स्थायी रूपमा मेटिनेछ।')) return;

                    fetch(`/admin/survey-responses/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert(data.message || 'Error occurred');
                            }
                        })
                        .catch(e => {
                            console.error(e);
                            alert('An error occurred');
                        });
                };
            });
        </script>
    @endpush

</x-app-layout>
