<?php

namespace Apachish\Blog\App\Livewire\Posts;

// توجه: مسیر مدل Post رو با ساختار واقعی پکیج/پروژه‌ی خودتون چک کنید
use Apachish\Blog\App\Models\Category;
use Apachish\Blog\App\Models\Post;
use Apachish\User\Models\User;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
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
    public string $locale = '';
    public string $category = '';
    public array $tags = [];
    public  $meta_title = '';
    public  $meta_description = '';
    public $featured_image = null;
    public ?string $featured_image_preview = null;
    public bool $seoOpen = false;

    public $metaTitleLength = 0;
    public $excerptLength = 0;
    public bool $saved = false;
    public string $wordCount = '0 کلمه';
     public $published_time ;
     public $published_at ;

    // برای جلوگیری از بازنویسی اسلاگ بعد از ویرایش دستی توسط کاربر
    public bool $slugManuallyEdited = false;

    public $project;
    public $categories = [];
    public $post = null;
    public $post_id = null;


    public function mount()
    {
        $this->project = current_project();
        $this->post = Post::find($this->post_id);
        $this->locale = app()->getLocale() != $this->locale && $this->locale ? $this->locale : app()->getLocale() ;

        if($this->post)
        {
            $this->title = $this->post->title;
            $this->slug = $this->post->slug;
            $this->content = $this->post->content;
            $this->excerpt = $this->post->excerpt;
            $this->status = $this->post->status;
            $this->meta_description = $this->post->meta_description;
            $this->meta_title = $this->post->meta_title;
            $this->locale = $this->post->locale;
            $this->featured_image = $this->post->featured_image;
            $this->featured_image_preview = $this->post->featured_image;
            $this->category = $this->post->categories->first()->id??null;
        }

    }
    // Validation Rules
    protected function rules(): array
    {
        return [
            'title'            => 'required|min:3|max:255',
            'slug'             => 'required|alpha_dash|unique:blog_posts,slug,'.$this->post_id,
            'content'          => 'required|min:10',
            'excerpt'          => 'nullable|max:500',
            'status'           => 'required|in:draft,published,scheduled',
            'category'         => ['nullable','string','max:100',function ($attribute, $value, $fail) {
                $exists = Category::where('locale', $this->locale)
                        ->where('id', $value)->exists();

                if (!$exists) {
                    $fail(__("blog::messages.Category not found in current language."));
                }
            }],
            'tags'             => 'nullable|array',
            'meta_title'       => 'nullable|max:60',
            'meta_description' => 'nullable|max:160',
            'featured_image'   =>  ($this->featured_image instanceof \Illuminate\Http\UploadedFile)
                ? 'nullable|image|max:2048'
                : 'nullable',
        ];
    }

    protected $messages = [
        'title.required'   => 'عنوان پست الزامی است.',
        'title.min'        => 'عنوان باید حداقل ۳ کاراکتر داشته باشد.',
        'slug.required'    => 'اسلاگ الزامی است.',
        'slug.alpha_dash'  => 'اسلاگ فقط می‌تواند حروف انگلیسی، عدد، خط‌تیره و آندرلاین داشته باشد.',
        'slug.unique'      => 'این اسلاگ قبلاً استفاده شده است.',
        'content.required' => 'محتوای پست الزامی است.',
        'content.min'      => 'محتوا باید حداقل ۱۰ کاراکتر داشته باشد.',
    ];

    // وقتی عنوان تغییر کند، اسلاگ به‌صورت خودکار تولید شود
    // فقط تا زمانی که کاربر خودش دستی اسلاگ را عوض نکرده باشد
    public function updatedTitle(string $value): void
    {
        if (! $this->slugManuallyEdited) {
            $this->slug = slug_seo($value);
        }
    }

    public function updatedStatus(string $value): void
    {
        if ( $value == "scheduled") {
            $this->published_time = now()->timezone(config('app.timezone'))->format('H:i');
            $date =  now()->timezone(config('app.timezone'));
            $this->published_at = $this->locale == "fa"?toJalali($date,'Y/m/d'):date($date,'Y/m/d');
        }
    }

    // وقتی کاربر خودش روی فیلد اسلاگ تایپ کند، دیگر آن را خودکار بازنویسی نکن
    public function updatedSlug(): void
    {
        $this->slugManuallyEdited = true;
    }

    // آپلود تصویر شاخص
    public function updatedFeaturedImage(): void
    {
        $this->validate(['featured_image' => 'image|max:2048']);
        $this->featured_image_preview = $this->featured_image->temporaryUrl();
    }

    // حذف تصویر شاخص انتخاب‌شده
    public function removeFeaturedImage(): void
    {
        $this->featured_image = null;
        $this->featured_image_preview = null;
    }

    // ذخیره محتوای ادیتور از Alpine/TipTap
    // این متد مستقیم (از $wire) و از طریق event هر دو کار می‌کند
    public function updateContent(string $html): void
    {
        $this->content = $html;
        $this->saved = false;

    }

    public function updateWordCount(string $count): void
    {
        $this->wordCount = $count;
    }

    // listener برای Livewire.dispatch('tiptap-content-updated', {html: ...})
    // چون Alpine component دیگر روی root div نیست، از dispatch استفاده می‌شود
    #[\Livewire\Attributes\On('tiptap-content-updated')]
    public function onTiptapUpdated(string $html): void
    {
        $this->content = $html;
    }

    // ذخیره پست
    public function save(string $status = 'draft'): void
    {
        $this->status = $status;
        $this->validate();

        $imagePath = ($this->featured_image instanceof \Illuminate\Http\UploadedFile)
            ? $this->featured_image->store($this->project->id.'/posts', 'public')
            : data_get($this->post, 'featured_image');

        $user_admin  = auth()->user();
        $user = User::where("email_mobile",$user_admin->email)->first();

        if(!$user)
        {
            $user_admin  = $user_admin->replicate();
            $user = User::create([
                'project_id'=>$this->project->id,
                'name'=> $user_admin->name,
                'family'=>null,
                'email_mobile'=>$user_admin->email,
                'password'=>$user_admin->password,
            ]);
        }
        $post = Post::updateOrCreate([
            "id"=>$this->post_id,
            'project_id'          => $this->project->id,
            ],[
            'title'            => $this->title,
            'slug'             => $this->slug,
            'content'          => $this->content,
            'excerpt'          => $this->excerpt,
            'status'           => $this->status,
            'meta_title'       => $this->meta_title ?: $this->title,
            'meta_description' => $this->meta_description ?: $this->excerpt,
            'featured_image'   => $imagePath,
            'user_id'          => $user->id,
            'locale'          => $this->locale,
        ]);
        $post->categories()->sync($this->categories);
        if(sizeof($this->tags))
            $post->tags()->sync(collect($this->tags)->pluck("id")->toArray());
        $this->saved = true;

        session()->flash('success', 'پست با موفقیت ذخیره شد.');
        $this->redirect(route('blog.posts.index',["api_key"=>$this->project->api_key]));
    }

    public function clearEditor(): void
    {
        $this->content = '';
        $this->title   = '';
        $this->saved   = false;
        $this->dispatch('editor-cleared');
    }


    public function render()
    {

        $this->categories = Category::where("project_id",$this->project->id)->where("locale",$this->locale)->get();
        return view('blog::livewire.posts.create-or-update')->layout('layouts::panel');
    }

    #[On('tag-added')]
    public function handleTagChanged($name, $value,$model){

        $this->tags[] = $model;
        $this->dispatch('tag-clear');

    }
}
