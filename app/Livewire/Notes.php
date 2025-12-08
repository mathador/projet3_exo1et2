<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

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

    public function mount()
    {
        $this->tags = Tag::all();
        $this->loadNotes();
    }

    public function loadNotes()
    {
        $this->notes = Note::with('tag')->where('user_id', Auth::id())->latest()->get();
    }

    public function refreshTags()
    {
        $this->tags = \App\Models\Tag::all();
    }

    public function save()
    {
        // metier
        $this->validate();

        // persistance
        Note::create([
            'user_id' => Auth::id(),
            'tag_id' => $this->tag_id,
            'text' => $this->text,
        ]);

        // réinitialisation
        $this->text = '';
        $this->tag_id = '';

        $this->loadNotes();

        session()->flash('message', 'Note added.');
    }

    public function delete($noteId)
    {
        // persitance
        Note::where('id', $noteId)->where('user_id', Auth::id())->delete();
        // ihm ?
        $this->loadNotes();
    }

    public function render()
    {
        return view('livewire.notes');
    }
}
