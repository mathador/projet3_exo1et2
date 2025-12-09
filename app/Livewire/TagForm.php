<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\Tags\TagService;

class TagForm extends Component
{
    public $name = '';

    protected $rules = [
        'name' => 'required|string|max:50|unique:tags,name',
    ];

    protected TagService $tagService;

    public function boot(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function save()
    {
        // Validation
        $this->validate();

        // Création via le service
        $this->tagService->createTag($this->name);

        // Réinitialisation
        $this->reset('name');

        // Notification aux autres composants
        $this->dispatch('tagCreated');

        session()->flash('message', 'Tag added!');
    }

    public function render()
    {
        return view('livewire.tag-form');
    }
}
