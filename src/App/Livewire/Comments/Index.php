<?php

namespace Apachish\Blog\App\Livewire\Comments;

use Apachish\Blog\App\Models\Comment;
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
    protected $listeners = ['delete-row' => 'deleteRow', 'refresh-comments' => '$refresh', 'dateSelected' => 'handleDateSelection'];

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
                'label' => __('blog::messages.Search'),
                'type' => 'text',
                'placeholder' => __("blog::messages.Comment") . '...',
            ],
            [
                'key' => 'status',
                'label' => 'وضعیت',
                'type' => 'select',
                'options' => [
                    ['value' => '', 'label' => __("blog::messages.All")],
                    ['value' => '1', 'label' => __("blog::messages.Active")],
                    ['value' => '0', 'label' => __("blog::messages.Disabled")],
                ],
            ],
        ];
        $this->link_create = null;
        $this->title = __('blog::messages.Comments');
        $this->headers = [
            [
                'key' => 'id',
                'type' => 'checkbox',
                'label' => __("Deal ID"),
            ],
            [
                'key' => 'content',
                'label' => __("blog::messages.Comment"),
            ],
            [
                'key' => 'post.title',
                'label' => __("blog::messages.Name Post"),
            ],
            [
                'key' => 'replies.content',// {{ $category->parent?->name ?? 'ندارد' }}
                'label' => __("blog::messages.Previous Comment"),
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
                'href_edit' => ["url" => route('blog.comments.index', ['api_key' => $this->project->api_key, 'comment_id' => '__ID__']), "can" => "edit_Coment"],
                'href_delete' => ["can" => "delete_Coment"],
            ],
        ];

        $this->loadCategories();

        return view('blog::livewire.comments.index')->layout('layouts::panel');

    }


    public function loadCategories()
    {
        $comments = Comment::orderBy("updated_at", "desc");

        $comments->when($this->filterState['search'] ?? null, function ($query, $value) {
            $query->where('content', 'like', "%{$value}%");
        });

        $comments->when(
            isset($this->filterState['status']) && in_array($this->filterState['status'], ["0", "1"]),
            function ($query) {
                $query->where('status', (int)$this->filterState['status']);
            }
        );


        $comments = $comments->simplePaginate($this->limit);

        $this->data = $comments->count()
            ? [
                "tableRowData" => $comments->items(),
                'pagination' => [
                    "current_page" => $comments->currentPage(),
                    "next_page_url" => $comments->nextPageUrl(),
                    "prev_page_url" => $comments->previousPageUrl(),
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

        $this->loadCategories();
    }

    public function setFilters()
    {
        $this->resetPage(); // اگر paginate داری
        $this->dispatch("refresh-comments");
        $this->dispatch("table-updated");

    }

    public function deleteRow($id)
    {
        if ($id) {
            Category::findOrFail($id)->delete();
            $this->loadCategories();
            $this->dispatch("refresh-comments");
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
