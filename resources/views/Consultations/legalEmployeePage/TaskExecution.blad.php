@extends('layouts.LegalEmployee')

@section('title', 'تنفيذ المهام الداخلية | منصة الإدارة القانونية')

@section('content')
<main class="container mx-auto px-6 py-10 flex-grow max-w-5xl">
    
    {{-- رأس الصفحة --}}
    <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-5">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">صندوق تنفيذ المهام الداخلية</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">استعراض وإنجاز التكليفات الإدارية المباشرة الموجهة إليك من قِبل مدير الإدارة</p>
    </div>

    {{-- قسم الرسم البياني السنوي لمراقبة الأداء حياً --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8 text-right">
        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-bar text-emerald-600"></i> مؤشر حجم وإنجاز المهام الداخلية خلال السنة الحالية (2026)
        </h3>
        <div class="relative w-full h-[220px]">
            <canvas id="yearlyPerformanceChart"></canvas>
        </div>
    </div>

    {{-- جدول عرض وتحديث المهام --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 text-right">
        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-4">قائمة التكليفات الجارية</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700 text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="pb-3">موضوع التكليف</th>
                        <th class="pb-3">مضمون ومضمون المهمة</th>
                        <th class="pb-3">تاريخ التكليف</th>
                        <th class="pb-3 text-center">الحالة</th>
                        <th class="pb-3 text-left pl-4">الإجراء</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-medium divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($myTasks as $task)
                        @php
                            $createdAtCarbon = \Carbon\Carbon::parse($task->created_at)->timezone('Asia/Riyadh');
                            $formattedDate = $createdAtCarbon->locale('ar')->translatedFormat('Y/m/d h:i a');
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition">
                            <td class="py-4 text-gray-800 dark:text-gray-200 font-bold max-w-[150px] truncate">{{ $task->title }}</td>
                            <td class="py-4 text-xs text-gray-500 max-w-[200px] truncate">{{ $task->description }}</td>
                            <td class="py-4 text-xs text-gray-400 font-mono">{{ $formattedDate }}</td>
                            <td class="py-4 text-center">
                                @if($task->status == 'قيد الدراسة' || $task->status == 'قيد المراجعة')
                                    <span class="px-2.5 py-0.5 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 text-xs font-bold rounded-md border border-indigo-200">قيد التنفيذ</span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-green-50 dark:bg-green-950/40 text-green-600 dark:text-green-400 text-xs font-bold rounded-md border border-green-200">مكتملة</span>
                                @endif
                            </td>
                            <td class="py-4 text-left pl-4">
                                @if($task->status == 'قيد الدراسة' || $task->status == 'قيد المراجعة')
                                    <form action="{{ route('employee.tasks-execution.update', $task->consultation_id) }}" method="POST" class="m-0 flex items-center justify-end gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="مكتملة">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] px-3 py-1.5 rounded-xl shadow-xs transition active:scale-95 cursor-pointer">
                                            <i class="fas fa-check"></i> تحديد كمكتملة
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400 font-bold"><i class="fas fa-check-double text-green-500"></i> تم الإنجاز</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-sm text-gray-400">لا توجد تكليفات أو مهام داخلية مسندة إليك حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection

@push('scripts')
{{-- 🌟 استدعاء مكتبة Chart.js من السيرفر لبناء الرسم البياني السنوي المطور حياً --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('yearlyPerformanceChart').getContext('2d');
        
        // جلب مصفوفة الـ 12 شهراً الممررة ديناميكياً من الكنترولر حياً
        const chartDataArray = @json($chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
                datasets: [{
                    label: 'عدد المهام الداخلية المنجزة والمسندة',
                    data: chartDataArray,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#10b981'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { family: 'sans-serif', size: 10 } }
                    },
                    x: {
                        ticks: { font: { family: 'sans-serif', size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endpush