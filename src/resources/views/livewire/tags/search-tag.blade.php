<div class="relative flex gap-2 items-center" data-dropdown id="category-search">

    <div class="relative flex-1">
        <input
            type="text"
            wire:model.live.debounce.150ms="search"
            wire:click="openClose"
            placeholder="جستجو..."
            class="w-full border px-3 py-2 rounded"
        >
        @if($dropdownOpen)
            <ul class="absolute bg-white border rounded w-full mt-1 shadow z-[9999]">
                @forelse($results as $item)
                    <li
                        wire:click="select({{ $item->id }})"
                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                    >
                        {{ $item->name }}
                    </li>
                @empty
                    <li class="px-4 py-2 text-gray-500">{{__("blog::messages.No results found.")}}</li>
                @endforelse
            </ul>
        @endif
    </div>

    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full whitespace-nowrap" wire:click="addTag">
        {{__("Add")}}
    </button>
</div>
