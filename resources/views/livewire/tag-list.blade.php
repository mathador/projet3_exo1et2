<div class="space-y-4">
    @if (session()->has('message-tag-list'))
        <div class="text-green-600 text-sm">{{ session('message-tag-list') }}</div>
    @endif
    @if (session()->has('error-tag-list'))
        <div class="text-red-500 text-sm">{{ session('error-tag-list') }}</div>
    @endif

    <h2 class="text-xl font-bold">Tags</h2>

    <div class="space-y-2">
        @forelse ($tags as $tag)
            <div class="border p-3 flex justify-between items-start">
                <div>
                    <p>{{ $tag->name }}</p>
                </div>
                <button wire:click="delete({{ $tag->id }})" class="text-red-500 text-sm">Delete</button>
            </div>
        @empty
            <p>No tags yet.</p>
        @endforelse
    </div>
</div>