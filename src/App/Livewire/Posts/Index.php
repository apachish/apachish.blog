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

    public array $statusLabels = [];
    public array $classes = [
        "draft"=> 'bg-blue-50 text-blue-700',
        "pending"=>'bg-yellow-50 text-yellow-700',
        "published"=>'bg-green-50 text-green-700',
        "private"=>'bg-yellow-50 text-yellow-700',
        "trash"=>'bg-red-50 text-red-700',
    ];

    public function mount()
    {
        $this->project = current_project();
        $this->statusLabels = [
            "draft"=>__("Draft"),
            "pending"=>__("pending"),
            "published"=>__("published"),
            "private"=>__("private"),
            "trash"=>__("trash"),

        ];

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
                'key' => 'title',
                'label' => __("blog::messages.Name Post"),
            ],
//            [
//                'key' => 'category.name',// {{ $post->parent?->name ?? 'ندارد' }}
//                'label' => __("blog::messages.Category"),
//            ],
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
                'href_edit' => ["url"=>route('blog.posts.edit', ['api_key' => $this->project->api_key,'post_id' => '__ID__']),"can"=>"edit_post"],
                'href_delete' => ["can"=>"delete_post"],
            ],
        ];
        $this->headers = collect( $this->headers)->map(function ($header) {
            if ($header['key'] === 'Action') {
                if (isset($header['href_edit']) && !auth()->user()->can($header['href_edit']['can'])) {
                    unset($header['href_edit']);
                }
                if (isset($header['href_panel']) && !auth()->user()->can($header['href_panel']['can'])) {
                    unset($header['href_panel']);
                }
                if (isset($header['href_delete']) && !auth()->user()->can($header['href_delete']['can'])) {
                    unset($header['href_delete']);
                }
                // اگر هیچ دکمه‌ای باقی نماند، کل ستون Action حذف شود
                if (!isset($header['href_edit']) && !isset($header['href_panel']) && !isset($header['href_delete'])) {
                    return null;
                }
            }
            return $header;
        })->filter()->values()->all();
        $this->loadPosts();


        return view('blog::livewire.posts.index')->layout('layouts::panel');
    }


    public function loadPosts()
    {
        $posts = Post::with('categories');

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
