<?php

namespace Apachish\Blog\App\Console\Commands;

use Apachish\Blog\App\Models\Media;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:prune-orphan-media')]
#[Description('حذف فایل‌های آپلودشده‌ای که هیچ‌وقت داخل محتوا ذخیره نشدن')]
class PruneOrphanMedia extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = 0;

        Media::query()
            ->where('used', false)
//            ->where('created_at', '<', now()->subHours(24))
            ->get()
            ->each(function (Media $media) use (&$count) {
                $media->delete(); // فایل هم خودکار پاک می‌شه، به شرطی که observer روی همین کلاس باشه
                $count++;
            });

        $this->info("تعداد {$count} فایل یتیم پاک شد.");

        return self::SUCCESS;
    }
}
