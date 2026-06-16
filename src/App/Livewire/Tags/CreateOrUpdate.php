<?php

namespace Apachish\Blog\App\Livewire\Tags;

use Apachish\Blog\App\Models\Tag;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateOrUpdate extends Component
{
    public $title;
    public $title_button;
    public $tag_id = null;
    public $project = null;

    public $tag = [
        'id' => null,
        'name' => null,
        'slug' => null,
        'project_id' => null,
    ];
    public $categories = [];


    public function mount()
    {
        $tag = Tag::find($this->tag_id);
        $this->tag = $tag ? $tag->toArray() : $this->tag;
        $this->title_button = __("Create");
        if ($tag)
            $this->title_button = __("Edit");
        $this->project = current_project();
        $this->tag["project_id"] = $this->project->id;
    }

    public function messages()
    {
        return [
            'tag.slug.unique' => __("blog::messages.This tag has already been registered in this project"),
            'tag.name.required' => __("blog::messages.Tag name is required."),
        ];
    }

    public function save()
    {
        $this->tag["slug"] = slug_seo($this->tag['name']);

        $this->validate([
            'tag.project_id' => ['required', 'exists:projects,id'],


            'tag.name' => [
                'required',
                'string',
                'max:255'
            ],

            'tag.slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blog_tags','slug')
                    ->where('project_id', $this->project->id)
                    ->ignore($this->tag_id)
            ],
        ]);

        Tag::updateOrCreate(['id' => $this->tag['id'],'project_id' => $this->tag['project_id']], [
            'name' => data_get($this->tag,'name'),
            'slug' => data_get($this->tag,'slug'),
        ]);
        return $this->redirect(route('blog.tags.index',['api_key'=>$this->project->api_key]));

    }

    public function render()
    {
        $this->title = __('blog::messages.Create Tag');
        return view('blog::livewire.tags.create-or-update')->layout('layouts::panel');
    }
}
