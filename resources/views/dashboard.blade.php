<x-app-layout>
    <div class="py-24">
        <div class="px-4 sm:px-6 lg:px-8 max-w-[100%]">
            <div class="bg-white overflow-hidden shadow-sm rounded-sm">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
                        <form method="GET" class="flex items-center gap-4">
                            <select name="ward" onchange="this.form.submit()"
                                class="border rounded-lg p-3 w-48 shadow-sm focus:ring-2 focus:ring-blue-500 outline-none">
                                @if (auth()->user()->isSuperAdmin())
                                    <option value="all" {{ $selectedWard == 'all' ? 'selected' : '' }}>
                                        {{ __('All Wards') }}
                                    </option>
                                @endif
                                @foreach ($wards as $ward)
                                    <option value="{{ $ward->id }}"
                                        {{ $selectedWard == $ward->id ? 'selected' : '' }}>
                                        {{ __('Ward') }} {{ $ward->ward_no }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        <a href="{{ route('dashboard.export', ['ward' => $selectedWard]) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            {{ __('Export to CSV') }}
                        </a>
                    </div>

                    <!-- Age Group Info Boxes -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                        @php
                            $ageRanges = [[0, 5], [6, 16], [17, 32], [33, 54], [55, 65], [66, 200]];
                            $ageImages = [
                                'infant.webp',
                                'children.png',
                                'youth.png',
                                'adult.jpg',
                                'elderly.webp',
                                'senior-citizen.png',
                            ];
                        @endphp
                        @foreach ($ageGroups as $gi => $group)
                            @php [$rMin, $rMax] = $ageRanges[$gi]; @endphp
                            <a href="{{ route('dashboard.members', ['filter_type' => 'age_group', 'range_min' => $rMin, 'range_max' => $rMax, 'ward' => $selectedWard, 'label' => $group['label']]) }}"
                                class="relative block p-5 rounded-sm border {{ $group['border_color'] }} {{ $group['light_color'] }} hover:shadow-md transition-shadow duration-300 overflow-hidden">
                                <div
                                    class="absolute right-0 top-0 bottom-0 w-1/3 opacity-5 pointer-events-none overflow-hidden rounded-sm flex items-center justify-center">
                                    <img src="{{ asset('assets/images/' . $ageImages[$gi]) }}"
                                        alt="{{ $group['label'] }}" class="w-full h-full object-contain object-center">
                                </div>

                                <!-- Content -->
                                <div class="relative z-10">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                                @if (app()->getLocale() === 'np')
                                                    {{ $group['label'] }}
                                                    ({{ \App\Helpers\NepaliHelper::toNepaliNumber($group['range']) }})
                                                @else
                                                    {{ $group['label'] }} ({{ $group['range'] }})
                                                @endif
                                            </h4>

                                        </div>
                                        <span class="text-2xl font-bold text-gray-800">
                                            @if (app()->getLocale() === 'np')
                                                {{ \App\Helpers\NepaliHelper::toNepaliPercentage($group['percentage']) }}
                                            @else
                                                {{ $group['percentage'] }}%
                                            @endif
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <div class="flex justify-between items-center mb-1">
                                            <span
                                                class="text-xs font-medium text-gray-600">{{ __('Population Count') }}</span>
                                            <span class="text-sm font-bold text-gray-800">
                                                @if (app()->getLocale() === 'np')
                                                    {{ \App\Helpers\NepaliHelper::toNepaliNumber($group['count']) }}
                                                @else
                                                    {{ $group['count'] }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="w-full bg-white rounded-full h-2.5 overflow-hidden">
                                            <div class="h-2.5 rounded-full {{ $group['color'] }}"
                                                style="width: {{ $group['percentage'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>


                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                        @php
                            $genderIds = [1, 2, 3];
                            $genderImages = ['male.jpg', 'female.png'];
                        @endphp
                        @foreach ($genderGroups as $gi => $group)
                            <a href="{{ route('dashboard.members', ['filter_type' => 'gender', 'gender_id' => $genderIds[$gi], 'ward' => $selectedWard, 'label' => $group['label']]) }}"
                                class="relative block p-5 rounded-sm border {{ $group['border_color'] }} {{ $group['light_color'] }} hover:shadow-md transition-shadow duration-300 overflow-hidden">

                                @if ($gi < 2)
                                    <div
                                        class="absolute right-0 top-0 bottom-0 w-1/3 opacity-5 pointer-events-none overflow-hidden rounded-sm flex items-center justify-center">
                                        <img src="{{ asset('assets/images/' . $genderImages[$gi]) }}"
                                            alt="{{ $group['label'] }}"
                                            class="w-full h-full object-contain object-center">
                                    </div>
                                @endif

                                <!-- Content -->
                                <div class="relative z-10">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                                {{ $group['label'] }}</h4>
                                        </div>
                                        <span class="text-2xl font-bold text-gray-800">
                                            @if (app()->getLocale() === 'np')
                                                {{ \App\Helpers\NepaliHelper::toNepaliPercentage($group['percentage']) }}
                                            @else
                                                {{ $group['percentage'] }}%
                                            @endif
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <div class="flex justify-between items-center mb-1">
                                            <span
                                                class="text-xs font-medium text-gray-600">{{ __('Total Count') }}</span>
                                            <span class="text-sm font-bold text-gray-800">
                                                @if (app()->getLocale() === 'np')
                                                    {{ \App\Helpers\NepaliHelper::toNepaliNumber($group['count']) }}
                                                @else
                                                    {{ $group['count'] }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="w-full bg-white rounded-full h-2.5 overflow-hidden">
                                            <div class="h-2.5 rounded-full {{ $group['color'] }}"
                                                style="width: {{ $group['percentage'] }}%"></div>
                                        </div>
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
                                class="bg-slate-50 border border-slate-100 rounded-sm p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col items-center">
                                <div class="w-full relative min-h-[380px] flex justify-center items-center">
                                    <canvas id="ageDemographicsChart"></canvas>
                                </div>
                            </div>

                            <!-- Gender Demographics Pie Chart -->
                            <div
                                class="bg-slate-50 border border-slate-100 rounded-sm p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col items-center">
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
                            <a href="{{ route('dashboard.members', ['filter_type' => 'citizenship', 'id' => $group['id'], 'ward' => $selectedWard, 'label' => $group['label']]) }}"
                                class="block bg-white border border-gray-200 p-5 flex flex-col gap-3 hover:shadow-md transition-shadow duration-300 rounded-sm">
                                <h4 class="text-sm font-semibold text-gray-700">
                                    {{ $group['label'] }}
                                </h4>

                                <span class="text-2xl font-bold text-gray-900">
                                    @if (app()->getLocale() === 'np')
                                        {{ \App\Helpers\NepaliHelper::toNepaliNumber($group['count']) }}
                                    @else
                                        {{ $group['count'] }}
                                    @endif
                                </span>

                                <div class="w-full bg-gray-200 h-2 rounded-sm overflow-hidden">
                                    <div class="{{ $group['color'] }} h-2 rounded-sm"
                                        style="width: {{ $group['percentage'] }}%">
                                    </div>
                                </div>

                                <span class="text-xs text-gray-600 text-right">
                                    @if (app()->getLocale() === 'np')
                                        {{ \App\Helpers\NepaliHelper::toNepaliPercentage($group['percentage'], 2) }}
                                    @else
                                        {{ number_format($group['percentage'], 2) }}%
                                    @endif
                                </span>
                            </a>
                        @endforeach
                    </div>


                    {{-- Flexbox per-column: each column stacks its panels independently --}}
                    <div class="flex flex-col lg:flex-row gap-x-12 gap-y-10 mt-12 mb-16 items-start">

                        {{-- LEFT COLUMN: मातृभाषा + शिक्षाको अवस्था --}}
                        <div class="flex flex-col flex-1 gap-10">

                            {{-- मातृभाषा --}}
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
                                        <a href="{{ route('dashboard.members', ['filter_type' => 'mother_tongue', 'id' => $row->id, 'ward' => $selectedWard, 'label' => $row->name]) }}"
                                            class="flex items-center justify-between py-3 border-b border-gray-50 group hover:bg-gray-50 transition-colors duration-150 px-2 rounded-lg">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-2 h-2 rounded-full {{ $mtColors[$index % count($mtColors)] }} shadow-sm">
                                                </div>
                                                <span class="text-gray-700 font-medium">{{ $row->name }}</span>
                                            </div>
                                            <div class="flex items-center gap-16 text-gray-600">
                                                <span class="font-semibold text-gray-900 w-16 text-right">
                                                    @if (app()->getLocale() === 'np')
                                                        {{ \App\Helpers\NepaliHelper::toNepaliNumber($row->total) }}
                                                    @else
                                                        {{ $row->total }}
                                                    @endif
                                                </span>
                                                <span class="w-16 text-right">
                                                    @if (app()->getLocale() === 'np')
                                                        {{ \App\Helpers\NepaliHelper::toNepaliPercentage($pct) }}
                                                    @else
                                                        {{ $pct }}%
                                                    @endif
                                                </span>
                                            </div>
                                        </a>
                                    @empty
                                        <p class="text-gray-400 italic text-center py-4">डेटा उपलब्ध छैन</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- शिक्षाको अवस्था --}}
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
                                        <a href="{{ route('dashboard.members', ['filter_type' => 'education', 'id' => $row->id, 'ward' => $selectedWard, 'label' => $row->label]) }}"
                                            class="flex items-center justify-between py-3 border-b border-gray-50 group hover:bg-gray-50 transition-colors duration-150 px-2 rounded-lg">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-2 h-2 rounded-full {{ $eduColors[$index % count($eduColors)] }} shadow-sm">
                                                </div>
                                                <span class="text-gray-700 font-medium">{{ $row->label }}</span>
                                            </div>
                                            <div class="flex items-center gap-16 text-gray-600">
                                                <span class="font-semibold text-gray-900 w-16 text-right">
                                                    @if (app()->getLocale() === 'np')
                                                        {{ \App\Helpers\NepaliHelper::toNepaliNumber($row->total) }}
                                                    @else
                                                        {{ $row->total }}
                                                    @endif
                                                </span>
                                                <span class="w-16 text-right">
                                                    @if (app()->getLocale() === 'np')
                                                        {{ \App\Helpers\NepaliHelper::toNepaliPercentage($pct) }}
                                                    @else
                                                        {{ $pct }}%
                                                    @endif
                                                </span>
                                            </div>
                                        </a>
                                    @empty
                                        <p class="text-gray-400 italic text-center py-4">डेटा उपलब्ध छैन</p>
                                    @endforelse
                                </div>
                            </div>

                        </div>{{-- end LEFT COLUMN --}}

                        {{-- RIGHT COLUMN: जातजातीहरु + धर्म --}}
                        <div class="flex flex-col flex-1 gap-10">

                            {{-- जातजातीहरु --}}
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
                                        <a href="{{ route('dashboard.members', ['filter_type' => 'caste', 'id' => $row->id, 'ward' => $selectedWard, 'label' => $row->name]) }}"
                                            class="flex items-center justify-between py-3 border-b border-gray-50 group hover:bg-gray-50 transition-colors duration-150 px-2 rounded-lg">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-2 h-2 rounded-full {{ $casteColors[$index % count($casteColors)] }} shadow-sm">
                                                </div>
                                                <span class="text-gray-700 font-medium">{{ $row->name }}</span>
                                            </div>
                                            <div class="flex items-center gap-16 text-gray-600">
                                                <span class="font-semibold text-gray-900 w-16 text-right">
                                                    @if (app()->getLocale() === 'np')
                                                        {{ \App\Helpers\NepaliHelper::toNepaliNumber($row->total) }}
                                                    @else
                                                        {{ $row->total }}
                                                    @endif
                                                </span>
                                                <span class="w-16 text-right">
                                                    @if (app()->getLocale() === 'np')
                                                        {{ \App\Helpers\NepaliHelper::toNepaliPercentage($pct) }}
                                                    @else
                                                        {{ $pct }}%
                                                    @endif
                                                </span>
                                            </div>
                                        </a>
                                    @empty
                                        <p class="text-gray-400 italic text-center py-4">डेटा उपलब्ध छैन</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- धर्म --}}
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
                                        <a href="{{ route('dashboard.members', ['filter_type' => 'religion', 'id' => $row->id, 'ward' => $selectedWard, 'label' => $row->label]) }}"
                                            class="flex items-center justify-between py-3 border-b border-gray-50 group hover:bg-gray-50 transition-colors duration-150 px-2 rounded-lg">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-2 h-2 rounded-full {{ $relColors[$index % count($relColors)] }} shadow-sm">
                                                </div>
                                                <span class="text-gray-700 font-medium">{{ $row->label }}</span>
                                            </div>
                                            <div class="flex items-center gap-16 text-gray-600">
                                                <span class="font-semibold text-gray-900 w-16 text-right">
                                                    @if (app()->getLocale() === 'np')
                                                        {{ \App\Helpers\NepaliHelper::toNepaliNumber($row->total) }}
                                                    @else
                                                        {{ $row->total }}
                                                    @endif
                                                </span>
                                                <span class="w-16 text-right">
                                                    @if (app()->getLocale() === 'np')
                                                        {{ \App\Helpers\NepaliHelper::toNepaliPercentage($pct) }}
                                                    @else
                                                        {{ $pct }}%
                                                    @endif
                                                </span>
                                            </div>
                                        </a>
                                    @empty
                                        <p class="text-gray-400 italic text-center py-4">डेटा उपलब्ध छैन</p>
                                    @endforelse
                                </div>
                            </div>

                        </div>{{-- end RIGHT COLUMN --}}

                    </div>{{-- end flex row --}}

                    @if (isset($chartsData) && count($chartsData) > 0)
                        <div class="mb-12">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
                                    {{ __('Survey Analytics') }}</h2>
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

    <script>
        window.dashboardChartData = {
            chartsData: @json($chartsData ?? []),
            ageGroups: @json($ageGroups ?? []),
            genderGroups: @json($genderGroups ?? [])
        };

        window.dashboardCharts = window.dashboardCharts || {};

        function initializeDashboardCharts() {
            if (typeof Chart === 'undefined') {
                console.warn('[Dashboard Charts] Chart.js not loaded yet');
                setTimeout(initializeDashboardCharts, 50);
                return;
            }

            Object.values(window.dashboardCharts).forEach(chart => {
                if (chart && typeof chart.destroy === 'function') {
                    chart.destroy();
                }
            });
            window.dashboardCharts = {};

            const chartsData = window.dashboardChartData.chartsData;
            const ageGroupsData = window.dashboardChartData.ageGroups;
            const genderGroupsData = window.dashboardChartData.genderGroups;

            const colors = [
                '#6366a6',
                '#d16b9f',
                '#4fa3c4',
                '#d4a94f',
                '#4fbf9f',
                '#8a6fbf',
                '#d46a7a',
                '#4fb3ad',
                '#c9b458',
                '#6c72c9',
                '#d48a4f',
                '#c96ad4'
            ];

            Object.entries(chartsData).forEach(([questionId, data]) => {
                const labels = data.labels;
                const totals = data.totals;
                const bgColors = labels.map((_, i) => colors[i % colors.length]);
                const ctx = document.getElementById(`dashboard-chart-${questionId}`);
                if (!ctx) return;

                window.dashboardCharts[`dashboard-chart-${questionId}`] = new Chart(ctx, {
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
                                position: window.innerWidth < 768 ? 'bottom' : 'right',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    boxWidth: 8,
                                    font: {
                                        size: 12,
                                        family: "'Inter', sans-serif",
                                        weight: '500'
                                    }
                                }
                            }
                        }
                    }
                });

            });

            const ageCtx = document.getElementById('ageDemographicsChart');

            if (ageCtx && ageGroupsData.length > 0) {
                if (window.dashboardCharts.ageChart) {
                    window.dashboardCharts.ageChart.destroy();
                }

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

                window.dashboardCharts.ageChart = new Chart(ageCtx, {
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

            const genderCtx = document.getElementById('genderDemographicsChart');

            if (genderCtx && genderGroupsData.length > 0) {
                if (window.dashboardCharts.genderChart) {
                    window.dashboardCharts.genderChart.destroy();
                }

                const genderLabels = genderGroupsData.map(group => group.label);
                const genderCounts = genderGroupsData.map(group => group.count);
                const genderColors = ['#3E95CD', ' #8E5EA2', '#3CBA9F'];

                window.dashboardCharts.genderChart = new Chart(genderCtx, {
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
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    boxWidth: 8,
                                    font: {
                                        size: 12,
                                        family: "'Inter', sans-serif",
                                        weight: '500'
                                    }
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
        }

        // Initialize on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeDashboardCharts);
        } else {
            initializeDashboardCharts();
        }

        // Reinitialize when AJAX loads new content
        window.addEventListener('reinitialize-charts', initializeDashboardCharts);

        window.initializeDashboardCharts = initializeDashboardCharts;
    </script>

</x-app-layout>
