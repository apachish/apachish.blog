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

    public function addTag(){
        $this->dispatch('tag-added', name: $this->name, value: $this->selectedId,model:$this->tag);

    }

    public function select($id)
    {
        $this->selectedId = $id;

        $this->tag = Tag::where("locale",$this->locale)->find($id);
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
        $results = [];


        $results =  Tag::where("locale",$this->locale);
        if (strlen($this->search) >= 2)
            $results->where('name', 'like', '%' . $this->search . '%');
        $results = $results->limit(5)
            ->get();
        return view('blog::livewire.tags.search-tag',[
        'results' => $results
        ])->layout('layouts::panel');
    }







}
