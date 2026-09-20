<div class="flex flex-col items-center justify-center min-h-[60vh]">

    @if($currentShift)
        <div wire:poll.1s="updateTimer" class="text-center mb-8">
            <p class="text-accent-teal text-sm font-bold tracking-widest uppercase mb-2">Currently Working</p>
            <h1 class="text-6xl font-mono font-bold text-white tracking-tight">{{ $elapsedTime }}</h1>
        </div>
    @else
        <div class="text-center mb-8">
            <p class="text-gray-400 text-sm font-bold tracking-widest uppercase mb-2">Ready to Work</p>
            <h1 class="text-6xl font-mono font-bold text-gray-600 tracking-tight">{{ now()->format('h:i A') }}</h1>
        </div>
    @endif

    <button wire:click="toggleClock"
        class="relative group w-48 h-48 rounded-full flex items-center justify-center transition-all duration-300 shadow-2xl
        {{ $currentShift ? 'bg-accent-red hover:bg-red-600 shadow-red-500/20' : 'bg-accent-teal hover:bg-teal-400 shadow-teal-500/20' }}">

        <span class="absolute inset-0 rounded-full opacity-30 animate-ping {{ $currentShift ? 'bg-accent-red' : 'bg-accent-teal' }}"></span>

        <div class="relative z-10 text-center">
            <span class="font-bold text-xl {{ $currentShift ? 'text-white' : 'text-navy-900' }}">
                {{ $currentShift ? 'CLOCK OUT' : 'CLOCK IN' }}
            </span>
        </div>
    </button>

    @script
    <script>
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const location = JSON.stringify({
                    lat: position.coords.latitude, lng: position.coords.longitude
                });
                Livewire.hook('request', ({options}) => { options.headers['X-Location'] = location; });
            });
        }
    </script>
    @endscript
</div>
    {{-- The best way to take care of the future is to take care of the present moment. - Thich Nhat Hanh --}}

