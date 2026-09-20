<div class="bg-gray-950 border border-gray-900 rounded-lg p-6">
    <h2 class="text-xl font-bold text-white mb-4">Request Time Entry Correction</h2>
    
    @if (session()->has('message'))
        <div class="bg-teal-500/10 border border-teal-500 text-teal-400 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="submit" class="space-y-4">
        <div>
            <label class="block text-gray-400 text-sm mb-2">Requested Hours</label>
            <input type="number" step="0.25" wire:model="requestedHours" 
                   class="w-full bg-black border border-gray-800 rounded px-3 py-2 text-white focus:outline-none focus:border-teal-500"
                   placeholder="e.g., 8.0">
            @error('requestedHours') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-400 text-sm mb-2">Reason for Correction</label>
            <textarea wire:model="reason" rows="4"
                      class="w-full bg-black border border-gray-800 rounded px-3 py-2 text-white focus:outline-none focus:border-teal-500"
                      placeholder="Explain why this correction is needed..."></textarea>
            @error('reason') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" 
                class="bg-teal-500 hover:bg-teal-600 text-black font-semibold px-6 py-2 rounded transition">
            Submit Correction Request
        </button>
    </form>
</div>
