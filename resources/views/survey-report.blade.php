<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Survey Detailed Report') }}
        </h2>
    </x-slot>

    <div class="py-24">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Title & Filter block -->
                <form method="GET" class="mb-8 flex flex-col md:flex-row md:items-end gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Select Ward') }}</label>
                        <select name="ward" class="border rounded-lg p-3 w-full md:w-48 shadow-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                            @foreach ($wards as $ward)
                                <option value="{{ $ward->id }}" {{ $selectedWard == $ward->id ? 'selected' : '' }}>
                                    {{ __('Ward') }} {{ $ward->ward_no }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-indigo-600 text-white font-medium px-6 py-3 rounded-lg shadow-md hover:bg-indigo-700 hover:shadow-lg transition-all duration-300">
                            {{ __('Update Report') }}
                        </button>
                    </div>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse ($charts as $questionId => $chart)
                        @php
                            $isPinned = isset($pinnedCharts) && $pinnedCharts->has($questionId);
                            $customTitle = $isPinned ? $pinnedCharts[$questionId]->custom_title : '';
                        @endphp
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col">
                            
                            <!-- Pinned Checkbox & Custom Title Form -->
                            @if(!Auth::user()->isDataCollector())
                            <div class="mb-6 bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex flex-col gap-3 z-10 relative">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" class="pin-chart-checkbox rounded text-indigo-600 focus:ring-indigo-500 w-5 h-5" 
                                        data-question-id="{{ $questionId }}" {{ $isPinned ? 'checked' : '' }}>
                                    <span class="text-sm font-semibold text-gray-700">{{ __('Show on Dashboard') }}</span>
                                </label>
                                <div class="custom-title-container {{ $isPinned ? '' : 'hidden' }}">
                                    <input type="text" class="custom-title-input w-full border-gray-200 rounded-lg text-sm p-2 focus:ring-indigo-500 shadow-inner" 
                                        placeholder="{{ __('Custom Dashboard Title') }}" value="{{ $customTitle }}" data-question-id="{{ $questionId }}">
                                    <button class="save-pin-title text-xs mt-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-medium px-3 py-1.5 rounded-md transition-colors w-full" data-question-id="{{ $questionId }}">
                                        {{ __('Save Dashboard Title') }}
                                    </button>
                                </div>
                            </div>
                            @endif                            
                            <h4 class="font-bold text-lg mb-6 text-center text-slate-800 leading-tight w-full flex-grow">{{ $chart['question_text'] }}</h4>
                            <div class="w-full relative flex justify-center items-center" style="height: 380px;">
                                <canvas id="chart-{{ $questionId }}"></canvas>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-1 md:col-span-2 flex flex-col items-center justify-center text-gray-500 p-12 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50">
                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            <span class="text-lg font-medium">{{ __('No data available for the selected ward.') }}</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- SweetAlert2 for notifications -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // CSRF Token for AJAX requests
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                // Handle Checkbox Toggle
                document.querySelectorAll('.pin-chart-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const qId = this.dataset.questionId;
                        const container = this.closest('div').querySelector('.custom-title-container');
                        const isChecked = this.checked;
                        const customTitleInput = container.querySelector('.custom-title-input');
                        
                        if(isChecked) {
                            container.classList.remove('hidden');
                            savePinState(qId, true, customTitleInput.value);
                        } else {
                            container.classList.add('hidden');
                            savePinState(qId, false, customTitleInput.value);
                        }
                    });
                });

                // Handle Save Title Button
                document.querySelectorAll('.save-pin-title').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const qId = this.dataset.questionId;
                        const input = this.closest('.custom-title-container').querySelector('.custom-title-input');
                        savePinState(qId, true, input.value);
                    });
                });

                function savePinState(questionId, isPinned, title) {
                    fetch('{{ route("dashboard.survey-report.pin") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            question_id: questionId,
                            is_pinned: isPinned,
                            custom_title: title
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: isPinned ? 'Chart pinned to Dashboard' : 'Chart removed from Dashboard',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    })
                    .catch(err => console.error(err));
                }

                // Chart initialization
                const chartsData = @json($charts);
                
                const colors = [
                    '#4f46e5', '#ec4899', '#0ea5e9', '#f59e0b',
                    '#10b981', '#8b5cf6', '#f43f5e', '#14b8a6',
                    '#eab308', '#6366f1', '#f97316', '#d946ef'
                ];

                Object.entries(chartsData).forEach(([questionId, data]) => {
                    const ctx = document.getElementById(`chart-${questionId}`);
                    if (!ctx) return;
                    
                    const labels = data.labels;
                    const totals = data.totals;
                    const bgColors = labels.map((_, i) => colors[i % colors.length]);

                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: totals,
                                backgroundColor: bgColors,
                                borderWidth: 2,
                                borderColor: '#ffffff',
                                hoverOffset: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 24,
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        font: {
                                            size: 13,
                                            family: "'Inter', sans-serif",
                                            weight: '500'
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    titleFont: { size: 14, family: "'Inter', sans-serif" },
                                    bodyFont: { size: 14, family: "'Inter', sans-serif", weight: 'bold' },
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            if (context.parsed !== null) {
                                                label += context.parsed + ' ' + 'responses';
                                            }
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
