<?php

namespace Apachish\Blog\App\Livewire\Categories;

use Apachish\Blog\App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateOrUpdate extends Component
{
    public $title;
    public $title_button;
    public $category_id = null;
    public $project = null;
    public string $locale = '';

    public $category = [
        'id' => null,
        'name' => null,
        'slug' => null,
        'project_id' => null,
        'locale' => null, // مقدار پیش‌فرض
        'parent_id' => null,
        'order' => 0,
        "description" => null,
        "status" => true,
    ];
    public $categories = [];


    public function mount()
    {
        $this->locale = app()->getLocale() != $this->locale && $this->locale ? $this->locale : app()->getLocale() ;

        $category = Category::find($this->category_id);
        $this->category = $category ? $category->toArray() : $this->category;
        $this->title_button = __("Create");
        if ($category)
            $this->title_button = __("Edit");
        $this->categories = Category::where("status", true)->get();
        $this->project = current_project();
        $this->category["project_id"] = $this->project->id;
        $this->category["locale"] = $this->locale;
    }

    public function messages()
    {
        return [
            'category.slug.unique' => 'این آدرس در این پروژه قبلاً ثبت شده است',
            'category.slug.required' => 'نام دسته بندی الزامی است',
            'category.name.required' => 'نام دسته بندی الزامی است',
        ];
    }

    public function save()
    {
        $this->category["slug"] = slug_seo($this->category['name']);

        $this->validate([
            'category.project_id' => ['required', 'exists:projects,id'],

            'category.parent_id' => [
                'nullable',
                'exists:blog_categories,id'
            ],

            'category.locale' => [
                'required',
                Rule::in(["en", "fa"])
            ],
            'category.name' => [
                'required',
                'string',
                'max:255'
            ],

            'category.slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blog_categories','slug')
                    ->where('project_id', $this->project->id)
                    ->ignore($this->category_id)
            ],

            'category.description' => [
                'nullable',
                'string'
            ],

            'category.status' => [
                'required',
                'boolean'
            ],

            'category.order' => [
                'nullable',
                'integer',
                'min:0'
            ]
        ]);
        // ۲. بررسی اینکه آیا عکس جدیدی آپلود شده است یا خیر

        Category::updateOrCreate(['id' => $this->category['id'],'project_id' => $this->category['project_id']], [
            'name' => data_get($this->category,'name'),
            'slug' => data_get($this->category,'slug'),
            'locale' => data_get($this->category,'locale'),
            'parent_id' => data_get($this->category,'parent_id')?:null,
            'order' => data_get($this->category,'order')?:1,
            'lo' => data_get($this->category,'order')?:1,

            'description' => data_get($this->category,'description'),
            'status' => data_get($this->category,'status'),

        ]);
        return $this->redirect(route('blog.categories.index',['api_key'=>$this->project->api_key]));

    }

    public function render()
    {
        $this->title = __('blog::messages.Create Category');
        return view('blog::livewire.categories.create-or-update')->layout('layouts::panel');
    }
}
