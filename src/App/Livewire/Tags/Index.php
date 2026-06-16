<?php

namespace Apachish\Blog\App\Livewire\Tags;

use Apachish\Blog\App\Models\Tag;
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
    protected $listeners = ['delete-row' => 'deleteRow', 'refresh-tags' => '$refresh', 'dateSelected' => 'handleDateSelection'];

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
                'placeholder' => __("blog::messages.Name Tag").'...',
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
        $this->link_create = route("blog.tags.create",["api_key"=>request()->route('api_key',$this->project->api_key)]);
        $this->title = __('blog::messages.Tags');
        $this->headers = [
            [
                'key' => 'id',
                'type' => 'checkbox',
                'label' => __("Deal ID"),
            ],
            [
                'key' => 'name',
                'label' => __("blog::messages.Name Tag"),
            ],
            [
                'key' => 'slug',
                'label' => __("blog::messages.Slug Tag"),
            ],
            [
                'key' => 'updated_at',
                'type' => 'date',
                'label' => __('Updated At'),
            ], [
                'key' => 'Action',
                'type' => 'actions',
                'label' => __('Action'),
                'href_edit' => route('blog.tags.edit', ['api_key' => $this->project->api_key,'category_id' => '__ID__']),
            ],
        ];

        $this->loadTags();


        return view('blog::livewire.tags.index')->layout('layouts::panel');
    }


    public function loadTags()
    {
        $tags = Tag::query();

        $tags->when($this->filterState['search'] ?? null, function ($query, $value) {
            $query->where('name', 'like', "%{$value}%");
        });

        $tags->when(
            isset($this->filterState['status']) && in_array($this->filterState['status'], ["0", "1"]),
            function ($query) {
                $query->where('status', (int)$this->filterState['status']);
            }
        );


        $tags = $tags->simplePaginate($this->limit);

        $this->data = $tags->count()
            ? [
                "tableRowData" => $tags->items(),
                'pagination' => [
                    "current_page" => $tags->currentPage(),
                    "next_page_url" => $tags->nextPageUrl(),
                    "prev_page_url" => $tags->previousPageUrl(),
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

        $this->loadTags();
    }

    public function setFilters()
    {
        $this->resetPage(); // اگر paginate داری
        $this->dispatch("refresh-tags");
        $this->dispatch("table-updated");

    }

    public function deleteRow($id)
    {
        if ($id) {
            Tag::findOrFail($id)->delete();
            $this->loadTags();
            $this->dispatch("refresh-tags");
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
