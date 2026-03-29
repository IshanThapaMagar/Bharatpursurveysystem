@props([
    'responses' => null,
    'wards' => null,
    'toles' => null,
])

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
        <div class="bg-white p-4 sm:p-6 rounded-md shadow-sm border border-gray-100">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ __('Details of Municipality in the computer system') }}
                    </h2>
                    <p class="text-gray-500 text-sm mt-1">
                        {{ __('View Municipality information and add ward details') }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('palika.admin.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700 transition">
                        Add Palika Admin
                    </a>
                </div>
            </div>

            @if (isset($admins) && $admins->count() > 0)
                <div class="mb-10">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($admins as $admin)
                            <div
                                class="bg-gray-50 border border-gray-100 flex flex-col items-start shadow-sm hover:shadow-md transition-all overflow-hidden group">
                                <!-- Full Width Photo Area -->
                                <div class="w-full relative shrink-0">
                                    @if ($admin->photo)
                                        <img src="{{ Storage::disk('public')->url($admin->photo) }}"
                                            alt="{{ $admin->name }}"
                                            class="h-[200px] w-auto object-cover transition-transform duration-300 group-hover:scale-105 mx-auto">
                                    @else
                                        <div class="h-[200px] w-full bg-gray-200 flex items-center justify-center">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-3 w-full text-left">
                                    <h4 class="text-sm font-bold text-gray-900 truncate" title="{{ $admin->name }}">
                                        {{ $admin->name }}</h4>
                                    <p class="text-xs font-semibold text-blue-600 mb-2 truncate"
                                        title="{{ $admin->designation->name }}">{{ $admin->designation->name }}</p>

                                    <div class="flex flex-col gap-1 text-xs text-gray-500 pt-2">
                                        @if ($admin->email)
                                            <div class="flex items-center gap-1.5 min-w-0">
                                                <svg class="h-3 w-3 text-gray-400 shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <a href="mailto:{{ $admin->email }}"
                                                    class="hover:text-blue-600 transition-colors truncate"
                                                    title="{{ $admin->email }}">{{ $admin->email }}</a>
                                            </div>
                                        @endif

                                        @if ($admin->phone)
                                            <div class="flex items-center gap-1.5 min-w-0">
                                                <svg class="h-3 w-3 text-gray-400 shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                <a href="tel:{{ $admin->phone }}"
                                                    class="hover:text-blue-600 transition-colors truncate"
                                                    title="{{ $admin->phone }}">{{ $admin->phone }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table id="responses-table" class="min-w-full divide-y divide-gray-200 table-auto text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                ऋ.स
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                स्थान
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                वडा नम्बर
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                कार्यहरू
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($responses ?? [] as $index => $response)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td data-label="ऋ.स" class="px-4 py-3 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td data-label="स्थान" class="px-4 py-3">
                                    {{ $response->householder?->location ?? '-' }}
                                </td>
                                <td data-label="वडा नम्बर" class="px-4 py-3">
                                    {{ $response->ward?->ward_no ?? '-' }}
                                </td>
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

    @push('scripts')
        <script type="module">
            $(document).ready(function() {
                const table = new DataTable('#responses-table', {
                    searching: false,
                    paging: false,
                    info: false,
                    lengthChange: false,
                    language: {
                        zeroRecords: "{{ __('No matching records found') }}",
                        emptyTable: "{{ __('No data available in table') }}",
                    },
                    columnDefs: [{
                            targets: [0, 3],
                            orderable: false
                        } // Disable ordering for serial and action columns
                    ],
                    order: [
                        [1, 'asc']
                    ],
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
