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

         <!-- لوحة التحكم -->
    <div class="bg-wadimakkah-dark text-white p-4 text-center rounded-t-lg font-bold text-lg">
        لوحة التحكم
    </div>

    <div class="bg-white p-8 rounded-b-lg shadow-sm border">

        <div class="flex flex-col items-center">

            <!-- SELECT -->
            <select id="dashboardSelect"
                class="bg-white px-6 py-3 rounded-xl shadow-md text-sm outline-none cursor-pointer mb-6 border">
                
                <option value="cases" selected>قضايا</option>
                <option value="contracts">عقود</option>
                <option value="consultations">استشارات</option>

            </select>

            <!-- IFRAME -->
            <div class="bg-white p-4 rounded-2xl shadow-md w-full max-w-5xl">
                <iframe id="powerBIFrame"
                    class="w-full h-[520px] rounded-lg"
                    frameborder="0"
                    allowFullScreen="true">
                </iframe>
            </div>

        </div>
    </div>
    </main>
    <script>
document.addEventListener("DOMContentLoaded", function () {

    const select = document.getElementById('dashboardSelect');
    const frame = document.getElementById('powerBIFrame');

    const dashboards = {
        cases: "https://app.powerbi.com/view?r=eyJrIjoiZjI3NmZjM2YtNjUwYS00MDQ3LWI0MGUtNTk4ZWFlZjEwMzc3IiwidCI6Ijc5YTA1N2ZiLWIwZDUtNDRkZC04ZjkwLTBiZjcxNTFmNWMzZiIsImMiOjl9",
        
        contracts: "https://app.powerbi.com/view?r=eyJrIjoiMjZlZTY1Y2ItZGE1MS00NzNiLTk1YWItNTY1NzNkZTlmOWFlIiwidCI6Ijc5YTA1N2ZiLWIwZDUtNDRkZC04ZjkwLTBiZjcxNTFmNWMzZiIsImMiOjl9",

        consultations: "https://app.powerbi.com/view?r=eyJrIjoiZjA4ODY2M2QtM2Y4Mi00OTlhLTk1OTYtOTE0YzBmNWRhN2IxIiwidCI6Ijc5YTA1N2ZiLWIwZDUtNDRkZC04ZjkwLTBiZjcxNTFmNWMzZiIsImMiOjl9"
    };

    //  تحميل القضايا تلقائياً
    frame.src = dashboards["cases"];

    // تغيير الداشبورد
    select.addEventListener('change', function () {
        frame.src = dashboards[this.value];
    });

});
</script>
@endsection