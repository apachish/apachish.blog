<?php

namespace Apachish\Blog\App\Livewire\Tags;

use Apachish\Blog\App\Models\Tag;
use Livewire\Attributes\On;
use Livewire\Component;

class SearchTag extends Component
{


    public $search = '';
    public $selectedId = null;
    public $dropdownOpen = false;

    public $name;
    public $locale;
    public $project;
    public $tag;
    protected $listeners = ['openCloseDropdown' => 'openClose'];

    public function mount()
    {


        $tag = Tag::find($this->selectedId);
        if ($tag) {

            $this->search = $tag->name;
        }
    }
    public function openClose()
    {
        $this->dropdownOpen =$this->dropdownOpen?false:true;
    }


    public function updatedSearch($value)
    {
        $this->dropdownOpen = false;
        if(!$value) {
            $this->selectedId = null;
        }

    }

    public function addTag()
    {
        // اگر از لیست انتخاب نکرده بود ولی متنی تایپ کرده بود
        if ($this->selectedId == null && !empty($this->search)) {
            // ایجاد یا پیدا کردن تگ جدید با در نظر گرفتن پروژه و زبان جاری

            $tag = Tag::firstOrCreate(
                [
                    'name' => trim($this->search),
                    'project_id' => $this->project->id,
                ],
                [
                    'slug' => \Illuminate\Support\Str::slug($this->search)
                ]
            );

            $this->selectedId = $tag->id;
            $this->tag = $tag;
        }

        // ارسال ایونت با شناسه و مدل معتبر
        $this->dispatch('tag-added', name: $this->name, value: $this->selectedId, model: $this->tag);
    }

    public function select($id)
    {
        $this->selectedId = $id;

        $this->tag = Tag::find($id);
        if ($this->tag) {
            $this->search = $this->tag->name;
        }
        $this->dropdownOpen = false;

    }

    #[On('tag-clear')]
    public function clearInpt()
    {
        $this->search = null;
        $this->tag = null;
        $this->selectedId = null;
    }
    public function render()
    {
        $results =  Tag::query();
        if (strlen($this->search) >= 2)
            $results->where('name', 'like', '%' . $this->search . '%');
        $results = $results->limit(5)
            ->get();
        return view('blog::livewire.tags.search-tag',[
        'results' => $results
        ]);
    }







}
