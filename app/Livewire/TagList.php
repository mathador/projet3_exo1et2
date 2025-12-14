<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\Api\TagApiClient;
use Illuminate\Support\Collection;

class TagList extends Component
{
    public Collection $tags;

    protected $listeners = ['tagCreated' => 'refreshTags'];

    public function boot(TagApiClient $tagApiClient)
    {
        $this->tags = $tagApiClient->list();
    }

    public function refreshTags(TagApiClient $tagApiClient)
    {
        $this->tags = $tagApiClient->list();
    }

    public function delete(int $tagId, TagApiClient $tagApiClient)
    {
        try {
            $tagApiClient->delete($tagId);
            $this->refreshTags($tagApiClient);
            session()->flash('message-tag-list', 'Tag deleted.');
        } catch (\RuntimeException $e) {
            session()->flash('error-tag-list', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.tag-list');
    }
}
