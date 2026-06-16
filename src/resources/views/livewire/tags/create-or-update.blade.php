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






        </x-admin.common.component-card>

    </form>
</div>
