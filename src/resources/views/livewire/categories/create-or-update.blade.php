<div class="grid grid-cols-1 gap-12 xl:grid-cols-1">
    <form class="space-y-12" wire:submit="save">
        <x-admin.common.component-card title="{{$title}}" button_submit="{{$title_button}}">
            <!-- Elements -->

            <div class="flex flex-wrap items-center gap-8">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{__("blog::messages.Language")}}
                </label>

                <!-- گزینه انگلیسی -->
                <div>
                    <label for="locale_en" class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                        <div class="relative flex items-center ml-2">
                            <!-- استفاده از radio و اضافه کردن wire:model و value -->
                            <input type="radio" id="locale_en" name="locale" value="en" wire:model="category.locale" class="peer sr-only" />

                            <!-- استایل‌دهی با کلاس‌های peer-checked -->
                            <div class="mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px] border-gray-300 bg-transparent transition-all hover:border-brand-500 peer-checked:border-brand-500 peer-checked:bg-brand-500 dark:border-gray-700 dark:hover:border-brand-500">
                                <span class="h-2 w-2 rounded-full bg-transparent peer-checked:bg-white"></span>
                            </div>
                        </div>
                        {{__("blog::messages.EN")}}
                    </label>
                </div>

                <!-- گزینه فارسی -->
                <div>
                    <label for="locale_fa" class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                        <div class="relative flex items-center ml-2">
                            <input type="radio" id="locale_fa" name="locale" value="fa" wire:model="category.locale" class="peer sr-only" />

                            <div class="mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px] border-gray-300 bg-transparent transition-all hover:border-brand-500 peer-checked:border-brand-500 peer-checked:bg-brand-500 dark:border-gray-700 dark:hover:border-brand-500">
                                <span class="h-2 w-2 rounded-full bg-transparent peer-checked:bg-white"></span>
                            </div>
                        </div>
                        {{__("blog::messages.FA")}}
                    </label>
                </div>

                @error('category.locale')
                <p class="text-theme-xs text-error-500">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{__("blog::messages.Name Catetgory")}}
                </label>
                <input type="text" wire:model="category.name"
                       class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"/>
                @error('category.name')
                <p class="text-theme-xs text-error-500">{{ $message }}</p>
                @else
                    @error('category.slug')
                    <p class="text-theme-xs text-error-500">{{ $message }}</p>
                    @enderror
                    @enderror
                @error('category.project_id')
                <p class="text-theme-xs text-error-500">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Elements -->
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{ __("blog::messages.Parent Category") }}
                </label>

                <div
                    class="relative z-20 bg-transparent"
                >
                    <select
                        wire:model="category.parent_id"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
            dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border
            border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm
            placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
            dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
            dark:placeholder:text-white/30"

                        :class="status ? 'text-gray-800 dark:text-white/90' : 'text-gray-400'"
                    >
                        <option value="">
                            {{ __("blog::messages.Choose a Parent") }}
                        </option>


                        @foreach($categories as  $category)
                            <option value="{{ data_get($category,'id') }}">{{ data_get($category,'name') }}</option>
                        @endforeach

                    </select>

                    <span
                        class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
            <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                      stroke-width="1.5"
                      stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
        </span>

                </div>
                @error('category.parent_id')
                <p class="text-theme-xs text-error-500">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Elements -->
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{__("Description")}}
                </label>
                <textarea
                    placeholder="{{__("Enter a description...")}}"
                    rows="6"
                    wire:model="category.description"
                    class="w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30

    {{-- کلاس‌های وضعیت عادی --}}
    @error('category.description')
        border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800
    @else
        border-gray-300 focus:border-primary-500 focus:ring-primary-500/10 dark:border-gray-700 dark:focus:border-primary-600
    @enderror"
                ></textarea>
                @error('category.description')
                <p class="text-theme-xs text-error-500">
                    {{ $message }}
                </p>
                @enderror
            </div>


            <!-- Elements -->
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{ __("Status") }}
                </label>

                <div
                    class="relative z-20 bg-transparent"
                >
                    <select
                        wire:model="category.status"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
            dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border
            border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm
            placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
            dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
            dark:placeholder:text-white/30"

                        :class="status ? 'text-gray-800 dark:text-white/90' : 'text-gray-400'"
                    >
                        <option value="">
                            {{ __("Select status") }}
                        </option>

                        <option value="1">
                            {{ __("Active") }}
                        </option>

                        <option value="0">
                            {{ __("Disabled") }}
                        </option>
                    </select>

                    <span
                        class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
            <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                      stroke-width="1.5"
                      stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
        </span>

                </div>
                @error('category.status')
                <p class="text-theme-xs text-error-500">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{__("blog::messages.Priority")}}
                </label>
                <input type="number" wire:model="category.order"
                       class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"/>
                @error('category.order')
                <p class="text-theme-xs text-error-500">
                    {{ $message }}
                </p>
                @enderror
                @error('category.order')
                <p class="text-theme-xs text-error-500">
                    {{ $message }}
                </p>
                @enderror
            </div>





        </x-admin.common.component-card>

    </form>
</div>
