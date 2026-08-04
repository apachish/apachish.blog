<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    {{-- ── Header ── --}}
    <div class="sticky top-0 z-30 border-b border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="mx-auto flex max-w-[1400px] items-center justify-between px-6 py-3">

            {{-- عنوان صفحه --}}
            <div class="flex items-center gap-3">
                <a href=""
                   class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{__("blog::messages.Create a new post")}}</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">پیش‌نویس — ذخیره نشده</p>
                </div>
            </div>

            {{-- دکمه‌های عملیات --}}
            <div class="flex items-center gap-2">
                <button
                    wire:click="save('draft')"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 disabled:opacity-60 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    {{__("blog::messages.Save draft")}}
                </button>

                <button
                    wire:click="save('published')"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 disabled:opacity-60 dark:bg-indigo-500 dark:hover:bg-indigo-600 dark:focus:ring-offset-gray-800"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    {{__("blog::messages.Publication")}}
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
                    <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800 dark:border-green-800 dark:bg-green-900/30 dark:text-green-300">
                        <svg class="h-5 w-5 flex-shrink-0 text-green-500 dark:text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 dark:bg-red-900/30 dark:border-red-800 dark:text-red-300">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- عنوان --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <input
                        wire:model.live.debounce.400ms="title"
                        type="text"
                        placeholder="{{__("blog::messages.Write the post title here…")}}"
                        class="w-full border-0 bg-transparent text-2xl font-bold text-gray-900 placeholder-gray-300 focus:outline-none focus:ring-0 dark:text-gray-100 dark:placeholder-gray-600"
                    />
                    @error('title')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    {{-- اسلاگ --}}
                    <div class="mt-3 flex items-center gap-2 border-t border-gray-100 pt-3 dark:border-gray-700">
                        <span class="text-xs text-gray-400 dark:text-gray-500">{{__("blog::messages.Slug")}}:</span>
                        <span class="text-xs text-gray-400 dark:text-gray-500">/blog/</span>
                        <input
                            wire:model.lazy="slug"
                            type="text"
                            class="flex-1 rounded-md border-0 bg-gray-50 px-2 py-1 text-xs text-gray-600 focus:outline-none focus:ring-1 focus:ring-indigo-400 dark:bg-gray-900 dark:text-gray-300"
                        />
                        @error('slug')
                        <p class="text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <x-admin.form.form-elements.editor/>

                {{-- خلاصه مطلب --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <label class="mb-3 flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/>
                        </svg>
                        {{__("blog::messages.Summary")}}
                    </label>
                    <textarea
                        wire:model="excerpt"
                        rows="3"
                        placeholder="{{__("blog::messages.A summary of the post to display in the blog list (optional)…")}}"
                        class="w-full resize-none rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 placeholder-gray-400 transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 dark:placeholder-gray-500 dark:focus:ring-indigo-900/40"
                    ></textarea>
                    <p class="mt-1 text-right text-xs text-gray-400 dark:text-gray-500">
                        <span x-text="($wire.excerpt ?? '').length"></span>/500
                    </p>
                </div>

                {{-- ── SEO ── --}}
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800" x-data>
                    <button
                        @click="$wire.set('seoOpen', !$wire.seoOpen)"
                        class="flex w-full items-center justify-between px-6 py-4 text-left"
                    >
                        <span class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            <svg class="h-4 w-4 text-green-500 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            {{__("blog::messages.SEO settings")}}
                        </span>
                        <svg class="h-4 w-4 text-gray-400 transition-transform dark:text-gray-500"
                             :class="$wire.seoOpen ? 'rotate-180' : ''"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="$wire.seoOpen" x-transition class="border-t border-gray-100 px-6 pb-6 pt-4 space-y-4 dark:border-gray-700">
                        {{-- Meta Title --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">{{__("blog::messages.Meta Title")}}</label>
                            <input
                                wire:model="meta_title"
                                type="text"
                                placeholder="{{__("blog::messages.Meta Title")}}"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 dark:placeholder-gray-500 dark:focus:ring-indigo-900/40"
                                maxlength="60"
                            />
                        </div>

                        {{-- Meta Description --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">{{__("blog::messages.Meta description")}}</label>
                            <textarea
                                wire:model="meta_description"
                                rows="3"
                                placeholder="{{__("blog::messages.Short description to display in search results…")}}"
                                class="w-full resize-none rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 dark:placeholder-gray-500 dark:focus:ring-indigo-900/40"
                                maxlength="160"
                            ></textarea>
                        </div>

                        {{-- پیش‌نمایش Google --}}
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                            <p class="mb-2 text-xs font-medium text-gray-500 dark:text-gray-400">{{__("blog::messages.Preview on Google")}}</p>
                            <p class="text-base font-medium text-blue-700 hover:underline dark:text-blue-400" x-text="$wire.meta_title || $wire.title || '{{__("blog::messages.Post title")}}'"></p>
                            <p class="text-xs text-green-700 dark:text-green-400">yoursite.com/blog/<span x-text="$wire.slug || 'slug'"></span></p>
                            <p class="mt-1 text-xs text-gray-600 dark:text-gray-400" x-text="$wire.meta_description || $wire.excerpt || '{{__("blog::messages.Meta description is displayed here…")}}'"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── ستون کنار ── --}}
            <div class="w-72 flex-shrink-0 space-y-4">

                {{-- وضعیت انتشار --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800" x-data>
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                        <svg class="h-4 w-4 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{__("blog::messages.Release status")}}
                    </h3>

                    <div class="space-y-2">
                        @foreach ([
                            'draft'     => [__("blog::messages.Draft"),     'border-gray-300   dark:border-gray-600',   'bg-gray-50    dark:bg-gray-700/40',   'text-gray-600  dark:text-gray-300'],
                            'published' => [__("blog::messages.published"), 'border-green-300  dark:border-green-700',  'bg-green-50   dark:bg-green-900/30',  'text-green-700 dark:text-green-300'],
                            'scheduled' => [__("blog::messages.scheduled"), 'border-orange-300 dark:border-orange-700', 'bg-orange-50  dark:bg-orange-900/30', 'text-orange-700 dark:text-orange-300'],
                        ] as $val => [$label, $border, $bg, $text])
                            <label
                                class="flex cursor-pointer items-center gap-2 rounded-lg border p-3 transition {{ $border }} {{ $bg }}"
                                :class="'{{ $val }}' === $wire.status
                ? 'ring-2 ring-indigo-500 dark:ring-indigo-400'
                : 'hover:brightness-95 dark:hover:brightness-110'"
                            >
                                <input wire:model.live="status" type="radio" value="{{ $val }}" class="text-indigo-600"/>
                                <span class="text-sm font-medium {{ $text }}">{{ $label }}</span>
                            </label>
                        @endforeach

                        <div x-show="$wire.status === 'scheduled'" class="space-y-2 pt-1">
                            @php $isFa = app()->getLocale() === 'fa'; @endphp
                            <div class="grid grid-cols-2 gap-2">
                                @if($isFa)
                                    <div wire:ignore x-data="persianDatepicker(@this, 'published_at')">
                                        <input x-ref="input" type="text" wire:model="published_at" autocomplete="off"
                                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200">
                                    </div>
                                @else
                                    <div wire:ignore x-data="gregorianDatepicker(@this, 'published_at')">
                                        <input x-ref="input" type="text" wire:model="published_at" autocomplete="off" placeholder="YYYY-MM-DD"
                                               class="h-10 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200">
                                    </div>
                                @endif
                                <input type="time" wire:model="published_time" value="{{ now()->format('H:i') }}"
                                       class="h-10 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <button wire:click="save('draft')"
                                class="rounded-xl border border-gray-300 bg-white py-2 text-xs font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            {{__("blog::messages.Save draft")}}
                        </button>
                        <button wire:click="save('published')"
                                class="rounded-xl bg-indigo-600 py-2 text-xs font-medium text-white transition hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                            {{__("blog::messages.Publication")}}
                        </button>
                    </div>
                </div>

                {{-- زبان --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800" x-data>
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                        <svg class="h-4 w-4 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{__("blog::messages.Language")}}
                    </h3>

                    <div class="space-y-2">
                        @foreach (['en' => [__("blog::messages.EN"), 'text-yellow-300', 'bg-amber-100 dark:bg-amber-900/30'], 'fa' => [__("blog::messages.FA"), 'text-lime-600', 'bg-lime-100 dark:bg-lime-900/30']] as $val => [$label, $textColor, $bgColor])
                            <label class="flex cursor-pointer items-center justify-between rounded-lg border p-3 transition hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ $bgColor }}"
                                   :class="'{{ $val }}' === $wire.locale ? 'border-indigo-300 bg-indigo-50 dark:border-indigo-500 dark:bg-indigo-900/30' : 'border-gray-200 dark:border-gray-600'">
                                <div class="flex items-center gap-2">
                                    <input wire:model.live="locale" type="radio" value="{{ $val }}" class="text-indigo-600"/>
                                    <span class="text-sm text-gray-700 dark:text-gray-200">{{ $label }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- تصویر شاخص --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                        <svg class="h-4 w-4 text-pink-500 dark:text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{__("blog::messages.Featured image")}}
                    </h3>

                    @if ($featured_image_preview)
                        <div class="relative mb-3 overflow-hidden rounded-xl">
                            <img src="{{ data_get($post,'featured_image')?route("image.display",["path"=>$featured_image_preview]):$featured_image_preview }}" class="h-40 w-full object-cover"/>
                            <button wire:click="$set('featured_image', null); $set('featured_image_preview', null)"
                                    class="absolute right-2 top-2 rounded-full bg-red-500 p-1 text-white shadow">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @else
                        <label class="flex h-36 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 text-gray-400 transition hover:border-indigo-300 hover:text-indigo-400 dark:border-gray-600 dark:text-gray-500 dark:hover:border-indigo-500 dark:hover:text-indigo-400">
                            <svg class="mb-2 h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <span class="text-xs">{{__("blog::messages.Drop the image here.")}}</span>
                            <span class="text-xs text-gray-300 dark:text-gray-600">{{__("blog::messages.Or click")}}</span>
                            <input wire:model="featured_image" type="file" accept="image/*" class="hidden"/>
                        </label>
                    @endif

                    @error('featured_image')
                    <p class="mt-2 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- دسته‌بندی --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                        <svg class="h-4 w-4 text-yellow-500 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        دسته‌بندی
                    </h3>
                    <select
                        wire:model="category"
                        class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm text-gray-700 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200"
                    >
                        <option value="">انتخاب دسته‌بندی…</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>

                {{-- برچسب‌ها --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                        <svg class="h-4 w-4 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                        </svg>
                        برچسب‌ها
                    </h3>
                    <livewire:blog::tags.search-tag name="tags" :locale="$locale"/>

                    @if($post && $post->tags && $post->tags->count())
                        @foreach($post->tags as $tag)
                            <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                {{ data_get($tag,'name') }}
                            </span>
                        @endforeach
                    @endif
                    @if(sizeof($tags))
                        @foreach($tags as $tag)
                            <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                {{ $tag['name'] }}
                            </span>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
