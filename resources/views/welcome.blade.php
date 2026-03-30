<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Bharatpur Survey System') }} — सार्वजनिक तथ्याङ्क</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900">

    {{-- ===== Top Navigation Bar ===== --}}
    <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
        <div class=" px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Brand --}}
                <div class="flex items-center">
                    <img src="{{ asset('assets/logo/logo-horizontal.png') }}"
                        alt="Lmnc Logo"
                        class="h-10 w-auto object-contain">
                </div>

                {{-- Right side: Language switcher + Ward dropdown --}}
                <div class="flex items-center gap-3">

                    {{-- Language Switcher --}}
                    @include('partials/language_switcher')

                    {{-- Ward Filter Dropdown --}}
                    <form method="GET" action="{{ route('welcome') }}" class="flex items-center gap-2">
                        <label class="text-sm text-gray-600 font-medium whitespace-nowrap hidden sm:block">वडा:</label>
                        <select name="ward" onchange="this.form.submit()"
                            class="w-24 border border-gray-300 rounded-lg px-3 py-1.5 text-sm text-gray-800 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="all" {{ $selectedWard == 'all' ? 'selected' : '' }}>सबै वडा</option>
                            @foreach ($wards as $ward)
                                <option value="{{ $ward->id }}" {{ $selectedWard == $ward->id ? 'selected' : '' }}>
                                    वडा {{ $ward->ward_no }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                </div>

            </div>
        </div>
    </nav>

    {{-- ===== Main Content ===== --}}
    <div class=" px-4 sm:px-6 lg:px-8 py-10 space-y-14">

        {{-- ===== Age Group Info Boxes ===== --}}
        <section>
            <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                <span class="w-1 h-5 bg-rose-500 rounded-full inline-block"></span>
                उमेर समूह अनुसार जनसंख्या
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $ageRanges = [
                        [0, 5],
                        [6, 16],
                        [17, 32],
                        [33, 54],
                        [55, 65],
                        [66, 200],
                    ];
                @endphp
                @foreach ($ageGroups as $gi => $group)
                    @php [$rMin, $rMax] = $ageRanges[$gi]; @endphp
                    <div class="block p-5 rounded-sm border {{ $group['border_color'] }} {{ $group['light_color'] }} hover:shadow-md transition-shadow duration-300">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                    {{ $group['label'] }}</h4>
                            </div>
                            <span class="text-2xl font-bold text-gray-800">{{ $group['percentage'] }}%</span>
                        </div>
                        <div class="mb-2">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-medium text-gray-600">{{ __('Population Count') }}</span>
                                <span class="text-sm font-bold text-gray-800">{{ $group['count'] }}</span>
                            </div>
                            <div class="w-full bg-white rounded-full h-2.5 overflow-hidden">
                                <div class="h-2.5 rounded-full {{ $group['color'] }}"
                                    style="width: {{ $group['percentage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ===== Gender Statistics ===== --}}
        <section>
            <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                <span class="w-1 h-5 bg-blue-500 rounded-full inline-block"></span>
                लिंग अनुसार जनसंख्या
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php $genderIds = [1, 2, 3]; @endphp
                @foreach ($genderGroups as $gi => $group)
                    <div class="block p-5 rounded-sm border {{ $group['border_color'] }} {{ $group['light_color'] }} hover:shadow-md transition-shadow duration-300">
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
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ===== Demographic Charts ===== --}}
        <section>
            <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                <span class="w-1 h-5 bg-indigo-500 rounded-full inline-block"></span>
                जनसांख्यिक चार्ट
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Age Bar Chart --}}
                <div class="bg-slate-50 border border-slate-100 rounded-sm p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col items-center">
                    <div class="w-full relative min-h-[380px] flex justify-center items-center">
                        <canvas id="ageDemographicsChart"></canvas>
                    </div>
                </div>

                {{-- Gender Pie Chart --}}
                <div class="bg-slate-50 border border-slate-100 rounded-sm p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col items-center">
                    <h4 class="font-bold text-lg mb-6 text-center text-slate-800 leading-tight w-full">
                        {{ __('Gender Distribution') }}</h4>
                    <div class="w-full relative min-h-[380px] flex justify-center items-center">
                        <canvas id="genderDemographicsChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== Citizenship / Residence ===== --}}
        <section>
            <h2 class="text-lg font-bold text-gray-800 mb-2 flex items-center gap-2">
                <span class="w-1 h-5 bg-teal-500 rounded-full inline-block"></span>
                बसोबास (घरधुरीको आधारमा)
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($citizenshipGroups as $group)
                    <div class="bg-white border border-gray-200 p-5 flex flex-col gap-3 hover:shadow-md transition-shadow duration-300 rounded-sm">
                        <h4 class="text-sm font-semibold text-gray-700">{{ $group['label'] }}</h4>
                        <span class="text-2xl font-bold text-gray-900">{{ $group['count'] }}</span>
                        <div class="w-full bg-gray-200 h-2 rounded-sm overflow-hidden">
                            <div class="{{ $group['color'] }} h-2 rounded-sm"
                                style="width: {{ $group['percentage'] }}%"></div>
                        </div>
                        <span class="text-xs text-gray-600 text-right">{{ number_format($group['percentage'], 2) }}%</span>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ===== Statistics Collage (Tables) ===== --}}
        <section>
            <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                <span class="w-1 h-5 bg-amber-500 rounded-full inline-block"></span>
                विस्तृत तथ्याङ्क तालिका
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-10">

                {{-- मातृभाषा --}}
                <div class="flex flex-col">
                    <h3 class="text-sm font-bold text-gray-800 border-b-2 border-gray-100 pb-2 mb-4 uppercase tracking-wider">
                        मातृभाषा</h3>
                    <div class="space-y-0 text-sm">
                        @php
                            $mtColors = [
                                'bg-blue-500', 'bg-emerald-500', 'bg-rose-500', 'bg-amber-500',
                                'bg-indigo-500', 'bg-violet-500', 'bg-cyan-500', 'bg-fuchsia-500',
                                'bg-orange-500', 'bg-teal-500', 'bg-lime-500', 'bg-pink-500',
                            ];
                        @endphp
                        @forelse ($motherTongueStats as $index => $row)
                            @php $pct = $motherTongueTotal > 0 ? number_format(($row->total / $motherTongueTotal) * 100, 2) : 0; @endphp
                            <div class="flex items-center justify-between py-3 border-b border-gray-50 group hover:bg-gray-50 transition-colors duration-150 px-2 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full {{ $mtColors[$index % count($mtColors)] }} shadow-sm flex-shrink-0"></div>
                                    <span class="text-gray-700 font-medium">{{ $row->name }}</span>
                                </div>
                                <div class="flex items-center gap-16 text-gray-600">
                                    <span class="font-semibold text-gray-900 w-16 text-right">{{ $row->total }}</span>
                                    <span class="w-16 text-right">{{ $pct }}%</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 italic text-center py-4">डेटा उपलब्ध छैन</p>
                        @endforelse
                    </div>
                </div>

                {{-- जातजातीहरु --}}
                <div class="flex flex-col">
                    <h3 class="text-sm font-bold text-gray-800 border-b-2 border-gray-100 pb-2 mb-4 uppercase tracking-wider">
                        जातजातीहरु</h3>
                    <div class="space-y-0 text-sm">
                        @php
                            $casteColors = [
                                'bg-indigo-500', 'bg-teal-500', 'bg-purple-500', 'bg-orange-500',
                                'bg-pink-500', 'bg-red-500', 'bg-sky-500', 'bg-emerald-500',
                                'bg-yellow-500', 'bg-blue-500', 'bg-gray-500', 'bg-rose-500',
                            ];
                        @endphp
                        @forelse ($casteStats as $index => $row)
                            @php $pct = $casteTotal > 0 ? number_format(($row->total / $casteTotal) * 100, 2) : 0; @endphp
                            <div class="flex items-center justify-between py-3 border-b border-gray-50 group hover:bg-gray-50 transition-colors duration-150 px-2 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full {{ $casteColors[$index % count($casteColors)] }} shadow-sm flex-shrink-0"></div>
                                    <span class="text-gray-700 font-medium">{{ $row->name }}</span>
                                </div>
                                <div class="flex items-center gap-16 text-gray-600">
                                    <span class="font-semibold text-gray-900 w-16 text-right">{{ $row->total }}</span>
                                    <span class="w-16 text-right">{{ $pct }}%</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 italic text-center py-4">डेटा उपलब्ध छैन</p>
                        @endforelse
                    </div>
                </div>

                {{-- शिक्षाको अवस्था --}}
                <div class="flex flex-col">
                    <h3 class="text-sm font-bold text-gray-800 border-b-2 border-gray-100 pb-2 mb-4 uppercase tracking-wider">
                        शिक्षाको अवस्था</h3>
                    <div class="space-y-0 text-sm">
                        @php
                            $eduColors = [
                                'bg-amber-500', 'bg-blue-500', 'bg-green-500', 'bg-orange-500',
                                'bg-red-500', 'bg-violet-500', 'bg-cyan-500', 'bg-indigo-500',
                                'bg-emerald-500', 'bg-rose-500',
                            ];
                        @endphp
                        @forelse ($educationStats as $index => $row)
                            @php $pct = $educationTotal > 0 ? number_format(($row->total / $educationTotal) * 100, 2) : 0; @endphp
                            <div class="flex items-center justify-between py-3 border-b border-gray-50 group hover:bg-gray-50 transition-colors duration-150 px-2 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full {{ $eduColors[$index % count($eduColors)] }} shadow-sm flex-shrink-0"></div>
                                    <span class="text-gray-700 font-medium">{{ $row->label }}</span>
                                </div>
                                <div class="flex items-center gap-16 text-gray-600">
                                    <span class="font-semibold text-gray-900 w-16 text-right">{{ $row->total }}</span>
                                    <span class="w-16 text-right">{{ $pct }}%</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 italic text-center py-4">डेटा उपलब्ध छैन</p>
                        @endforelse
                    </div>
                </div>

                {{-- धर्म --}}
                <div class="flex flex-col">
                    <h3 class="text-sm font-bold text-gray-800 border-b-2 border-gray-100 pb-2 mb-4 uppercase tracking-wider">
                        धर्म (जनसंख्या के आधारमा)</h3>
                    <div class="space-y-0 text-sm">
                        @php
                            $relColors = [
                                'bg-rose-500', 'bg-sky-500', 'bg-emerald-500', 'bg-amber-500',
                                'bg-indigo-500', 'bg-violet-500', 'bg-cyan-500', 'bg-fuchsia-500',
                                'bg-orange-500', 'bg-teal-500', 'bg-lime-500', 'bg-pink-500',
                            ];
                        @endphp
                        @forelse ($religionStats as $index => $row)
                            @php $pct = $religionTotal > 0 ? number_format(($row->total / $religionTotal) * 100, 2) : 0; @endphp
                            <div class="flex items-center justify-between py-3 border-b border-gray-50 group hover:bg-gray-50 transition-colors duration-150 px-2 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full {{ $relColors[$index % count($relColors)] }} shadow-sm flex-shrink-0"></div>
                                    <span class="text-gray-700 font-medium">{{ $row->label }}</span>
                                </div>
                                <div class="flex items-center gap-16 text-gray-600">
                                    <span class="font-semibold text-gray-900 w-16 text-right">{{ $row->total }}</span>
                                    <span class="w-16 text-right">{{ $pct }}%</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 italic text-center py-4">डेटा उपलब्ध छैन</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </section>

        {{-- ===== Pinned Survey Analytics ===== --}}
        @if (isset($chartsData) && count($chartsData) > 0)
            <section>
                <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                    <span class="w-1 h-5 bg-purple-500 rounded-full inline-block"></span>
                    {{ __('Pinned Survey Analytics') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach ($chartsData as $questionId => $data)
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col items-center">
                            <h4 class="font-bold text-lg mb-6 text-center text-slate-800 leading-tight w-full">
                                {{ $data['title'] }}
                            </h4>
                            <div class="w-full relative flex justify-center items-center" style="height: 380px;">
                                <canvas id="dashboard-chart-{{ $questionId }}"></canvas>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    </div>

    {{-- ===== Footer ===== --}}
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-gray-500">
            <span>© {{ date('Y') }} भरतपुर महानगरपालिका — सार्वजनिक तथ्याङ्क पोर्टल</span>
            @guest
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">प्रशासन लगइन</a>
            @endguest
        </div>
    </footer>

    {{-- ===== Chart Scripts ===== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

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
                const ageLabels = ageGroupsData.length === 6
                    ? customLabels
                    : ageGroupsData.map(g => g.label + ' ' + (g.range || ''));
                const ageCounts = ageGroupsData.map(g => g.count);
                const ageColors = ['#4f46e5', '#f97316', '#4ade80', '#ef4444', '#dc2626', '#fbbf24'];

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
                                font: { size: 16, family: "'Inter', sans-serif", weight: 'normal' },
                                color: '#4b5563',
                                padding: { bottom: 20 }
                            },
                            legend: {
                                display: true,
                                position: 'top',
                                labels: { color: '#4b5563', font: { family: "'Inter', sans-serif" } }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(156, 163, 175, 0.9)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                padding: 12,
                                cornerRadius: 6,
                                displayColors: true
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: true, color: '#e5e7eb' },
                                ticks: { color: '#6b7280', font: { family: "'Inter', sans-serif" } },
                                border: { display: true, color: '#9ca3af' }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { display: true, color: '#e5e7eb' },
                                ticks: { color: '#6b7280', font: { family: "'Inter', sans-serif" }, stepSize: 500 },
                                border: { display: true, color: '#9ca3af' }
                            }
                        },
                        layout: { padding: { top: 10, bottom: 10 } }
                    },
                    plugins: [{
                        id: 'customCanvasBackgroundColor',
                        beforeDraw: (chart, args, options) => {
                            const { ctx } = chart;
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
                const genderLabels = genderGroupsData.map(g => g.label);
                const genderCounts = genderGroupsData.map(g => g.count);
                const genderColors = ['#3E95CD', '#8E5EA2', '#3CBA9F'];

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
                                labels: { padding: 16, usePointStyle: true, pointStyle: 'circle' }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleFont: { size: 14, family: "'Inter', sans-serif" },
                                bodyFont: { size: 14, family: "'Inter', sans-serif", weight: 'bold' },
                                padding: 12,
                                cornerRadius: 8,
                            }
                        }
                    }
                });
            }

            // Pinned Survey Analytics
            const chartsData = @json($chartsData ?? []);
            const chartColors = [
                '#4f46e5', '#ec4899', '#0ea5e9', '#f59e0b',
                '#10b981', '#8b5cf6', '#f43f5e', '#14b8a6',
                '#eab308', '#6366f1', '#f97316', '#d946ef'
            ];

            Object.entries(chartsData).forEach(([questionId, data]) => {
                const labels = data.labels;
                const totals = data.totals;
                const bgColors = labels.map((_, i) => chartColors[i % chartColors.length]);
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
                                labels: { padding: 16, usePointStyle: true, pointStyle: 'circle' }
                            }
                        }
                    }
                });
            });
        });
    </script>

</body>
</html>
