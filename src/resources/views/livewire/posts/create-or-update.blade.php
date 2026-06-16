<div
    x-data="tiptapEditor()"
    class="min-h-screen bg-gray-50"
>
    {{-- ── Header ── --}}
    <div class="sticky top-0 z-30 border-b border-gray-200 bg-white shadow-sm">
        <div class="mx-auto flex max-w-[1400px] items-center justify-between px-6 py-3">

            {{-- عنوان صفحه --}}
            <div class="flex items-center gap-3">
                <a href=""
                   class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-base font-semibold text-gray-900">ایجاد پست جدید</h1>
                    <p class="text-xs text-gray-500">پیش‌نویس — ذخیره نشده</p>
                </div>
            </div>

            {{-- دکمه‌های عملیات --}}
            <div class="flex items-center gap-2">
                <button
                    wire:click="save('draft')"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 disabled:opacity-60"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    ذخیره پیش‌نویس
                </button>

                <button
                    wire:click="save('published')"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 disabled:opacity-60"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    انتشار
                </button>
            </div>
        </div>
    </div>

    {{-- ── Body ── --}}
    <div class="mx-auto max-w-[1400px] px-6 py-8">
        <div class="flex gap-6">

            {{-- ── ستون اصلی ── --}}
            <div class="min-w-0 flex-1 space-y-5">

                {{-- Flash Message --}}
                @if (session('success'))
                    <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                        <svg class="h-5 w-5 flex-shrink-0 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- عنوان --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <input
                        wire:model.live.debounce.400ms="title"
                        type="text"
                        placeholder="عنوان پست را اینجا بنویسید…"
                        class="w-full border-0 bg-transparent text-2xl font-bold text-gray-900 placeholder-gray-300 focus:outline-none focus:ring-0"
                        dir="rtl"
                    />
                    @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    {{-- اسلاگ --}}
                    <div class="mt-3 flex items-center gap-2 border-t border-gray-100 pt-3">
                        <span class="text-xs text-gray-400">اسلاگ:</span>
                        <span class="text-xs text-gray-400">/blog/</span>
                        <input
                            wire:model.lazy="slug"
                            type="text"
                            class="flex-1 rounded-md border-0 bg-gray-50 px-2 py-1 text-xs text-gray-600 focus:outline-none focus:ring-1 focus:ring-indigo-400"
                            dir="ltr"
                        />
                        @error('slug')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ── ادیتور TipTap ── --}}
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                    {{-- نوار ابزار --}}
                    <div class="flex flex-wrap items-center gap-0.5 border-b border-gray-200 bg-gray-50 px-3 py-2"
                         id="editor-toolbar">

                        {{-- گروه: هدینگ‌ها --}}
                        <div class="flex items-center gap-0.5 border-r border-gray-200 pr-2 mr-1">
                            <button @click="setHeading(1)" :class="isActive('heading', {level:1}) ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded px-2 py-1 text-xs font-bold transition">H1</button>
                            <button @click="setHeading(2)" :class="isActive('heading', {level:2}) ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded px-2 py-1 text-xs font-bold transition">H2</button>
                            <button @click="setHeading(3)" :class="isActive('heading', {level:3}) ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded px-2 py-1 text-xs font-bold transition">H3</button>
                        </div>

                        {{-- گروه: فرمت متن --}}
                        <div class="flex items-center gap-0.5 border-r border-gray-200 pr-2 mr-1">
                            <button @click="editor.chain().focus().toggleBold().run()"
                                    :class="isActive('bold') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded p-1.5 transition" title="Bold">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/>
                                </svg>
                            </button>
                            <button @click="editor.chain().focus().toggleItalic().run()"
                                    :class="isActive('italic') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded p-1.5 transition" title="Italic">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M10 4v3h2.21l-3.42 10H6v3h8v-3h-2.21l3.42-10H18V4z"/>
                                </svg>
                            </button>
                            <button @click="editor.chain().focus().toggleUnderline().run()"
                                    :class="isActive('underline') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded p-1.5 transition" title="Underline">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 17c3.31 0 6-2.69 6-6V3h-2.5v8c0 1.93-1.57 3.5-3.5 3.5S8.5 12.93 8.5 11V3H6v8c0 3.31 2.69 6 6 6zm-7 2v2h14v-2H5z"/>
                                </svg>
                            </button>
                            <button @click="editor.chain().focus().toggleStrike().run()"
                                    :class="isActive('strike') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded p-1.5 transition" title="Strikethrough">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M6.85 7.08C6.85 4.37 9.45 3 12.24 3c1.64 0 3 .49 3.9 1.28.77.65 1.46 1.73 1.46 3.24h-3.01c0-.31-.05-.59-.15-.85-.29-.86-1.2-1.28-2.25-1.28-1.86 0-2.34.87-2.34 1.6 0 .45.21.85.67 1.13.09.06.27.15.51.26H6.98c-.08-.17-.13-.36-.13-.3zM21 12v-2H3v2h9.62c1.15.45 2.09.9 2.09 1.75 0 1-.93 1.5-2.26 1.5-1.45 0-2.already-1.5-2.47-2.25H7.01c0 1.42.47 2.51 1.35 3.25.93.75 2.29 1.25 3.87 1.25 3.21 0 5.51-1.87 5.51-4.7 0-.04 0-.07-.01-.1H21z"/>
                                </svg>
                            </button>
                            <button @click="editor.chain().focus().toggleCode().run()"
                                    :class="isActive('code') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded p-1.5 transition" title="Inline Code">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                </svg>
                            </button>
                        </div>

                        {{-- گروه: لیست‌ها --}}
                        <div class="flex items-center gap-0.5 border-r border-gray-200 pr-2 mr-1">
                            <button @click="editor.chain().focus().toggleBulletList().run()"
                                    :class="isActive('bulletList') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded p-1.5 transition" title="Bullet List">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            <button @click="editor.chain().focus().toggleOrderedList().run()"
                                    :class="isActive('orderedList') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded p-1.5 transition" title="Numbered List">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                            </button>
                            <button @click="editor.chain().focus().toggleBlockquote().run()"
                                    :class="isActive('blockquote') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded p-1.5 transition" title="Quote">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z"/>
                                </svg>
                            </button>
                            <button @click="editor.chain().focus().toggleCodeBlock().run()"
                                    :class="isActive('codeBlock') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded p-1.5 transition" title="Code Block">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>

                        {{-- گروه: لینک و تصویر --}}
                        <div class="flex items-center gap-0.5 border-r border-gray-200 pr-2 mr-1">
                            <button @click="setLink()"
                                    :class="isActive('link') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded p-1.5 transition" title="Link">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                            </button>
                            <button @click="addImage()"
                                    class="rounded p-1.5 text-gray-600 transition hover:bg-gray-200" title="Image">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>

                        {{-- گروه: align --}}
                        <div class="flex items-center gap-0.5 border-r border-gray-200 pr-2 mr-1">
                            <button @click="editor.chain().focus().setTextAlign('right').run()"
                                    :class="isActive({textAlign:'right'}) ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded p-1.5 transition" title="Align Right">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h12M3 6h18M3 18h18"/>
                                </svg>
                            </button>
                            <button @click="editor.chain().focus().setTextAlign('center').run()"
                                    :class="isActive({textAlign:'center'}) ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded p-1.5 transition" title="Center">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            <button @click="editor.chain().focus().setTextAlign('left').run()"
                                    :class="isActive({textAlign:'left'}) ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-200'"
                                    class="rounded p-1.5 transition" title="Align Left">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3M3 6h18M3 18h18"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Undo / Redo --}}
                        <div class="flex items-center gap-0.5">
                            <button @click="editor.chain().focus().undo().run()"
                                    class="rounded p-1.5 text-gray-500 transition hover:bg-gray-200" title="Undo">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                            </button>
                            <button @click="editor.chain().focus().redo().run()"
                                    class="rounded p-1.5 text-gray-500 transition hover:bg-gray-200" title="Redo">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- محتوای ادیتور --}}
                    <div
                        id="editor-content"
                        class="prose prose-lg prose-indigo max-w-none min-h-[500px] px-8 py-6 focus:outline-none"
                        dir="rtl"
                        style="font-family: 'Vazirmatn', sans-serif;"
                    ></div>

                    @error('content')
                    <p class="border-t border-red-100 bg-red-50 px-4 py-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- خلاصه مطلب --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <label class="mb-3 flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/>
                        </svg>
                        خلاصه مطلب
                    </label>
                    <textarea
                        wire:model="excerpt"
                        rows="3"
                        placeholder="خلاصه‌ای از پست برای نمایش در لیست بلاگ (اختیاری)…"
                        class="w-full resize-none rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 placeholder-gray-400 transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                        dir="rtl"
                    ></textarea>
                    <p class="mt-1 text-right text-xs text-gray-400">
                        <span x-text="($wire.excerpt ?? '').length"></span>/500
                    </p>
                </div>

                {{-- ── SEO ── --}}
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <button
                        @click="$wire.set('seoOpen', !$wire.seoOpen)"
                        class="flex w-full items-center justify-between px-6 py-4 text-left"
                    >
                        <span class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            تنظیمات SEO
                        </span>
                        <svg class="h-4 w-4 text-gray-400 transition-transform"
                             :class="$wire.seoOpen ? 'rotate-180' : ''"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="$wire.seoOpen" x-collapse class="border-t border-gray-100 px-6 pb-6 pt-4 space-y-4">
                        {{-- Meta Title --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-gray-600">عنوان متا (Meta Title)</label>
                            <input
                                wire:model="meta_title"
                                type="text"
                                placeholder="عنوان برای موتورهای جستجو…"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                                dir="rtl"
                                maxlength="60"
                            />
                            <div class="mt-1 flex justify-between">
                                <span class="text-xs text-gray-400">بهترین طول: ۵۰–۶۰ کاراکتر</span>
                                <span class="text-xs" :class="($wire.meta_title ?? '').length > 60 ? 'text-red-500' : 'text-gray-400'">
                                    <span x-text="($wire.meta_title ?? '').length"></span>/60
                                </span>
                            </div>
                        </div>

                        {{-- Meta Description --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-gray-600">توضیحات متا</label>
                            <textarea
                                wire:model="meta_description"
                                rows="3"
                                placeholder="توضیحات کوتاه برای نمایش در نتایج جستجو…"
                                class="w-full resize-none rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                                dir="rtl"
                                maxlength="160"
                            ></textarea>
                            <div class="mt-1 flex justify-between">
                                <span class="text-xs text-gray-400">بهترین طول: ۱۵۰–۱۶۰ کاراکتر</span>
                                <span class="text-xs" :class="($wire.meta_description ?? '').length > 160 ? 'text-red-500' : 'text-gray-400'">
                                    <span x-text="($wire.meta_description ?? '').length"></span>/160
                                </span>
                            </div>
                        </div>

                        {{-- پیش‌نمایش Google --}}
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <p class="mb-2 text-xs font-medium text-gray-500">پیش‌نمایش در گوگل</p>
                            <p class="text-base font-medium text-blue-700 hover:underline" x-text="$wire.meta_title || $wire.title || 'عنوان پست'"></p>
                            <p class="text-xs text-green-700">yoursite.com/blog/<span x-text="$wire.slug || 'slug'"></span></p>
                            <p class="mt-1 text-xs text-gray-600" x-text="$wire.meta_description || $wire.excerpt || 'توضیحات متا در اینجا نمایش داده می‌شود…'"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── ستون کنار ── --}}
            <div class="w-72 flex-shrink-0 space-y-4">

                {{-- وضعیت انتشار --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-gray-800">
                        <svg class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        وضعیت انتشار
                    </h3>

                    <div class="space-y-2">
                        @foreach (['draft' => ['پیش‌نویس', 'text-gray-500', 'bg-gray-100'], 'published' => ['منتشر شده', 'text-green-600', 'bg-green-100'], 'scheduled' => ['زمان‌بندی شده', 'text-orange-600', 'bg-orange-100']] as $val => [$label, $textColor, $bgColor])
                            <label class="flex cursor-pointer items-center justify-between rounded-lg border p-3 transition hover:bg-gray-50"
                                   :class="'{{ $val }}' === $wire.status ? 'border-indigo-300 bg-indigo-50' : 'border-gray-200'">
                                <div class="flex items-center gap-2">
                                    <input wire:model="status" type="radio" value="{{ $val }}" class="text-indigo-600"/>
                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                </div>
                                <span class="rounded-full {{ $bgColor }} {{ $textColor }} px-2 py-0.5 text-xs font-medium">{{ $val }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <button wire:click="save('draft')"
                                class="rounded-xl border border-gray-300 bg-white py-2 text-xs font-medium text-gray-600 transition hover:bg-gray-50">
                            ذخیره پیش‌نویس
                        </button>
                        <button wire:click="save('published')"
                                class="rounded-xl bg-indigo-600 py-2 text-xs font-medium text-white transition hover:bg-indigo-700">
                            انتشار
                        </button>
                    </div>
                </div>

                {{-- تصویر شاخص --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-800">
                        <svg class="h-4 w-4 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        تصویر شاخص
                    </h3>

                    @if ($featured_image_preview)
                        <div class="relative mb-3 overflow-hidden rounded-xl">
                            <img src="{{ $featured_image_preview }}" class="h-40 w-full object-cover"/>
                            <button wire:click="$set('featured_image', null); $set('featured_image_preview', null)"
                                    class="absolute right-2 top-2 rounded-full bg-red-500 p-1 text-white shadow">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @else
                        <label class="flex h-36 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 text-gray-400 transition hover:border-indigo-300 hover:text-indigo-400">
                            <svg class="mb-2 h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <span class="text-xs">تصویر را اینجا رها کنید</span>
                            <span class="text-xs text-gray-300">یا کلیک کنید</span>
                            <input wire:model="featured_image" type="file" accept="image/*" class="hidden"/>
                        </label>
                    @endif

                    @error('featured_image')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- دسته‌بندی --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-800">
                        <svg class="h-4 w-4 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        دسته‌بندی
                    </h3>
                    <select
                        wire:model="category"
                        class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm text-gray-700 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                        dir="rtl"
                    >
                        <option value="">انتخاب دسته‌بندی…</option>
                        <option value="tech">تکنولوژی</option>
                        <option value="design">طراحی</option>
                        <option value="business">کسب‌وکار</option>
                        <option value="lifestyle">سبک زندگی</option>
                        <option value="other">سایر</option>
                    </select>
                </div>

                {{-- برچسب‌ها --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-800">
                        <svg class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                        </svg>
                        برچسب‌ها
                    </h3>
                    <input
                        wire:model="tags"
                        type="text"
                        placeholder="laravel, php, web…"
                        class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                        dir="ltr"
                    />
                    <p class="mt-1.5 text-xs text-gray-400">با کاما از هم جدا کنید</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Alpine / TipTap ── --}}
{{--    <script>--}}
{{--        function tiptapEditor() {--}}
{{--            return {--}}
{{--                editor: null,--}}

{{--                init() {--}}
{{--                    const { Editor } = window.TiptapCore;--}}
{{--                    const {--}}
{{--                        StarterKit,--}}
{{--                        Underline,--}}
{{--                        Link,--}}
{{--                        Image,--}}
{{--                        TextAlign,--}}
{{--                        Placeholder,--}}
{{--                        CharacterCount--}}
{{--                    } = window.TiptapExtensions;--}}

{{--                    this.editor = new Editor({--}}
{{--                        element: document.getElementById('editor-content'),--}}
{{--                        extensions: [--}}
{{--                            StarterKit,--}}
{{--                            Underline,--}}
{{--                            Link.configure({ openOnClick: false }),--}}
{{--                            Image,--}}
{{--                            TextAlign.configure({ types: ['heading', 'paragraph'] }),--}}
{{--                            Placeholder.configure({ placeholder: 'محتوای پست را اینجا بنویسید…' }),--}}
{{--                        ],--}}
{{--                        content: '',--}}
{{--                        editorProps: {--}}
{{--                            attributes: {--}}
{{--                                class: 'focus:outline-none min-h-[460px]',--}}
{{--                                dir: 'rtl',--}}
{{--                            },--}}
{{--                        },--}}
{{--                        onUpdate: ({ editor }) => {--}}
{{--                            const html = editor.getHTML();--}}
{{--                        @this.updateContent(html);--}}
{{--                        },--}}
{{--                    });--}}
{{--                },--}}

{{--                isActive(name, attrs = {}) {--}}
{{--                    return this.editor?.isActive(name, attrs) ?? false;--}}
{{--                },--}}

{{--                setHeading(level) {--}}
{{--                    this.editor.chain().focus().toggleHeading({ level }).run();--}}
{{--                },--}}

{{--                setLink() {--}}
{{--                    const prev = this.editor.getAttributes('link').href;--}}
{{--                    const url = window.prompt('آدرس لینک را وارد کنید:', prev || 'https://');--}}
{{--                    if (url === null) return;--}}
{{--                    if (url === '') {--}}
{{--                        this.editor.chain().focus().unsetLink().run();--}}
{{--                        return;--}}
{{--                    }--}}
{{--                    this.editor.chain().focus().setLink({ href: url }).run();--}}
{{--                },--}}

{{--                addImage() {--}}
{{--                    const url = window.prompt('آدرس تصویر را وارد کنید:');--}}
{{--                    if (url) this.editor.chain().focus().setImage({ src: url }).run();--}}
{{--                },--}}
{{--            };--}}
{{--        }--}}
{{--    </script>--}}
</div>
