@php
    $isFa = app()->getLocale() === 'fa';
@endphp

<div class="space-y-1.5" style="{{ $isFa ? 'padding-right:' . ($level * 16) . 'px' : 'padding-left:' . ($level * 16) . 'px' }}">
    <label class="flex items-center gap-2.5 text-sm text-gray-700 dark:text-gray-300 cursor-pointer hover:bg-gray-100/60 dark:hover:bg-gray-800/60 p-1.5 rounded-lg transition-colors">
        <input
            type="checkbox"
            wire:model="categories"
            value="{{ $category->id }}"
            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:checked:bg-indigo-500"
        >
        <span class="select-none flex items-center gap-1.5">
            @if($level > 0)
                <span class="text-gray-400 dark:text-gray-600">—</span>
            @endif
            {{ $category->name }}
        </span>
    </label>

    @if($category->childrenRecursive && $category->childrenRecursive->count())
        @foreach($category->childrenRecursive as $child)
            @include('blog::livewire.categories.category-item', ['category' => $child, 'level' => $level + 1])
        @endforeach
    @endif
</div>
