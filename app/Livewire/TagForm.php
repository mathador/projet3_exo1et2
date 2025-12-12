<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\Api\TagApiClient;

class TagForm extends Component
{
    public $name = '';

    protected $rules = [
        'name' => 'required|string|max:50|unique:tags,name',
    ];

    protected TagApiClient $tagApiClient;

    public function boot(TagApiClient $tagApiClient)
    {
        $this->tagApiClient = $tagApiClient;
    }

    public function save()
    {
        // Validation
        $this->validate();

        // Création via le service
        $this->tagApiClient->create($this->name);

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
