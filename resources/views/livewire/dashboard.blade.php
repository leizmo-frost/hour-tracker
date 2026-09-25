<div>
    <div class="hero-panel">
        <div class="hero-eyebrow">employee hours tracking / workspace</div>
        <h2>Turn clock events into clean timesheets, approvals and payroll.</h2>
        <p>This working application follows the object model from the design video: Employee, TimeEntry, Timesheet, Shift, Break, OvertimeRule, PTORequest and PayrollReport are represented in the backend and surfaced through role-aware dashboards.</p>
        <div class="hero-actions"><a class="btn btn-primary" href="{{ route('clock') }}">{{ $employee ? 'Open clock' : 'View workspace' }}</a><a class="btn btn-secondary" href="{{ route('system-design') }}">View OOD design</a></div>
    </div>

    <div style="height:16px"></div>
    <div class="grid grid-4">
        <div class="panel stat-card accent-cyan"><div class="stat-label">Active employees</div><div class="stat-value">{{ $stats['employees'] }}</div><div class="stat-note">Current active profiles</div></div>
        <div class="panel stat-card accent-purple"><div class="stat-label">Hours this month</div><div class="stat-value">{{ number_format($stats['hours'],2) }}</div><div class="stat-note">Closed time entries</div></div>
        <div class="panel stat-card accent-orange"><div class="stat-label">Overtime</div><div class="stat-value">{{ number_format($stats['overtime'],2) }}</div><div class="stat-note">Hours above the base threshold</div></div>
        <div class="panel stat-card accent-green"><div class="stat-label">Pending actions</div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-note">Approvals / your submissions</div></div>
    </div>

    <div style="height:16px"></div>
    <div class="grid grid-2">
        <div class="panel">
            <div class="toolbar"><div><h2>Recent time entries</h2><div class="muted">Latest clock activity</div></div><a class="btn btn-small btn-secondary" href="{{ route('clock') }}">Open clock</a></div>
            @if($recentEntries->isEmpty())<div class="empty">No time entries yet.</div>@else<div class="table-wrap"><table><thead><tr><th>Date</th><th>Clock in</th><th>Clock out</th><th>Total</th><th>Status</th></tr></thead><tbody>@foreach($recentEntries as $entry)<tr><td>{{ $entry->work_date->format('d M') }}</td><td>{{ $entry->clock_in->format('H:i') }}</td><td>{{ $entry->clock_out?->format('H:i') ?? '—' }}</td><td>{{ number_format($entry->hours,2) }}h</td><td><span class="badge {{ $entry->status==='closed'?'badge-green':'badge-yellow' }}">{{ $entry->status }}</span></td></tr>@endforeach</tbody></table></div>@endif
        </div>
        <div class="panel">
            <div class="toolbar"><div><h2>Your timesheets</h2><div class="muted">Latest weekly submissions</div></div><a class="btn btn-small btn-secondary" href="{{ route('timesheets') }}">View all</a></div>
            @if($recentTimesheets->isEmpty())<div class="empty">No timesheets generated yet.</div>@else<div class="table-wrap"><table><thead><tr><th>Period</th><th>Total</th><th>Status</th></tr></thead><tbody>@foreach($recentTimesheets as $sheet)<tr><td>{{ $sheet->period_start->format('d M') }} – {{ $sheet->period_end->format('d M') }}</td><td>{{ number_format($sheet->total_hours,2) }}h</td><td><span class="badge {{ $sheet->status==='approved'?'badge-green':($sheet->status==='rejected'?'badge-red':'badge-blue') }}">{{ $sheet->status }}</span></td></tr>@endforeach</tbody></table></div>@endif
        </div>
    </div>
</div>
