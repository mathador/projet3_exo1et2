<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\Notes\NoteService;
use App\Services\Tags\TagService;

class Notes extends Component
{
    public $notes;
    public $text = '';
    public $tag_id = '';
    public $tags;

    protected $rules = [
        'text' => 'required|string',
        'tag_id' => 'required|exists:tags,id',
    ];
    protected $listeners = ['tagCreated' => 'refreshTags'];

    protected NoteService $noteService;
    protected TagService $tagService;

    public function boot(NoteService $noteService, TagService $tagService)
    {
        $this->noteService = $noteService;
        $this->tagService = $tagService;
    }

    public function mount()
    {
        $this->tags = $this->tagService->getAllTags();
        $this->loadNotes();
    }

    public function loadNotes()
    {
        $this->notes = $this->noteService->getUserNotes();
    }

    public function refreshTags()
    {
        $this->tags = $this->tagService->getAllTags();
    }

    public function save()
    {
        // Validation
        $this->validate();

        // Création via le service
        $this->noteService->createNote($this->text, $this->tag_id);

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
        $this->noteService->deleteNote($noteId);
        
        // Rechargement des notes
        $this->loadNotes();
    }

    public function render()
    {
        return view('livewire.notes');
    }
}
