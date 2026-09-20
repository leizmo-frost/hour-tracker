<div class="p-6">
    <h2 class="text-2xl font-bold mb-6 text-accent-orange">Pending Approvals</h2>

    @foreach ($pendingTimesheets as $timesheet)
        <div class="bg-navy-800 border border-navy-700 rounded-lg p-5 mb-4 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-white">{{ $timesheet->employee->user->name }}</h3>
                <p class="text-sm text-gray-400">{{ $timesheet->period_start->format('M d') }} - {{ $timesheet->period_end->format('M d') }}</p>
                <p class="text-accent-teal font-bold mt-1">{{ $timesheet->total_hours }} Hours</p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- The Red Locked Badge -->
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border bg-accent-red/10 text-accent-red border-accent-red/30">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                    Ready to Lock
                </span>
                <button wire:click="approve({{ $timesheet->id }})"
                    class="px-4 py-2 bg-accent-teal text-navy-900 rounded font-bold hover:bg-teal-400 transition">
                    Approve & Lock
                </button>
            </div>
        </div>
    @endforeach
</div>
    {{-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius --}}
