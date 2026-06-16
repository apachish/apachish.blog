<?php

namespace Apachish\Blog\App\Livewire\Posts;

use Livewire\Component;
use Livewire\WithFileUploads;

class CreateOrUpdate extends Component
{
    use WithFileUploads;

    // فیلدهای فرم
    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public string $excerpt = '';
    public string $status = 'draft';
    public string $category = '';
    public string $tags = '';
    public string $meta_title = '';
    public string $meta_description = '';
    public $featured_image = null;
    public ?string $featured_image_preview = null;
    public bool $seoOpen = false;

    // Validation Rules
    protected function rules(): array
    {
        return [
            'title'            => 'required|min:3|max:255',
            'slug'             => 'required|unique:posts,slug',
            'content'          => 'required|min:10',
            'excerpt'          => 'nullable|max:500',
            'status'           => 'required|in:draft,published,scheduled',
            'category'         => 'nullable|string|max:100',
            'tags'             => 'nullable|string|max:500',
            'meta_title'       => 'nullable|max:60',
            'meta_description' => 'nullable|max:160',
            'featured_image'   => 'nullable|image|max:2048',
        ];
    }

    protected $messages = [
        'title.required'   => 'عنوان پست الزامی است.',
        'title.min'        => 'عنوان باید حداقل ۳ کاراکتر داشته باشد.',
        'slug.required'    => 'اسلاگ الزامی است.',
        'slug.unique'      => 'این اسلاگ قبلاً استفاده شده است.',
        'content.required' => 'محتوای پست الزامی است.',
        'content.min'      => 'محتوا باید حداقل ۱۰ کاراکتر داشته باشد.',
    ];

    // وقتی عنوان تغییر کند، اسلاگ به‌صورت خودکار تولید شود
    public function updatedTitle(string $value): void
    {
        if (empty($this->slug) || $this->slug === Str::slug($this->title)) {
            $this->slug = Str::slug($value);
        }
    }

    // آپلود تصویر شاخص
    public function updatedFeaturedImage(): void
    {
        $this->validate(['featured_image' => 'image|max:2048']);
        $this->featured_image_preview = $this->featured_image->temporaryUrl();
    }

    // ذخیره محتوای ادیتور از Alpine/TipTap
    public function updateContent(string $html): void
    {
        $this->content = $html;
    }

    // ذخیره پست
    public function save(string $status = 'draft'): void
    {
        $this->status = $status;
        $this->validate();

        $imagePath = null;
        if ($this->featured_image) {
            $imagePath = $this->featured_image->store('posts', 'public');
        }

        Post::create([
            'title'            => $this->title,
            'slug'             => $this->slug,
            'content'          => $this->content,
            'excerpt'          => $this->excerpt,
            'status'           => $this->status,
            'category'         => $this->category,
            'tags'             => $this->tags,
            'meta_title'       => $this->meta_title ?: $this->title,
            'meta_description' => $this->meta_description ?: $this->excerpt,
            'featured_image'   => $imagePath,
            'user_id'          => auth()->id(),
        ]);

        session()->flash('success', 'پست با موفقیت ذخیره شد.');
        $this->redirect(route('blog.posts.index'));
    }


    public function render()
    {
        return view('blog::livewire.posts.create-or-update')->layout('layouts::panel');
    }



}
