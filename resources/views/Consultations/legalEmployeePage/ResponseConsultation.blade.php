@extends('layouts.LegalEmployee')

@section('title', 'إدارة الاستشارات القانونية | منصة الإدارة القانونية')

@section('content')
    <main class="container mx-auto px-6 py-10 flex-grow max-w-5xl">
        
        {{-- رأس الصفحة --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 dark:border-gray-700 pb-5">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">إدارة الاستشارات والمهام القانونية</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">استعراض الطلبات المسندة إليك من قِبل المدير وصياغة الردود عليها</p>
            </div>
            
            @php
                $pendingCount = $consultations->count();
            @endphp
            <span id="total-badge" class="self-start sm:self-center text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-bold px-3 py-1.5 rounded-xl border border-blue-100 dark:border-blue-800 shadow-sm">
                المعاملات الجارية والمسندة: {{ $pendingCount }} معاملة
            </span>
        </div>

        {{-- شريط التصفية والبحث المتقدم الموحد والمطابق تماماً لصفحات المدير --}}
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="flex flex-wrap items-end gap-3 w-full">
                
                {{-- البحث بالعنوان --}}
                <div class="flex-1 min-w-[150px] flex flex-col gap-1">
                    <label class="text-[11px] font-bold text-gray-400 dark:text-gray-500">البحث بالعنوان</label>
                    <div class="flex items-center bg-gray-50 dark:bg-gray-700/50 px-2.5 py-1.5 rounded-xl border border-gray-100 dark:border-gray-600">
                        <i class="fas fa-search text-gray-400 ml-2 text-[10px]"></i>
                        <input type="text" id="search-title" placeholder="عنوان المعاملة..." class="w-full bg-transparent text-xs outline-none text-gray-700 dark:text-gray-200">
                    </div>
                </div>

                {{-- نوع العملية المحدث بدعم كامل للقضايا --}}
                <div class="w-32 flex flex-col gap-1">
                    <label class="text-[11px] font-bold text-gray-400 dark:text-gray-500">نوع العملية</label>
                    <select id="filter-category" class="w-full p-1.5 bg-gray-50 dark:bg-gray-700/50 text-xs font-semibold rounded-xl border border-gray-100 dark:border-gray-600 text-gray-600 dark:text-gray-300 outline-none">
                        <option value="all">كل العمليات</option>
                        <option value="استشارة">استشارات</option>
                        <option value="عقد">عقود</option>
                        <option value="قضية">قضايا</option>
                    </select>
                </div>

                {{-- من تاريخ --}}
                <div class="w-36 flex flex-col gap-1">
                    <label class="text-[11px] font-bold text-gray-400 dark:text-gray-500">من تاريخ</label>
                    <input type="date" id="filter-date-from" class="w-full p-1.5 bg-gray-50 dark:bg-gray-700/50 text-xs font-semibold rounded-xl border border-gray-100 dark:border-gray-600 text-gray-600 dark:text-gray-300 outline-none font-mono">
                </div>

                {{-- إلى تاريخ --}}
                <div class="w-36 flex flex-col gap-1">
                    <label class="text-[11px] font-bold text-gray-400 dark:text-gray-500">إلى تاريخ</label>
                    <input type="date" id="filter-date-to" class="w-full p-1.5 bg-gray-50 dark:bg-gray-700/50 text-xs font-semibold rounded-xl border border-gray-100 dark:border-gray-600 text-gray-600 dark:text-gray-300 outline-none font-mono">
                </div>

                {{-- زر التطبيق الأفقي --}}
                <button onclick="applyEmployeeFilters()" class="bg-wadimakkah-dark hover:bg-blue-800 text-white px-5 py-2 rounded-xl text-xs font-bold shadow-sm transition cursor-pointer flex items-center justify-center h-[34px]">
                    تطبيق
                </button>

            </div>
        </div>

        {{-- جدول الاستشارات والمهام القانونية المسندة للموظف ديناميكياً --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-700 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <th class="pb-3">نوع العملية</th>
                            <th class="pb-3">تصنيف المعاملة</th>
                            <th class="pb-3">عنوان المعاملة</th>
                            <th class="pb-3">تاريخ ووقت الإرسال</th>
                            <th class="pb-3 text-center">الحالة الحالية</th>
                            <th class="pb-3 text-left pl-4">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="employee-tasks-body" class="text-sm font-medium divide-y divide-gray-50 dark:divide-gray-700/50">
                        @forelse($consultations as $consultation)
                            @php
                                $dateRaw = is_string($consultation->created_at) ? date('Y-m-d', strtotime($consultation->created_at)) : $consultation->created_at->format('Y-m-d');
                                
                                // 🌟 فصل الأنوع والتصنيفات بدقة تامة حياً منعاً لأي خلط
                                $processType = $consultation->type_category ?? 'استشارة'; 
                                $processClassification = $consultation->type ?? 'عام';
                                
                                $createdAtCarbon = \Carbon\Carbon::parse($consultation->created_at)->timezone('Asia/Riyadh');
                                $formattedDateTimeSaudi = $createdAtCarbon->locale('ar')->translatedFormat('Y/m/d h:i a');
                            @endphp
                            <tr class="employee-task-row hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition"
                                data-title="{{ $consultation->title }}"
                                data-category="{{ $processType }}"
                                data-date="{{ $dateRaw }}">
                                
                                {{-- عمود نوع العملية (قضية / عقد / استشارة) --}}
                                <td class="py-4">
                                    @if($processType == 'عقد')
                                        <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 text-xs font-bold rounded-lg border border-blue-100 dark:border-blue-900/60">عقد</span>
                                    @elseif($processType == 'قضية')
                                        <span class="px-2.5 py-1 bg-yellow-50 dark:bg-yellow-950/40 text-yellow-700 dark:text-yellow-400 text-xs font-bold rounded-lg border border-yellow-100 dark:border-yellow-900/60">قضية</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-green-50 dark:bg-green-950/40 text-green-700 dark:text-green-400 text-xs font-bold rounded-lg border border-green-100 dark:border-green-900/60">استشارة</span>
                                    @endif
                                </td>

                                {{-- عمود تصنيف العملية (عمالي / شركات / عقود) --}}
                                <td class="py-4 text-xs text-gray-600 dark:text-gray-400 font-semibold">
                                    {{ $processClassification }}
                                </td>

                                <td class="py-4 text-gray-800 dark:text-gray-200 font-bold max-w-[180px] truncate">{{ $consultation->title }}</td>
                                <td class="py-4 text-xs text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $formattedDateTimeSaudi }}
                                </td>
                                <td class="py-4 text-center">
                                    <span class="px-2.5 py-0.5 bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 text-xs font-bold rounded-md border border-amber-200 dark:border-amber-900/40">
                                        <i class="fas fa-clock text-[10px] ml-0.5"></i> {{ $consultation->status }}
                                    </span>
                                </td>
                                <td class="py-4 text-left whitespace-nowrap pr-4">
                                    <button 
                                        onclick="openResponseModal('{{ $consultation->consultation_id }}', '{{ addslashes($consultation->title) }}', '{{ addslashes($processType) }}', '{{ addslashes($processClassification) }}', '{{ addslashes($consultation->description) }}', '{{ $consultation->attachment ?? '' }}')" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2 rounded-xl shadow-xs transition active:scale-[0.98] cursor-pointer inline-flex items-center gap-2">
                                        <i class="fas fa-balance-scale text-xs"></i> صياغة الرد القانوني
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="employee-empty-row">
                                <td colspan="6" class="py-16 text-center text-sm text-gray-400 dark:text-gray-500">
                                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300 dark:text-gray-600 border dark:border-gray-700">
                                        <i class="fas fa-inbox text-xl"></i>
                                    </div>
                                    <p class="font-bold text-gray-700 dark:text-gray-300 mb-0.5">الصندوق فارغ تماماً</p>
                                    لا توجد معاملات قانونية مسندة إليك حالياً من قِبل المدير.
                                </td>
                            </tr>
                        @endforelse
                        
                        <tr id="employee-filter-empty" class="hidden">
                            <td colspan="6" class="py-12 text-center text-sm text-gray-400">
                                <i class="fas fa-search block text-xl mb-2 text-gray-300 dark:text-gray-700"></i>
                                لا توجد معاملات مسندة تطابق خيارات التصفية المحددة.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    {{-- 🔹 🌟 النافذة المنبثقة (Modal) للرد متطابقة في التصميم والحجم المرن مع نافذة السجل بالملي 🌟 🔹 --}}
    <div id="response-modal" class="hidden fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4 backdrop-blur-md animate-fadeIn">
        <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-100 dark:border-gray-800 w-full max-w-5xl h-[85vh] flex flex-col shadow-2xl overflow-hidden max-h-[90vh]">
            
            {{-- هيدر المودال الموحد بستايل فخم ومريح --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700/80 flex justify-between items-center flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <i class="fas fa-gavel text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-gray-800 dark:text-gray-100">صياغة الرد القانوني الرسمي</h3>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">صياغة الردود القانونية بشكل دقيق تمهيداً لرفعها وطلب الاعتماد من المدير</p>
                    </div>
                </div>
                <button onclick="closeResponseModal()" class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-red-500 hover:text-white dark:hover:bg-red-600 transition flex items-center justify-center text-gray-500 dark:text-gray-400">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            {{-- فورم الإرسال الفعلي المعتمد --}}
            <form id="modal-response-form" method="POST" enctype="multipart/form-data" class="m-0 flex flex-col flex-grow overflow-hidden">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                {{-- جسم المودال المقسم لعمودين متوازيين متناسق تماماً مع واجهة السجل --}}
                <div class="flex-grow overflow-y-auto p-6 grid grid-cols-1 md:grid-cols-2 gap-6 items-start text-right">
                    
                    {{-- 🔹 القسم الأيمن: تفاصيل المعاملة والطلب الأساسي مع المرفقات الأصلية 🔹 --}}
                    <div class="space-y-4 bg-gray-50/50 dark:bg-gray-800/20 p-5 rounded-2xl border border-gray-100 dark:border-gray-800/60">
                        <div class="flex items-center gap-1.5 border-b border-gray-100 dark:border-gray-700 pb-2 mb-1">
                            <span class="w-1.5 h-3 bg-blue-600 rounded-sm"></span>
                            <h4 class="text-xs font-bold text-gray-700 dark:text-gray-300">تفاصيل الطلب القانوني الأساسي</h4>
                        </div>
                        
                        <div>
                            <label class="text-[11px] font-bold text-gray-400 dark:text-gray-500 block mb-1">موضوع الطلب / العنوان</label>
                            <p id="modal-consultation-title" class="font-bold text-xs text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700/50 shadow-inner"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-400 dark:text-gray-500 block mb-1">نوع العملية</label>
                                <span id="modal-consultation-type" class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 text-[10px] font-black rounded-lg border border-blue-100 dark:border-blue-900 inline-block"></span>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-400 dark:text-gray-500 block mb-1">تصنيف العملية</label>
                                <span id="modal-consultation-class" class="px-2.5 py-1 bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 text-[10px] font-black rounded-lg border border-purple-100 dark:border-purple-900 inline-block"></span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="text-[11px] font-bold text-gray-400 dark:text-gray-500 block mb-1">شرح ومضمون الطلب</label>
                            <textarea id="modal-consultation-desc" readonly class="w-full bg-white dark:bg-gray-800 text-xs font-medium text-gray-700 dark:text-gray-300 leading-relaxed text-justify p-3 rounded-xl border border-gray-100 dark:border-gray-700/50 outline-none resize-none overflow-y-auto min-h-[140px] shadow-xs focus:outline-none"></textarea>
                        </div>

                        {{-- قسم المستندات والمرفقات المرفوعة من الموظف الداخلي --}}
                        <div id="modal-request-docs-container" class="space-y-1.5 pt-1 hidden">
                            <label class="text-[11px] font-bold text-gray-400 dark:text-gray-500 block mb-1">المستندات والمرفقات الواردة بالطلب الأصلي:</label>
                            <div id="modal-incoming-attachments" class="text-xs font-semibold flex flex-col gap-2"></div>
                        </div>
                    </div>

                    {{-- 🔹 القسم الأيسر: محرر صياغة الرد واللوائح مع قسم المرفقات المطور كلياً 🔹 --}}
                    <div class="space-y-4 bg-white dark:bg-gray-800/40 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/50">
                        <div class="flex items-center gap-1.5 border-b border-gray-100 dark:border-gray-700 pb-2 mb-1">
                            <span class="w-1.5 h-3 bg-emerald-600 rounded-sm"></span>
                            <h4 class="text-xs font-bold text-emerald-600">تدوين وصياغة الرد القانوني واللوائح</h4>
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-bold text-emerald-600 dark:text-emerald-400 mb-1">صيغة الرد والإفادة الصادرة: <span class="text-red-500">*</span></label>
                            <textarea required id="legal_response" name="legal_response" rows="5" placeholder="اكتب رد ومذكرة الإدارة القانونية بشكل مفصل ودقيق هنا..." 
                                class="w-full p-3 border border-emerald-100 dark:border-emerald-900/40 bg-emerald-50/20 dark:bg-emerald-950/10 text-xs font-medium rounded-xl outline-none text-gray-700 dark:text-gray-300 text-justify leading-relaxed min-h-[140px] resize-none overflow-y-auto shadow-xs"></textarea>
                        </div>

                        {{-- قسم المستندات والمرفقات الداعمة المطور كلياً --}}
                        <div class="space-y-2">
                            <div class="flex justify-between items-center w-full">
                                <label class="text-[11px] font-bold text-gray-400 dark:text-gray-500 block mb-1">المستندات والملفات المرفقة مع الرد (اختياري)</label>
                                <span id="file-counter-badge" class="px-2 py-0.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 text-[10px] font-bold rounded-md border border-blue-100 dark:border-blue-900/60 font-mono">0 / 3 ملفات</span>
                            </div>
                            
                            {{-- المربع المقطع الكبير الحاوي للكروت وزر الإضافة --}}
                            <div class="w-full p-4 border-2 border-gray-200 dark:border-gray-700 border-dashed rounded-2xl bg-gray-50/30 dark:bg-gray-700/10 flex flex-col gap-3">
                                
                                {{-- حاوية قائمة الملفات المختارة --}}
                                <div id="selected-files-list" class="flex flex-col gap-3"></div>
                                
                                {{-- الزر التفاعلي لإضافة مستند --}}
                                <button type="button" onclick="triggerFileInput()" class="w-full py-3 flex flex-col items-center justify-center gap-1 text-blue-600 dark:text-blue-400 hover:text-blue-700 transition cursor-pointer text-xs font-bold">
                                    <div class="w-5 h-5 bg-blue-600 text-white rounded-full flex items-center justify-center shadow-sm">
                                        <i class="fas fa-plus text-[10px]"></i>
                                    </div>
                                    <span class="mt-1">اضغط هنا لإضافة مستند إضافي للمعاملة</span>
                                </button>
                                <input type="file" name="employee_attachments[]" id="employee_attachment" onchange="handleFileSelection(this)" class="hidden" multiple />
                            </div>
                        </div>
                    </div>

                </div>

                {{-- فوتر المودال الثابت الموحد --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/40 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 flex-shrink-0">
                    <button type="button" onclick="closeResponseModal()" class="px-4 py-2 rounded-xl text-xs font-bold bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition cursor-pointer shadow-xs">
                        إلغاء
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl text-xs font-bold shadow-sm transition active:scale-[0.98] cursor-pointer">
                        <i class="fas fa-paper-plane text-[9px]"></i> رفع لطلب الاعتماد من المدير
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // دالة الفلترة الفورية الحية الموحدة لجدول الموظف القانوني بعد دعم القضايا بالملي
        function applyEmployeeFilters() {
            const searchVal = document.getElementById('search-title').value.toLowerCase().trim();
            const categoryVal = document.getElementById('filter-category').value;
            const dateFrom = document.getElementById('filter-date-from').value;
            const dateTo = document.getElementById('filter-date-to').value;

            const rows = document.querySelectorAll('.employee-task-row');
            const totalBadge = document.getElementById('total-badge');
            let matchCount = 0;

            rows.forEach(row => {
                const rTitle = row.getAttribute('data-title').toLowerCase();
                const rCategory = row.getAttribute('data-category');
                const rDate = row.getAttribute('data-date');

                const matchSearch = rTitle.includes(searchVal);
                const matchCategory = (categoryVal === 'all' || rCategory === categoryVal);
                
                let matchDate = true;
                if (dateFrom && rDate < dateFrom) matchDate = false;
                if (dateTo && rDate > dateTo) matchDate = false;

                if (matchSearch && matchCategory && matchDate) {
                    row.classList.remove('hidden');
                    matchCount++;
                } else {
                    row.classList.add('hidden');
                }
            });

            totalBadge.innerText = `نتائج التصفية: ${matchCount} معاملة`;

            const emptyRow = document.getElementById('employee-filter-empty');
            if (rows.length > 0) {
                if (matchCount === 0) {
                    emptyRow.classList.remove('hidden');
                } else {
                    emptyRow.classList.add('hidden');
                }
            }
        }

        let selectedFilesArray = [];

        function triggerFileInput() {
            document.getElementById('employee_attachment').click();
        }

        function handleFileSelection(input) {
            if (input.files && input.files.length > 0) {
                const totalIncoming = selectedFilesArray.length + input.files.length;
                if (totalIncoming > 3) {
                    alert('عذراً، أقصى عدد ملفات مسموح برفعها مع الرد هو 3 ملفات فقط.');
                    input.value = '';
                    return;
                }

                for (let i = 0; i < input.files.length; i++) {
                    selectedFilesArray.push(input.files[i]);
                }
                renderFilesContainer();
            }
        }

        function renderFilesContainer() {
            const list = document.getElementById('selected-files-list');
            const badge = document.getElementById('file-counter-badge');
            list.innerHTML = '';

            badge.innerText = `${selectedFilesArray.length} / 3 ملفات`;

            selectedFilesArray.forEach((file, index) => {
                const card = document.createElement('div');
                card.className = "flex items-center justify-between bg-white dark:bg-gray-800 px-4 py-3 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm w-full animate-fadeIn";
                
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);

                card.innerHTML = `
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="removeSelectedFile(${index})" class="text-gray-400 hover:text-red-500 transition cursor-pointer p-1">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                        <span class="px-2.5 py-0.5 bg-green-50 dark:bg-green-950/40 text-green-600 dark:text-green-400 text-[10px] font-bold rounded-md border border-green-100 dark:border-green-900/40">جاهز للرفع</span>
                    </div>
                    <div class="flex items-center gap-3 text-right">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate max-w-[240px] font-mono" dir="ltr">${file.name}</span>
                            <span class="text-[10px] text-gray-400 font-bold font-mono mt-0.5 text-left">MB ${fileSizeMB}</span>
                        </div>
                        <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/50 flex items-center justify-center text-blue-600 dark:text-blue-400 border border-blue-100/50 dark:border-blue-900/40 flex-shrink-0">
                            <i class="fas fa-file-alt text-xs"></i>
                        </div>
                    </div>
                `;
                list.appendChild(card);
            });

            updateInputFilesObject();
        }

        function removeSelectedFile(index) {
            selectedFilesArray.splice(index, 1);
            renderFilesContainer();
        }

        function updateInputFilesObject() {
            const input = document.getElementById('employee_attachment');
            const dataTransfer = new DataTransfer();
            selectedFilesArray.forEach(file => dataTransfer.items.add(file));
            input.files = dataTransfer.files;
        }

        // 🌟 تحديث الدالة لتستقبل نوع وتصنيف العملية بدقة وعرضها في الخانات المستقلة 🌟
        function openResponseModal(id, title, type, classification, description, incomingAttachmentsJson) {
            document.getElementById('modal-consultation-title').innerText = title;
            document.getElementById('modal-consultation-type').innerText = type;
            document.getElementById('modal-consultation-class').innerText = classification;
            
            const descTextarea = document.getElementById('modal-consultation-desc');
            const responseTextarea = document.getElementById('legal_response');
            
            descTextarea.value = description ? description : 'لا يوجد تفاصيل مكتوبة بالطلب.';
            
            document.getElementById('modal-response-form').action = `/consultations/${id}/reply`;
            
            // معالجة وإدراج مرفقات الطلب الأصلي على اليمين بالملي كالسجل
            const docContainer = document.getElementById('modal-request-docs-container');
            const filesList = document.getElementById('modal-incoming-attachments');
            filesList.innerHTML = '';

            if (incomingAttachmentsJson && incomingAttachmentsJson.trim() !== '') {
                try {
                    const filesArray = JSON.parse(incomingAttachmentsJson);
                    if (Array.isArray(filesArray) && filesArray.length > 0) {
                        filesArray.forEach(file => {
                            const fileLink = document.createElement('a');
                            fileLink.href = `/storage/${file.path}`;
                            fileLink.target = '_blank';
                            fileLink.className = "inline-flex items-center gap-2 bg-white dark:bg-gray-800 hover:bg-blue-50/50 dark:hover:bg-blue-950/20 border dark:border-gray-700 px-3 py-2 rounded-xl text-[11px] font-semibold text-gray-700 dark:text-gray-300 w-full truncate transition shadow-xs";
                            fileLink.innerHTML = `<i class="fas fa-paperclip text-blue-500 text-xs"></i> <span class="truncate text-blue-600 dark:text-blue-400 hover:underline">${file.name}</span>`;
                            filesList.appendChild(fileLink);
                        });
                        docContainer.classList.remove('hidden');
                    } else {
                        docContainer.classList.add('hidden');
                    }
                } catch(e) {
                    docContainer.classList.add('hidden');
                }
            } else {
                docContainer.classList.add('hidden');
            }

            document.getElementById('response-modal').classList.remove('hidden');
            
            selectedFilesArray = [];
            renderFilesContainer();
            
            setTimeout(() => {
                adjustTextareaHeight(descTextarea);
                adjustTextareaHeight(responseTextarea);
            }, 50);

            responseTextarea.addEventListener('input', function() {
                adjustTextareaHeight(this);
            });
        }

        function adjustTextareaHeight(el) {
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        }

        // إغلاق وتصفير حقول المرفقات
        function closeResponseModal() {
            document.getElementById('response-modal').classList.add('hidden');
            document.getElementById('modal-response-form').reset();
            selectedFilesArray = [];
            renderFilesContainer();
            document.getElementById('modal-request-docs-container').classList.add('hidden');
        }

        window.addEventListener('click', function(event) {
            const responseModal = document.getElementById('response-modal');
            if (event.target === responseModal) {
                closeResponseModal();
            }
        });
    </script>
@endpush