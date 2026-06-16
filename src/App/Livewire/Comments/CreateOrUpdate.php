<?php

namespace Apachish\Blog\App\Livewire\Comments;

use Livewire\Component;

class CreateOrUpdate extends Component
{
    public function render()
    {
        return view('blog::livewire.comments.create-or-update')->layout('layouts::panel');
    }
}
