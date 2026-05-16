<div class="space-y-6">
    @if(session('status'))
        <div class="bg-green-50 border border-green-200 rounded-md p-3 text-sm text-green-800">{{ session('status') }}</div>
    @endif

    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <div><dt class="text-gray-500">Customer</dt><dd class="font-medium">{{ $enquiry->customer->name }}</dd></div>
            <div><dt class="text-gray-500">Type</dt><dd>{{ ucfirst($enquiry->enquiry_type) }}</dd></div>
            <div><dt class="text-gray-500">Status</dt>
                <dd><span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">{{ $enquiry->status }}</span></dd>
            </div>
            <div><dt class="text-gray-500">Destination</dt><dd>{{ $enquiry->destination ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Travel Dates</dt><dd>{{ $enquiry->travel_from?->format('d M') }} – {{ $enquiry->travel_to?->format('d M Y') ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">PAX</dt><dd>{{ $enquiry->pax_adult }}A {{ $enquiry->pax_child }}C</dd></div>
            <div><dt class="text-gray-500">Budget</dt><dd>₹{{ number_format($enquiry->budget_min?->toRupees() ?? 0) }} – ₹{{ number_format($enquiry->budget_max?->toRupees() ?? 0) }}</dd></div>
            <div><dt class="text-gray-500">Assigned To</dt><dd>{{ $enquiry->assignedUser?->name ?? 'Unassigned' }}</dd></div>
            <div><dt class="text-gray-500">Created</dt><dd>{{ $enquiry->created_at->format('d M Y H:i') }}</dd></div>
        </div>
        @if($enquiry->special_requirements)
            <div class="mt-4 pt-4 border-t">
                <dt class="text-gray-500 text-sm mb-1">Special Requirements</dt>
                <dd class="text-gray-700">{{ $enquiry->special_requirements }}</dd>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Change Status --}}
        <div class="bg-white shadow-sm rounded-lg p-4">
            <h3 class="font-medium text-gray-800 mb-3">Change Status</h3>
            <div class="flex gap-2">
                <select wire:model="newStatus" class="flex-1 border border-gray-300 rounded-md px-2 py-2 text-sm">
                    <option value="new">New</option>
                    <option value="in_progress">In Progress</option>
                    <option value="quoted">Quoted</option>
                    <option value="closed">Closed</option>
                </select>
                <button wire:click="changeStatus" class="px-4 py-2 bg-blue-700 text-white text-sm rounded-md">Update</button>
            </div>
        </div>

        {{-- Assign Agent --}}
        <div class="bg-white shadow-sm rounded-lg p-4">
            <h3 class="font-medium text-gray-800 mb-3">Assign Agent</h3>
            <div class="flex gap-2">
                <select wire:model="assignUserId" class="flex-1 border border-gray-300 rounded-md px-2 py-2 text-sm">
                    <option value="">Unassigned</option>
                    @foreach($this->agents as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                    @endforeach
                </select>
                <button wire:click="assignAgent" class="px-4 py-2 bg-gray-700 text-white text-sm rounded-md">Assign</button>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h3 class="font-medium text-gray-800 mb-4">Notes ({{ $enquiry->notes->count() }})</h3>
        <div class="space-y-3 mb-4">
            @forelse($enquiry->notes as $note)
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $note->author?->name ?? 'System' }}</span>
                        <span class="text-xs text-gray-400">{{ $note->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-600">{{ $note->body }}</p>
                </div>
            @empty
                <p class="text-sm text-gray-400">No notes yet.</p>
            @endforelse
        </div>
        <div class="flex gap-2">
            <textarea wire:model="newNote" rows="2" placeholder="Add a note..." class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
            <button wire:click="addNote" class="px-4 py-2 bg-blue-700 text-white text-sm rounded-md self-end">Add</button>
        </div>
        @error('newNote')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
    </div>
</div>
