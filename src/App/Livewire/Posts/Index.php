<?php

namespace Apachish\Blog\App\Livewire\Posts;

use Apachish\Blog\App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $title;
    public $data;
    public $link_create;
    public $limit = 15;
    public $headers;
    public $project;
    protected $listeners = ['delete-row' => 'deleteRow', 'refresh-posts' => '$refresh', 'dateSelected' => 'handleDateSelection'];

    public array $filters = [];

    public array $filterState = [
        'search' => '',
        'status' => '',
    ];

    public function mount()
    {
        $this->project = current_project();

    }

    public function render()
    {
        $this->filters = [
            [
                'key' => 'search',
                'label' => __('Search'),
                'type' => 'text',
                'placeholder' => __("blog::messages.Name Post").'...',
            ],
            [
                'key' => 'status',
                'label' => 'وضعیت',
                'type' => 'select',
                'options' => [
                    ['value' => '', 'label' => __("All")],
                    ['value' => '1', 'label' => __("Active")],
                    ['value' => '0', 'label' => __("Disabled")],
                ],
            ],
        ];
        $this->link_create = route("blog.posts.create",["api_key"=>request()->route('api_key',$this->project->api_key)]);
        $this->title = __('blog::messages.Posts');
        $this->headers = [
            [
                'key' => 'id',
                'type' => 'checkbox',
                'label' => __("Deal ID"),
            ],
            [
                'key' => 'name',
                'label' => __("blog::messages.Name Post"),
            ],
            [
                'key' => 'category.name',// {{ $post->parent?->name ?? 'ندارد' }}
                'label' => __("blog::messages.Category"),
            ],
            [
                'key' => 'viewer',// {{ $post->parent?->name ?? 'ندارد' }}
                'label' => __("blog::messages.Viewer"),
            ],
            [
                'key' => 'tags.name',// {{ $post->parent?->name ?? 'ندارد' }}
                'label' => __("blog::messages.Tags"),
            ],
            [
                'key' => 'status',
                'type' => 'status',
                'label' => __('Status'),
            ],
            [
                'key' => 'updated_at',
                'type' => 'date',
                'label' => __('Updated At'),
            ], [
                'key' => 'Action',
                'type' => 'actions',
                'label' => __('Action'),
                'href_edit' => route('blog.posts.edit', ['api_key' => $this->project->api_key,'post_id' => '__ID__']),
            ],
        ];

        $this->loadPosts();


        return view('blog::livewire.posts.index')->layout('layouts::panel');
    }


    public function loadPosts()
    {
        $posts = Post::with('parent');

        $posts->when($this->filterState['search'] ?? null, function ($query, $value) {
            $query->where('name', 'like', "%{$value}%");
        });

        $posts->when(
            isset($this->filterState['status']) && in_array($this->filterState['status'], ["0", "1"]),
            function ($query) {
                $query->where('status', (int)$this->filterState['status']);
            }
        );


        $posts = $posts->simplePaginate($this->limit);

        $this->data = $posts->count()
            ? [
                "tableRowData" => $posts->items(),
                'pagination' => [
                    "current_page" => $posts->currentPage(),
                    "next_page_url" => $posts->nextPageUrl(),
                    "prev_page_url" => $posts->previousPageUrl(),
                ],
            ]  // فقط آرایه داده‌ها
            : ["tableRowData" => []];
    }

    public function resetFilters()
    {
        $this->filterState = [
            'search' => '',
            'status' => '',
        ];

        $this->loadPosts();
    }

    public function setFilters()
    {
        $this->resetPage(); // اگر paginate داری
        $this->dispatch("refresh-posts");
        $this->dispatch("table-updated");

    }

    public function deleteRow($id)
    {
        if ($id) {
            Post::findOrFail($id)->delete();
            $this->loadPosts();
            $this->dispatch("refresh-posts");
        }
    }

    public function handleDateSelection($name, $value)
    {


        // حالا دستی ست کن
        data_set($this, $name, $value);

        // اگر نیاز داری بلافاصله فیلتر اعمال بشه، همینجا متدش رو صدا بزن
        // $this->applyFilters();
    }
}
