@php
    $isFa = app()->getLocale() === 'fa';
@endphp

<div class="rounded-2xl border border-gray-200/80 bg-white p-5 shadow-xs transition-colors dark:border-gray-800 dark:bg-gray-900/80"
     x-data="{ activeTab: 'all', showAddBox: false }"
     dir="{{ $isFa ? 'rtl' : 'ltr' }}">

    <h3 class="mb-3.5 flex items-center justify-between text-sm font-semibold text-gray-800 dark:text-gray-100">
        <span class="flex items-center gap-2">
            <svg class="h-4 w-4 text-amber-500 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            {{ __("blog::messages.Categories") ?? (__($isFa ? "دسته‌بندی‌های مقالات" : "Article Categories")) }}
        </span>
    </h3>

    {{-- تب‌ها --}}
    <div class="flex border-b border-gray-200 text-xs dark:border-gray-700 mb-3">
        <button type="button"
                @click="activeTab = 'all'"
                :class="activeTab === 'all' ? 'border-b-2 border-indigo-600 text-indigo-600 font-medium dark:text-indigo-400 dark:border-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                class="pb-2 px-3 transition-colors">
            {{ $isFa ? "همه دسته‌ها" : "All Categories" }}
        </button>
    </div>

    {{-- لیست چک‌باکس‌ها (درختی) --}}
    <div class="max-h-56 overflow-y-auto space-y-1.5 {{ $isFa ? 'pl-1' : 'pr-1' }} custom-scrollbar border border-gray-100 dark:border-gray-800 rounded-xl p-2 bg-gray-50/50 dark:bg-gray-950/50">
        @forelse($allCategories as $cat)
            @include('blog::livewire.categories.category-item', ['category' => $cat, 'level' => 0])
        @empty
            <p class="text-xs text-gray-400 text-center py-3">{{ $isFa ? "دسته‌بندی‌ای یافت نشد." : "No categories found." }}</p>
        @endforelse
    </div>

    {{-- دکمه افزودن دسته جدید --}}
    <div class="mt-3">
        <button
            type="button"
            @click="showAddBox = !showAddBox"
            class="text-xs font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
        >
            {{ $isFa ? "+ افزودن دسته جدید" : "+ Add new category" }}
        </button>

        {{-- بخش کشویی افزودن دسته جدید + والد --}}
        <div x-show="showAddBox" x-transition class="mt-3 space-y-2.5 pt-3 border-t border-gray-100 dark:border-gray-800">
            <div>
                <input
                    type="text"
                    wire:model="newCategoryName"
                    placeholder="{{ $isFa ? 'نام دسته جدید...' : 'New category name...' }}"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:placeholder-gray-500"
                >
                @error('newCategoryName')
                <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <select
                    wire:model="newCategoryParentId"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                >
                    <option value="">— {{ $isFa ? 'دسته اصلی (بدون والد)' : 'Parent Category (None)' }} —</option>
                    @foreach(\Apachish\Blog\App\Models\Category::where('project_id', $project->id)->where('locale', $locale)->get() as $pCat)
                        <option value="{{ $pCat->id }}">{{ $pCat->name }}</option>
                    @endforeach
                </select>
            </div>

            <button
                type="button"
                wire:click="addNewCategory"
                class="w-full rounded-xl bg-gray-900 text-white dark:bg-indigo-600 dark:hover:bg-indigo-700 py-2 text-xs font-medium transition hover:bg-gray-800"
            >
                {{ $isFa ? "افزودن دسته جدید" : "Add category" }}
            </button>
        </div>
    </div>
</div>
