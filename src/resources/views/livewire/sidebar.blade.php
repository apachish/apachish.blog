@canany(["categories","posts","tags",'comments'])

    @if($packages && in_array('Blog(dev-master)',$packages->pluck("version")->toArray()))
        <flux:sidebar.nav>
            <flux:sidebar.group expandable :heading="__('Blog')" class="grid">
                <x-slot:heading>
        <span class="flex items-center gap-2">
            <flux:brand href="#" name="book-open" class="size-4">
                    <x-slot name="logo" class="size-6 rounded-full  text-white text-xs font-bold">
                        <i class="{{$packages->where("version",'Blog(dev-master)')->first()->icon}} text-lg text-gray-700 dark:text-gray-300'"></i>
                    </x-slot>
            </flux:brand>
            <span>{{ __('Blog') }}</span>
        </span>
                </x-slot:heading>
                @can("categories")
                    <flux:sidebar.item icon="archive-box"
                                       :href="route('blog.categories.index',['api_key'=>$api_key])"
                                       :current="request()->routeIs('category.index')" wire:navigate>
                        {{ __('Categories') }}
                    </flux:sidebar.item>
                @endcan
                @can("posts")
                    <flux:sidebar.item icon="document-text"
                                       :href="route('blog.posts.index',['api_key'=>$api_key])"
                                       :current="request()->routeIs('posts.index')" wire:navigate>
                        {{ __('Posts') }}
                    </flux:sidebar.item>
                @endcan
                @can("tags")
                    <flux:sidebar.item icon="hashtag" :href="route('blog.tags.index',['api_key'=>$api_key])"
                                       :current="request()->routeIs('tags.index')" wire:navigate>
                        {{ __('Tags') }}
                    </flux:sidebar.item>
                @endcan
                @can("comments")
                    <flux:sidebar.item icon="chat-bubble-left-right"
                                       :href="route('blog.comments.index',['api_key'=>$api_key])"
                                       :current="request()->routeIs('comments.index')" wire:navigate>
                        {{ __('Comments') }}
                    </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
        </flux:sidebar.nav>
    @endif

@endcanany
