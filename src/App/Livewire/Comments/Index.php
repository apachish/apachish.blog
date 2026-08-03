<?php

namespace Apachish\Blog\App\Livewire\Comments;

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
    protected $listeners = ['delete-row' => 'deleteRow', 'refresh-categories' => '$refresh', 'dateSelected' => 'handleDateSelection'];

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
                'placeholder' => __("blog::messages.Name Category") . '...',
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
        $this->link_create = route("blog.categories.create", ["api_key" => request()->route('api_key', $this->project->api_key)]);
        $this->title = __('blog::messages.Categories');
        $this->headers = [
            [
                'key' => 'id',
                'type' => 'checkbox',
                'label' => __("Deal ID"),
            ],
            [
                'key' => 'name',
                'label' => __("blog::messages.Name Category"),
            ],
            [
                'key' => 'parent.name',// {{ $category->parent?->name ?? 'ندارد' }}
                'label' => __("blog::messages.Parent Category"),
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
        $categories = Category::with('parent');

        $categories->when($this->filterState['search'] ?? null, function ($query, $value) {
            $query->where('name', 'like', "%{$value}%");
        });

        $categories->when(
            isset($this->filterState['status']) && in_array($this->filterState['status'], ["0", "1"]),
            function ($query) {
                $query->where('status', (int)$this->filterState['status']);
            }
        );


        $categories = $categories->simplePaginate($this->limit);

        $this->data = $categories->count()
            ? [
                "tableRowData" => $categories->items(),
                'pagination' => [
                    "current_page" => $categories->currentPage(),
                    "next_page_url" => $categories->nextPageUrl(),
                    "prev_page_url" => $categories->previousPageUrl(),
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
        $this->dispatch("refresh-categories");
        $this->dispatch("table-updated");

    }

    public function deleteRow($id)
    {
        if ($id) {
            Category::findOrFail($id)->delete();
            $this->loadCategories();
            $this->dispatch("refresh-categories");
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
