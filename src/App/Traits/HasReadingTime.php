<?php
// app/Traits/HasReadingTime.php

namespace Apachish\Blog\App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasReadingTime
{
    protected function readingTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->calculateReadingTime($this->{$this->readingTimeColumn ?? 'body'})
        );
    }

    private function calculateReadingTime(?string $text, int $wpm = 200): int
    {
        if (empty($text)) {
            return 0;
        }

        $plainText = strip_tags($text);
        $words = preg_split('/\s+/u', trim($plainText), -1, PREG_SPLIT_NO_EMPTY);

        return max(1, (int) ceil(count($words) / $wpm));
    }
}
