<div class="grid grid-cols-1 gap-12 xl:grid-cols-1">
    <form class="space-y-12" wire:submit="save">
        <x-admin.common.component-card title="{{$title}}" button_submit="{{$title_button}}">
            <!-- Elements -->
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{__("blog::messages.Name Tag")}}
                </label>
                <input type="text" wire:model="tag.name"
                       class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"/>
                @error('tag.name')
                <p class="text-theme-xs text-error-500">
                    {{ $message }}
                </p>
                @enderror
                @error('tag.slug')
                <p class="text-theme-xs text-error-500">
                    {{ $message }}
                </p>
                @enderror
                @error('tag.project_id')
                <p class="text-theme-xs text-error-500">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div class="flex flex-wrap items-center gap-8">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{__("blog::messages.Language")}}
                </label>

                <!-- گزینه انگلیسی -->
                <div>
                    <label for="locale_en" class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                        <div class="relative flex items-center ml-2">
                            <!-- استفاده از radio و اضافه کردن wire:model و value -->
                            <input type="radio" id="locale_en" name="locale" value="en" wire:model="tag.locale" class="peer sr-only" />

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
                            <input type="radio" id="locale_fa" name="locale" value="fa" wire:model="tag.locale" class="peer sr-only" />

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






        </x-admin.common.component-card>

    </form>
</div>
