<x-app-layout>
    <div class="py-24">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Ward Dropdown -->
                    <form method="GET" class="mb-8">
                        <select name="ward" onchange="this.form.submit()"
                            class="border rounded-lg p-3 w-48 shadow-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            @foreach ($wards as $ward)
                                <option value="{{ $ward->id }}" {{ $selectedWard == $ward->id ? 'selected' : '' }}>
                                    {{ __('Ward') }} {{ $ward->ward_no }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <!-- Age Group Info Boxes -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                        @foreach ($ageGroups as $group)
                            <a href="#"
                                class="block p-5 rounded-xl border {{ $group['border_color'] }} {{ $group['light_color'] }} hover:shadow-md transition-shadow duration-300">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                            {{ $group['label'] }}</h4>

                                    </div>
                                    <span class="text-2xl font-bold text-gray-800">{{ $group['percentage'] }}%</span>
                                </div>
                                <div class="mb-2">
                                    <div class="flex justify-between items-center mb-1">
                                        <span
                                            class="text-xs font-medium text-gray-600">{{ __('Population Count') }}</span>
                                        <span class="text-sm font-bold text-gray-800">{{ $group['count'] }}</span>
                                    </div>
                                    <div class="w-full bg-white rounded-full h-2.5 overflow-hidden">
                                        <div class="h-2.5 rounded-full {{ $group['color'] }}"
                                            style="width: {{ $group['percentage'] }}%"></div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Gender Statistics Info Boxes -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                        @foreach ($genderGroups as $group)
                            <a href="#"
                                class="block p-5 rounded-xl border {{ $group['border_color'] }} {{ $group['light_color'] }} hover:shadow-md transition-shadow duration-300">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                            {{ $group['label'] }}</h4>
                                    </div>
                                    <span class="text-2xl font-bold text-gray-800">{{ $group['percentage'] }}%</span>
                                </div>
                                <div class="mb-2">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-medium text-gray-600">{{ __('Total Count') }}</span>
                                        <span class="text-sm font-bold text-gray-800">{{ $group['count'] }}</span>
                                    </div>
                                    <div class="w-full bg-white rounded-full h-2.5 overflow-hidden">
                                        <div class="h-2.5 rounded-full {{ $group['color'] }}"
                                            style="width: {{ $group['percentage'] }}%"></div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Demographic Charts Row -->
                    <div class="mb-12">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Age Demographics Bar Chart -->
                            <div
                                class="bg-slate-50 border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col items-center">
                                <div class="w-full relative min-h-[380px] flex justify-center items-center">
                                    <canvas id="ageDemographicsChart"></canvas>
                                </div>
                            </div>

                            <!-- Gender Demographics Pie Chart -->
                            <div
                                class="bg-slate-50 border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col items-center">
                                <h4 class="font-bold text-lg mb-6 text-center text-slate-800 leading-tight w-full">
                                    {{ __('Gender Distribution') }}</h4>
                                <div class="w-full relative min-h-[380px] flex justify-center items-center">
                                    <canvas id="genderDemographicsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Citizenship Permanent Address Statistics Info Boxes -->
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            बसोबास (घरधुरीको आधारमा)
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                        @foreach ($citizenshipGroups as $group)
                            <div class="bg-white border border-gray-200 p-5 flex flex-col gap-3">
                                <h4 class="text-sm font-semibold text-gray-700">
                                    {{ $group['label'] }}
                                </h4>

                                <span class="text-2xl font-bold text-gray-900">
                                    {{ $group['count'] }}
                                </span>

                                <div class="w-full bg-gray-200 h-2">
                                    <div class="bg-gray-600 h-2" style="width: {{ $group['percentage'] }}%">
                                    </div>
                                </div>

                                <span class="text-xs text-gray-600 text-right">
                                    {{ number_format($group['percentage'], 2) }}%
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Statistics Collage (Screenshot Style) -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-10 mt-12 mb-16">

                        <!-- मातृभाषा Table -->
                        <div class="flex flex-col">
                            <h3
                                class="text-sm font-bold text-gray-800 border-b-2 border-gray-100 pb-2 mb-4 uppercase tracking-wider">
                                मातृभाषा</h3>
                            <div class="space-y-0 text-sm">
                                @php
                                    $mtColors = [
                                        'bg-blue-500',
                                        'bg-emerald-500',
                                        'bg-rose-500',
                                        'bg-amber-500',
                                        'bg-indigo-500',
                                        'bg-violet-500',
                                        'bg-cyan-500',
                                        'bg-fuchsia-500',
                                        'bg-orange-500',
                                        'bg-teal-500',
                                        'bg-lime-500',
                                        'bg-pink-500',
                                    ];
                                @endphp
                                @forelse ($motherTongueStats as $index => $row)
                                    @php $pct = $motherTongueTotal > 0 ? number_format(($row->total / $motherTongueTotal) * 100, 2) : 0; @endphp
                                    <div
                                        class="flex items-center justify-between py-3 border-b border-gray-50 group hover:bg-gray-50 transition-colors duration-150 px-2 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-2 h-2 rounded-full {{ $mtColors[$index % count($mtColors)] }} shadow-sm">
                                            </div>
                                            <span class="text-gray-700 font-medium">{{ $row->name }}</span>
                                        </div>
                                        <div class="flex items-center gap-16 text-gray-600">
                                            <span
                                                class="font-semibold text-gray-900 w-16 text-right">{{ $row->total }}</span>
                                            <span class="w-16 text-right">{{ $pct }}%</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-400 italic text-center py-4">डेटा उपलब्ध छैन</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- जातजातीहरु Table -->
                        <div class="flex flex-col">
                            <h3
                                class="text-sm font-bold text-gray-800 border-b-2 border-gray-100 pb-2 mb-4 uppercase tracking-wider">
                                जातजातीहरु</h3>
                            <div class="space-y-0 text-sm">
                                @php
                                    $casteColors = [
                                        'bg-indigo-500',
                                        'bg-teal-500',
                                        'bg-purple-500',
                                        'bg-orange-500',
                                        'bg-pink-500',
                                        'bg-red-500',
                                        'bg-sky-500',
                                        'bg-emerald-500',
                                        'bg-yellow-500',
                                        'bg-blue-500',
                                        'bg-gray-500',
                                        'bg-rose-500',
                                    ];
                                @endphp
                                @forelse ($casteStats as $index => $row)
                                    @php $pct = $casteTotal > 0 ? number_format(($row->total / $casteTotal) * 100, 2) : 0; @endphp
                                    <div
                                        class="flex items-center justify-between py-3 border-b border-gray-50 group hover:bg-gray-50 transition-colors duration-150 px-2 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-2 h-2 rounded-full {{ $casteColors[$index % count($casteColors)] }} shadow-sm">
                                            </div>
                                            <span class="text-gray-700 font-medium">{{ $row->name }}</span>
                                        </div>
                                        <div class="flex items-center gap-16 text-gray-600">
                                            <span
                                                class="font-semibold text-gray-900 w-16 text-right">{{ $row->total }}</span>
                                            <span class="w-16 text-right">{{ $pct }}%</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-400 italic text-center py-4">डेटा उपलब्ध छैन</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- शिक्षाको अवस्था Table -->
                        <div class="flex flex-col">
                            <h3
                                class="text-sm font-bold text-gray-800 border-b-2 border-gray-100 pb-2 mb-4 uppercase tracking-wider">
                                शिक्षाको अवस्था</h3>
                            <div class="space-y-0 text-sm">
                                @php
                                    $eduColors = [
                                        'bg-amber-500',
                                        'bg-blue-500',
                                        'bg-green-500',
                                        'bg-orange-500',
                                        'bg-red-500',
                                        'bg-violet-500',
                                        'bg-cyan-500',
                                        'bg-indigo-500',
                                        'bg-emerald-500',
                                        'bg-rose-500',
                                    ];
                                @endphp
                                @forelse ($educationStats as $index => $row)
                                    @php $pct = $educationTotal > 0 ? number_format(($row->total / $educationTotal) * 100, 2) : 0; @endphp
                                    <div
                                        class="flex items-center justify-between py-3 border-b border-gray-50 group hover:bg-gray-50 transition-colors duration-150 px-2 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-2 h-2 rounded-full {{ $eduColors[$index % count($eduColors)] }} shadow-sm">
                                            </div>
                                            <span class="text-gray-700 font-medium">{{ $row->label }}</span>
                                        </div>
                                        <div class="flex items-center gap-16 text-gray-600">
                                            <span
                                                class="font-semibold text-gray-900 w-16 text-right">{{ $row->total }}</span>
                                            <span class="w-16 text-right">{{ $pct }}%</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-400 italic text-center py-4">डेटा उपलब्ध छैन</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- धर्म Table -->
                        <div class="flex flex-col">
                            <h3
                                class="text-sm font-bold text-gray-800 border-b-2 border-gray-100 pb-2 mb-4 uppercase tracking-wider">
                                धर्म (जनसंख्या के आधारमा)</h3>
                            <div class="space-y-0 text-sm">
                                @php
                                    $relColors = [
                                        'bg-rose-500',
                                        'bg-sky-500',
                                        'bg-emerald-500',
                                        'bg-amber-500',
                                        'bg-indigo-500',
                                        'bg-violet-500',
                                        'bg-cyan-500',
                                        'bg-fuchsia-500',
                                        'bg-orange-500',
                                        'bg-teal-500',
                                        'bg-lime-500',
                                        'bg-pink-500',
                                    ];
                                @endphp
                                @forelse ($religionStats as $index => $row)
                                    @php $pct = $religionTotal > 0 ? number_format(($row->total / $religionTotal) * 100, 2) : 0; @endphp
                                    <div
                                        class="flex items-center justify-between py-3 border-b border-gray-50 group hover:bg-gray-50 transition-colors duration-150 px-2 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-2 h-2 rounded-full {{ $relColors[$index % count($relColors)] }} shadow-sm">
                                            </div>
                                            <span class="text-gray-700 font-medium">{{ $row->label }}</span>
                                        </div>
                                        <div class="flex items-center gap-16 text-gray-600">
                                            <span
                                                class="font-semibold text-gray-900 w-16 text-right">{{ $row->total }}</span>
                                            <span class="w-16 text-right">{{ $pct }}%</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-400 italic text-center py-4">डेटा उपलब्ध छैन</p>
                                @endforelse
                            </div>
                        </div>

                    </div>

                    <!-- Pinned Charts -->
                    @if (isset($chartsData) && count($chartsData) > 0)
                        <div class="mb-12">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
                                    {{ __('Pinned Survey Analytics') }}</h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                @foreach ($chartsData as $questionId => $data)
                                    <div
                                        class="bg-slate-50 border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col items-center">
                                        <h4
                                            class="font-bold text-lg mb-6 text-center text-slate-800 leading-tight w-full">
                                            {{ $data['title'] }}</h4>
                                        <div class="w-full relative flex justify-center items-center"
                                            style="height: 380px;">
                                            <canvas id="dashboard-chart-{{ $questionId }}"></canvas>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const chartsData = @json($chartsData ?? []);
                const colors = [
                    '#4f46e5', '#ec4899', '#0ea5e9', '#f59e0b',
                    '#10b981', '#8b5cf6', '#f43f5e', '#14b8a6',
                    '#eab308', '#6366f1', '#f97316', '#d946ef'
                ];

                Object.entries(chartsData).forEach(([questionId, data]) => {

                    const labels = data.labels;
                    const totals = data.totals;

                    // Repeat colors if not enough
                    const bgColors = labels.map((_, i) => colors[i % colors.length]);

                    const ctx = document.getElementById(`dashboard-chart-${questionId}`);
                    if (!ctx) return;

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
                                        padding: 16,
                                        usePointStyle: true,
                                        pointStyle: 'circle'
                                    }
                                }
                            }
                        }
                    });

                });

                const ageGroupsData = @json($ageGroups ?? []);
                const ageCtx = document.getElementById('ageDemographicsChart');

                if (ageCtx && ageGroupsData.length > 0) {
                    const customLabels = [
                        'जम्मा शिशु',
                        'जम्मा बालबालिका',
                        'जम्मा युवा',
                        'जम्मा अधबैंसे',
                        'जम्मा बृद्ध',
                        'जम्मा जेष्ठ नागरिक'
                    ];
                    const ageLabels = ageGroupsData.length === 6 ? customLabels : ageGroupsData.map(group => group
                        .label + ' ' + (group.range || ''));
                    const ageCounts = ageGroupsData.map(group => group.count);

                    const ageColors = [
                        '#4f46e5',
                        '#f97316',
                        '#4ade80',
                        '#ef4444',
                        '#dc2626',
                        '#fbbf24'
                    ];

                    new Chart(ageCtx, {
                        type: 'bar',
                        data: {
                            labels: ageLabels,
                            datasets: [{
                                label: 'नगरपालिकाको जनसंख्या',
                                data: ageCounts,
                                backgroundColor: ageColors,
                                borderSkipped: false,
                                borderRadius: 0,
                                barPercentage: 0.8,
                                categoryPercentage: 0.9
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'उमेर समूह अनुसारको तथ्यांक',
                                    font: {
                                        size: 16,
                                        family: "'Inter', sans-serif",
                                        weight: 'normal'
                                    },
                                    color: '#4b5563',
                                    padding: {
                                        bottom: 20
                                    }
                                },
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        color: '#4b5563',
                                        font: {
                                            family: "'Inter', sans-serif"
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(156, 163, 175, 0.9)',
                                    titleColor: '#ffffff',
                                    bodyColor: '#ffffff',
                                    titleFont: {
                                        size: 14,
                                        family: "'Inter', sans-serif"
                                    },
                                    bodyFont: {
                                        size: 14,
                                        family: "'Inter', sans-serif"
                                    },
                                    padding: 12,
                                    cornerRadius: 6,
                                    displayColors: true
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: true,
                                        color: '#e5e7eb',
                                    },
                                    ticks: {
                                        color: '#6b7280',
                                        font: {
                                            family: "'Inter', sans-serif"
                                        }
                                    },
                                    border: {
                                        display: true,
                                        color: '#9ca3af'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: true,
                                        color: '#e5e7eb'
                                    },
                                    ticks: {
                                        color: '#6b7280',
                                        font: {
                                            family: "'Inter', sans-serif"
                                        },
                                        stepSize: 500
                                    },
                                    border: {
                                        display: true,
                                        color: '#9ca3af'
                                    }
                                }
                            },
                            layout: {
                                padding: {
                                    top: 10,
                                    bottom: 10
                                }
                            }
                        },
                        plugins: [{
                            id: 'customCanvasBackgroundColor',
                            beforeDraw: (chart, args, options) => {
                                const {
                                    ctx
                                } = chart;
                                ctx.save();
                                ctx.globalCompositeOperation = 'destination-over';
                                ctx.fillStyle = options.color || '#f3f4f6';
                                ctx.fillRect(0, 0, chart.width, chart.height);
                                ctx.restore();
                            }
                        }]
                    });
                }


                const genderGroupsData = @json($genderGroups ?? []);
                const genderCtx = document.getElementById('genderDemographicsChart');

                if (genderCtx && genderGroupsData.length > 0) {
                    const genderLabels = genderGroupsData.map(group => group.label);
                    const genderCounts = genderGroupsData.map(group => group.count);
                    const genderColors = ['#3E95CD', ' #8E5EA2', '#3CBA9F'];

                    new Chart(genderCtx, {
                        type: 'pie',
                        data: {
                            labels: genderLabels,
                            datasets: [{
                                data: genderCounts,
                                backgroundColor: genderColors,
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
                                        padding: 16,
                                        usePointStyle: true,
                                        pointStyle: 'circle'
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    titleFont: {
                                        size: 14,
                                        family: "'Inter', sans-serif"
                                    },
                                    bodyFont: {
                                        size: 14,
                                        family: "'Inter', sans-serif",
                                        weight: 'bold'
                                    },
                                    padding: 12,
                                    cornerRadius: 8,
                                }
                            }
                        }
                    });
                }

            });
        </script>
    @endpush
</x-app-layout>
