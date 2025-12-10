<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\Api\NoteApiClient;
use App\Services\Api\TagApiClient;

class Notes extends Component
{
    public $notes;
    public $text = '';
    public $tag_id = '';
    public $tags;

    protected $rules = [
        'text' => 'required|string',
        'tag_id' => 'required|integer',
    ];
    protected $listeners = ['tagCreated' => 'refreshTags'];

    protected NoteApiClient $noteService;
    protected TagApiClient $tagService;

    public function boot(NoteApiClient $noteService, TagApiClient $tagService)
    {
        $this->noteService = $noteService;
        $this->tagService = $tagService;
    }

    public function mount()
    {
        $this->tags = $this->tagService->list();
        $this->loadNotes();
    }

    public function loadNotes()
    {
        $this->notes = $this->noteService->list();
    }

    public function refreshTags()
    {
        $this->tags = $this->tagService->list();
    }

    public function save()
    {
        // Validation
        $this->validate();

        // Création via le service
        $this->noteService->create($this->text, (int) $this->tag_id);

        // Réinitialisation
        $this->text = '';
        $this->tag_id = '';

        // Rechargement des notes
        $this->loadNotes();

        session()->flash('message', 'Note added.');
    }

    public function delete($noteId)
    {
        // Suppression via le service
        $this->noteService->delete((int) $noteId);
        
        // Rechargement des notes
        $this->loadNotes();
    }

    public function render()
    {
        return view('livewire.notes');
    }
}
