<div class="relative flex gap-2 items-center" data-dropdown id="category-search" >

    <div class="relative flex-1">
        <input
            type="text"
            wire:model.live.debounce.150ms="search"
            wire:click="openClose"
            placeholder="{{__("blog::messages.Search")}}..."
            class="w-full border px-3 py-2 rounded bg-white dark:bg-neutral-800 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100 placeholder-neutral-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        @if($dropdownOpen)
            <ul class="absolute bg-white dark:bg-neutral-800 border dark:border-neutral-700 rounded w-full mt-1 shadow-lg z-[9999]">
                @forelse($results as $item)
                    <li
                        wire:click="select({{ $item->id }})"
                        class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-neutral-700 text-neutral-800 dark:text-neutral-200 cursor-pointer transition-colors"
                    >
                        {{ $item->name }}
                    </li>
                @empty
                    <li class="px-4 py-2 text-gray-500 dark:text-neutral-400">{{__("blog::messages.No results found.")}}</li>
                @endforelse
            </ul>
        @endif
    </div>

    <button type="button" class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-full whitespace-nowrap transition-colors" wire:click="addTag">
        {{__("Add")}}
    </button>
</div>
