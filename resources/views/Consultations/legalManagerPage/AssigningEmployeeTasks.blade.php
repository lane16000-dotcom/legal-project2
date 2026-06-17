@extends('layouts.LegalManager')

@section('title', 'إسناد المهام الداخلية | منصة الإدارة القانونية')

@section('content')
<main class="container mx-auto px-6 py-10 flex-grow max-w-5xl">
    
    {{-- رأس الصفحة --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 dark:border-gray-700 pb-5">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">إسناد وتكليف المهام الداخلية</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">إنشاء تكليفات ومهام عمل إدارية وتوجيهها مباشرة للمستشارين القانونيين</p>
        </div>
    </div>

    {{-- فورم إنشاء المهمة الداخلية --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8 text-right">
        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2">
            <i class="fas fa-plus-circle text-blue-600"></i> إضافة تكليف بمهمة جديدة
        </h3>
        
        <form action="{{ route('manager.internal-tasks.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-400">عنوان المهمة التكليفية</label>
                    <input type="text" name="title" required placeholder="مثلاً: صياغة اللائحة التنفيذية الداخلية..." class="p-2.5 bg-gray-50 dark:bg-gray-700 text-xs rounded-xl border dark:border-gray-600 text-gray-700 dark:text-gray-200 outline-none focus:border-blue-50">
                </div>
                
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-400">اختر المستشار القانوني المكلف</label>
                    <select name="assigned_to" required class="p-2.5 bg-gray-50 dark:bg-gray-700 text-xs rounded-xl border dark:border-gray-600 text-gray-600 dark:text-gray-300 outline-none">
                        <option value="" disabled selected>-- حدد المستشار القانوني لتنفيذ المهمة --</option>
                        @foreach($legalEmployees as $employee)
                            <option value="{{ $employee->user_id }}">الأستاذ(ة): {{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-400">تفاصيل ومضمون التكليف الإداري</label>
                <textarea name="description" required rows="3" placeholder="اكتب تفاصيل وشروط ومضمون المهمة المطلوبة بدقة هنا..." class="p-3 bg-gray-50 dark:bg-gray-700 text-xs rounded-xl border dark:border-gray-600 text-gray-700 dark:text-gray-200 outline-none resize-none"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow-md transition active:scale-95 cursor-pointer">
                    <i class="fas fa-check-circle ml-1"></i> إرسال وتكليف المستشار فوراً
                </button>
            </div>
        </form>
    </div>

    {{-- جدول متابعة المهام المسندة --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-4">سجل متابعة المهام الداخلية الجارية</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700 text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="pb-3">عنوان التكليف الداخلي</th>
                        <th class="pb-3">المستشار المكلف</th>
                        <th class="pb-3">تاريخ التكليف</th>
                        <th class="pb-3 text-center">الحالة</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-medium divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($internalTasks as $task)
                        @php
                            $createdAtCarbon = \Carbon\Carbon::parse($task->created_at)->timezone('Asia/Riyadh');
                            $formattedDate = $createdAtCarbon->locale('ar')->translatedFormat('Y/m/d h:i a');
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition">
                            <td class="py-4 text-gray-800 dark:text-gray-200 font-bold max-w-[240px] truncate">{{ $task->title }}</td>
                            <td class="py-4 text-xs text-gray-600 dark:text-gray-400">{{ $task->employee_name }}</td>
                            <td class="py-4 text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $formattedDate }}</td>
                            <td class="py-4 text-center">
                                @if($task->status == 'قيد الدراسة' || $task->status == 'قيد المراجعة')
                                    <span class="px-2.5 py-0.5 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 text-xs font-bold rounded-md border border-indigo-200">قيد التنفيذ</span>
                                @elseif($task->status == 'تم الرد' || $task->status == 'مكتملة')
                                    <span class="px-2.5 py-0.5 bg-green-50 dark:bg-green-950/40 text-green-600 dark:text-green-400 text-xs font-bold rounded-md border border-green-200">مكتملة</span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-gray-100 text-gray-600 text-xs font-bold rounded-md border">{{ $task->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-sm text-gray-400">لا توجد مهام وتكليفات داخلية مضافة حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection