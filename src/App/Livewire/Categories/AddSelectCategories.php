<?php

namespace Apachish\Blog\App\Livewire\Categories;

use Apachish\Blog\App\Models\Category;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;

class AddSelectCategories extends Component
{
    #[Modelable]
    public array $categories = [];
    public $allCategories = [];
    public string $newCategoryName = '';
    public ?string $newCategoryParentId = null; // برای نگهداری آی‌دی دسته‌بندی والد
    public $project;
    public $locale;
    public $isFa;
    public $isFata = false;

    public function mount()
    {
        $this->loadCategories();
        $this->isFa = app()->getLocale() === 'fa';

    }

    public function updatedCategories($value)
    {
        $this->categories[] = $value;
        dd($this->categories);

    }

    public function loadCategories()
    {
        // دریافت دسته‌بندی‌ها به همراه روابط زیردسته‌ها
        $this->allCategories = Category::where("project_id", $this->project->id)
            ->where("locale", $this->locale)
            ->whereNull('parent_id') // دریافت دسته‌های اصلی
            ->with('childrenRecursive') // دریافت زیردسته‌ها به‌صورت درختی
            ->get();
    }

// متد افزودن دسته جدید به همراه انتخاب دسته والد
    public function addNewCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|string|max:100',
            'newCategoryParentId' => 'nullable|exists:blog_categories,id',
        ]);
        $category = Category::create([
            'project_id' => $this->project->id,
            'locale'     => $this->locale,
            'name'       => $this->newCategoryName,
            'slug'       => slug_seo($this->newCategoryName),
            'parent_id'  => $this->newCategoryParentId ?: null,
        ]);

        $this->categories[] = (string)$category->id;
        $this->newCategoryName = '';
        $this->newCategoryParentId = null;
        $this->loadCategories();

        session()->flash('success', __("blog::messages.Category added successfully"));
    }

    public function render()
    {

        return view('blog::livewire.categories.add-select-categories');
    }


    public function getCategoriesTreeProperty()
    {
        // گرفتن تمام دسته‌های پروژه و زبان جاری
        $categories = Category::where('project_id', $this->project->id)
            ->where('locale', $this->locale)
            ->get();

        // مرتب‌سازی درختی به صورت یک آرایه مسطح (Flat) با حفظ ترتیب والد و فرزند
        return $this->formatCategoryTree($categories);
    }

    protected function formatCategoryTree($categories, $parentId = null, $prefix = '')
    {
        $result = collect();

        // فیلتر کردن دسته‌هایی که والدشان برابر با parentId است
        $filtered = $categories->where('parent_id', $parentId);

        foreach ($filtered as $category) {
            // ایجاد یک کپی یا تغییر نام برای نمایش فرورفتگی
            $category->display_name = $prefix . ' ' . $category->name;
            $result->push($category);

            // فراخوانی بازگشتی برای فرزندان این دسته (با افزایش فاصله پیشوند)
            $children = $this->formatCategoryTree($categories, $category->id, $prefix . ' —');
            $result = $result->concat($children);
        }

        return $result;
    }
}
