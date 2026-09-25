<div wire:poll.3s="refreshState">
    <div class="page-title">
        <div>
            <h1>Clock in / out</h1>
            <p>Record the work session and let the system calculate total hours.</p>
        </div>
        <span class="badge badge-blue">live clock</span>
    </div>
    @if($message)<div class="notice {{ $messageType }}">{{ $message }}</div>@endif
    <div class="grid grid-2">
        <div class="panel clock-center">
            @if($activeEntry)
                <div class="badge badge-yellow">session open</div>
                <div style="height:16px"></div>
                <div class="clock-ring">
                    <div>
                        <div class="clock-time">{{ Carbon\Carbon::parse($activeEntry['clock_in'])->diffForHumans(now(), true) }}</div>
                        <div class="clock-date">elapsed</div>
                    </div>
                </div>
                <div style="height:15px"></div>
                <div class="muted">Started {{ Carbon\Carbon::parse($activeEntry['clock_in'])->format('D d M, H:i') }}</div>
                <div class="hero-actions"><button wire:click="clockOut" class="btn btn-danger">Clock out</button>
            </div>
            @else
                <div class="badge badge-green">ready to punch</div>
                <div style="height:16px"></div>
                <div class="clock-ring">
                    <div>
                        <div class="clock-time">{{ now()->format('H:i') }}</div>
                        <div class="clock-date">{{ now()->format('d M Y') }}</div>
                    </div>
                </div>
                <div style="height:18px"></div>
                <div class="hero-actions">
                    <button wire:click="clockIn" class="btn btn-primary">Clock in</button>
                </div>
            @endif
        </div>
        <div class="panel panel-pad">
            <div class="toolbar" style="padding:0 0 14px;border:0">
                <div>
                    <h2>Session details</h2>
                    <div class="muted">Optional details stored with the punch.</div>
                </div>
            </div>
            <div class="form-grid">
                <div class="field">
                    <label>Break minutes</label>
                    <input class="input" type="number" min="0" max="480" wire:model="breakMinutes">
                </div>
                <div class="field full">
                    <label>Notes</label>
                    <textarea class="textarea" wire:model="notes" placeholder="Client visit, office work, remote session…"></textarea>
                </div>
            </div>
            <div style="height:18px"></div>
            <div class="kpi-row">
                <div class="mini">
                    <strong>{{ count($entries) }}</strong>
                    <span>recent entries</span>
                </div>
                <div class="mini">
                    <strong>{{ $activeEntry ? 'OPEN' : 'READY' }}</strong>
                    <span>current state</span>
                </div>
            </div>
        </div>
    </div>
    <div style="height:16px"></div>
    <div class="panel">
        <div class="toolbar">
            <div>
                <h2>Attendance history</h2>
                <div class="muted">Your latest punches</div>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Shift</th>
                        <th>In</th>
                        <th>Out</th>
                        <th>Break</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $row)
                    <tr>
                        <td>{{ Carbon\Carbon::parse($row['work_date'])->format('d M Y') }}</td>
                        <td>{{ data_get($row,'shift.name','—') }}</td>
                        <td>{{ Carbon\Carbon::parse($row['clock_in'])->format('H:i') }}</td>
                        <td>{{ $row['clock_out'] ? Carbon\Carbon::parse($row['clock_out'])->format('H:i') : '—' }}</td>
                        <td>{{ $row['break_minutes'] }}m</td><td>{{ number_format($row['total_minutes']/60,2) }}h</td>
                        <td>
                            <span class="badge {{ $row['status']==='closed'?'badge-green':'badge-yellow' }}">{{ $row['status'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty">No attendance records yet.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
