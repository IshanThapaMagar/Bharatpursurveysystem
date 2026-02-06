<x-app-layout>
    <div class="py-24">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Ward Dropdown -->
                    <form method="GET" class="mb-8">
                        <select name="ward" onchange="this.form.submit()" class="border rounded-lg p-2 w-48">
                            @foreach ($wards as $ward)
                                <option value="{{ $ward->id }}" {{ $selectedWard == $ward->id ? 'selected' : '' }}>
                                    Ward {{ $ward->ward_no }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <!-- Charts in 2 columns -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($charts as $questionId => $data)
                            <div class="bg-gray-50 p-4 rounded-lg shadow">
                                <h3 class="font-bold mb-2 text-center">
                                    {{ $data->first()->question_text }}
                                </h3>
                                <div style="width: 100%; height: 300px;">
                                    <canvas id="chart-{{ $questionId }}"></canvas>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const charts = @json($charts);

                // Some nice colors for slices
                const colors = [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#C9CBCF', '#8DD1E1',
                    '#A2D39C', '#F67019'
                ];

                Object.entries(charts).forEach(([questionId, data]) => {

                    const labels = data.map(d => d.choice_text);
                    const totals = data.map(d => d.total);

                    // Repeat colors if not enough
                    const bgColors = labels.map((_, i) => colors[i % colors.length]);

                    new Chart(document.getElementById(`chart-${questionId}`), {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: totals,
                                backgroundColor: bgColors
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });

                });

            });
        </script>
    @endpush
</x-app-layout>
