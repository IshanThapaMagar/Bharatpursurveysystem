<x-app-layout>
    <div class="py-24">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="p-8">

                        @if (session('error'))
                            <span class="text-red-600">{{ session('error') }}</span>
                        @endif

                        <!-- Ward Select -->
                        <form method="GET" class="mb-8 max-w-sm">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Select Ward
                            </label>

                            <select name="ward_id" onchange="this.form.submit()"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm
                               focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 focus:outline-none
                               transition">
                                <option value="">-- Select Ward --</option>

                                @foreach ($wards as $ward)
                                    <option value="{{ $ward->id }}"
                                        {{ request('ward_id') == $ward->id ? 'selected' : '' }}>
                                        Ward {{ $ward->ward_no }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        <!-- Table -->
                        <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-sm">
                            <table id="survey-sections" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                            Section
                                        </th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 w-40">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody id="sortable" class="divide-y divide-gray-100 bg-white cursor-move">
                                    @if (!request('ward_id'))
                                        <tr>
                                            <td colspan="2" class="px-6 py-12 text-center text-gray-400 font-medium">
                                                Please select the ward first
                                            </td>
                                        </tr>
                                    @elseif($sections->isEmpty())
                                        <tr>
                                            <td colspan="2" class="px-6 py-12 text-center text-gray-400 font-medium">
                                                No sections found
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($sections->sortBy('order_index') as $section)
                                            <tr class="hover:bg-gray-50 transition" data-id="{{ $section->id }}">
                                                <td class="px-6 py-4 font-medium text-gray-900">
                                                    {{ $section->title }}
                                                </td>
                                                <td class="px-6 py-4 space-x-4">
                                                    <a href="{{ route('surveyform.edit', $section->id) }}"
                                                        class="text-indigo-600 hover:text-indigo-800 font-medium">
                                                        Edit
                                                    </a>
                                                    <a href=""
                                                        class="text-red-600 hover:text-red-800 font-medium">
                                                        Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script type="module">
                const sortable = document.getElementById('sortable');

                if (sortable) {
                    new Sortable(sortable, {
                        animation: 150,
                        ghostClass: 'bg-gray-100',
                        onEnd: function(evt) {
                            const order = Array.from(sortable.children).map((row, index) => ({
                                id: row.dataset.id,
                                order_index: index + 1
                            }));

                            fetch("{{ route('survey.sections.reorder') }}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                    },
                                    body: JSON.stringify({
                                        order
                                    })
                                })
                                .then(res => res.json())
                                .catch(err => console.error(err));
                        }
                    });
                }
            </script>
        @endpush
</x-app-layout>
