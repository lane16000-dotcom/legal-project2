@extends('layouts.InternalEmployee')

@section('title', 'لوحة التحكم | منصة الإدارة القانونية')

@section('content')
    <main class="container mx-auto px-6 py-10 flex-grow">
        
        {{-- عنوان الصفحة المطور --}}
        <div class="mt-10 text-center">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">لوحة تحكم موظفي الإدارات الداخلية</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2 mb-8 text-sm">متابعة وإدارة طلبات القضايا، العقود، والاستشارات القانونية الخاصة بكم</p>
        </div>

        {{-- كروت الإحصائيات الخاصة بالموظف الداخلي الحالي --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            {{-- كارت القضايا الخاصة بي --}}
            <div class="bg-blue-50 dark:bg-gray-800 border-2 border-blue-200 dark:border-gray-700 p-6 rounded-2xl shadow-sm text-center">
                <p class="text-gray-600 dark:text-gray-400 font-bold mb-2">القضايا الخاصة بي</p>
                <span class="text-5xl font-black text-wadimakkah-dark dark:text-wadimakkah-light font-mono">{{ $stats['total_cases'] ?? 0 }}</span>
            </div>

            {{-- كارت العقود الخاصة بي --}}
            <div class="bg-blue-50 dark:bg-gray-800 border-2 border-blue-200 dark:border-gray-700 p-6 rounded-2xl shadow-sm text-center">
                <p class="text-gray-600 dark:text-gray-400 font-bold mb-2">العقود الخاصة بي</p>
                <span class="text-5xl font-black text-wadimakkah-dark dark:text-wadimakkah-light font-mono">{{ $stats['total_contracts'] ?? 0 }}</span>
            </div>

            {{-- كارت الاستشارات الخاصة بي --}}
            <div class="bg-blue-50 dark:bg-gray-800 border-2 border-blue-200 dark:border-gray-700 p-6 rounded-2xl shadow-sm text-center">
                <p class="text-gray-600 dark:text-gray-400 font-bold mb-2">الاستشارات الخاصة بي</p>
                <span class="text-5xl font-black text-wadimakkah-dark dark:text-wadimakkah-light font-mono">{{ $stats['total_consultations'] ?? 0 }}</span>
            </div>
        </div>

        {{-- أقسام الخدمات القانونية المتوفرة للإدارات --}}
        <div class="mb-12">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-6">الخدمات القانونية المتاحة</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- قسم القضايا --}}
                <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-100 dark:border-gray-600">
                        <i class="fas fa-gavel text-wadimakkah-dark dark:text-wadimakkah-light text-lg"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-800 dark:text-gray-200">طلب رفع قضية</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">تقديم طلب قضية جديدة، رفع المستندات الأساسية ومتابعة تحديثاتها الإجرائية مع الإدارة القانونية.</p>
                    <a href="{{ Route::has('cases.index') ? route('cases.index') : '#' }}" class="block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 py-2 rounded-lg text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">انتقال إلى الخدمة</a>
                </div>

                {{-- قسم العقود --}}
                <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-100 dark:border-gray-600">
                        <i class="fas fa-file-contract text-wadimakkah-dark dark:text-wadimakkah-light text-lg"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-800 dark:text-gray-200">طلب إنشاء عقد</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">إنشاء العقود و المسودات التعاقدية وإرسالها للمراجعة القانونية والاعتماد من الإدارة القانونية.</p>
                    <a href="{{ Route::has('contracts.index') ? route('contracts.index') : '#' }}" class="block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 py-2 rounded-lg text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">انتقال إلى الخدمة</a>
                </div>

                {{-- قسم الاستشارات --}}
                <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-100 dark:border-gray-600">
                        <i class="fas fa-balance-scale text-wadimakkah-dark dark:text-wadimakkah-light text-lg"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-800 dark:text-gray-200">طلب استشارة قانونية</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">طرح الاستشارات العاجلة للإدارة القانونية وتلقي الردود الرسمية المعتمدة من قبل المدير.</p>
                    <a href="{{ route('internal.consultations.create') }}" class="block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 py-2 rounded-lg text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">انتقال إلى الخدمة</a>
                </div>

            </div>
        </div>

        {{-- 📊 قسم لوحة المؤشرات البيانية (PowerBI Dashboard) المضافة حديثاً تحت الخدمات 📊 --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 mb-12">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-wadimakkah-dark dark:text-wadimakkah-light"></i> لوحة الإحصائيات والمؤشرات
                    </h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">اختر نوع لوحة البيانات لاستعراض الإحصائيات مباشرة من نظام PowerBI</p>
                </div>
                
                {{-- قائمة اختيار نوع الداشبورد المنسدلة --}}
                <div class="relative min-w-[200px]">
                    <select id="dashboardSelect" class="w-full bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-semibold py-2.5 px-4 pr-10 rounded-xl border border-gray-200 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-wadimakkah-light focus:border-transparent appearance-none transition cursor-pointer shadow-sm">
                        <option value="cases">  القضايا</option>
                        <option value="contracts">  العقود</option>
                        <option value="consultations">  الاستشارات</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            {{-- إطار عرض لوحة PowerBI --}}
            <div class="relative w-full rounded-xl overflow-hidden shadow-inner border border-gray-100 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900" style="height: 600px;">
                <iframe id="powerBIFrame" src="" frameborder="0" allowFullScreen="true" class="w-full h-full absolute inset-0"></iframe>
            </div>
        </div>
    </main>
@endsection

{{-- حقن السكريبت في الـ Stack الخاص بالملف الرئيسي ليعمل بشكل نظامي وآمن --}}
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const select = document.getElementById('dashboardSelect');
            const frame = document.getElementById('powerBIFrame');

            if (select && frame) {
                const dashboards = {
                    cases: "https://app.powerbi.com/view?r=eyJrIjoiZjI3NmZjM2YtNjUwYS00MDQ3LWI0MGUtNTk4ZWFlZjEwMzc3IiwidCI6Ijc5YTA1N2ZiLWIwZDUtNDRkZC04ZjkwLTBiZjcxNTFmNWMzZiIsImMiOjl9",
                    contracts: "https://app.powerbi.com/view?r=eyJrIjoiMjZlZTY1Y2ItZGE1MS00NzNiLTk1YWItNTY1NzNkZTlmOWFlIiwidCI6Ijc5YTA1N2ZiLWIwZDUtNDRkZC04ZjkwLTBiZjcxNTFmNWMzZiIsImMiOjl9",
                    consultations: "https://app.powerbi.com/view?r=eyJrIjoiZjA4ODY2M2QtM2Y4Mi00OTlhLTk1OTYtOTE0YzBmNWRhN2IxIiwidCI6Ijc5YTA1N2ZiLWIwZDUtNDRkZC04ZjkwLTBiZjcxNTFmNWMzZiIsImMiOjl9"
                };

                // تحميل القضايا تلقائياً كخيار افتراضي أول عند الدخول
                frame.src = dashboards["cases"];

                // الاستماع لتغيير القائمة المنسدلة وتحديث رابط الـ iframe
                select.addEventListener('change', function () {
                    frame.src = dashboards[this.value];
                });
            }
        });
    </script>
@endpush