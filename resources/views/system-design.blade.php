<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Employee Hours Tracking OOD</title>
@vite('resources/css/app.css')
</head>
<body class="system-page">
<section class="system-top">
    <div>
        <div class="system-clock"></div>
        <h1>Employee hours tracking<br>OOD</h1>
        <p style="color:#7b879d;font-size:12px;margin-top:20px">How would you design
            <span style="color:#2ee7e3;font-weight:800">an hours tracking system?</span>
            <br>Object-Oriented Design Breakdown
        </p>
    </div>
</section>
<section class="system-section">
    <div class="section-meta">01 / system requirements</div>
    <h3>System Requirements</h3><div class="requirements">
        <div class="req">Employees can clock in and clock out with timestamps.</div>
        <div class="req">The system calculates daily, weekly and overtime hours.</div>
        <div class="req">Managers review and approve submitted timesheets.</div>
        <div class="req">Employees submit PTO requests and managers process them.</div>
        <div class="req">Payroll reports use approved hours and configured overtime rules.</div>
    </div>
</section>
<section class="system-section">
    <div class="section-meta">02 / main actors</div>
    <h3 style="color:#b8a7ff">Main Actors</h3>
    <div class="actor-grid"><div class="actor">
        <div class="actor-head" style="background:#2ee7e3">Employee</div>
        <div class="actor-body">Clock in/out, view hours, submit timesheets and PTO requests.</div>
    </div>
    <div class="actor"><div class="actor-head" style="background:#6ca9ff">Manager</div>
    <div class="actor-body">Review timesheets, approve leave and monitor employee activity.</div>
</div>
    <div class="actor"><div class="actor-head" style="background:#ff9f24">Admin</div>
    <div class="actor-body">Manage employees, schedules, payroll and system configuration.</div>
</div>
    <div class="actor"><div class="actor-head" style="background:#15d5a0">System</div>
    <div class="actor-body">Calculates totals, tracks status changes and generates reports.</div>
</div>
</div>
</section>
<section class="system-section">
    <div class="section-meta">03 / class diagram</div>
    <h3 style="color:#2ebcf0">Key Classes</h3><div class="class-grid"><div class="class-card c-cyan"><div class="class-head">Employee</div><div class="class-body">Profile, department,<br>role, schedule.</div></div><div class="class-card c-blue"><div class="class-head">TimeEntry</div><div class="class-body">Clock event with<br>timestamps + GPS.</div></div><div class="class-card c-green"><div class="class-head">Timesheet</div><div class="class-body">Aggregated entries<br>for pay period.</div></div><div class="class-card c-orange"><div class="class-head">Shift</div><div class="class-body">Scheduled work<br>hours per day.</div></div><div class="class-card c-yellow"><div class="class-head">Break</div><div class="class-body">Break start/end<br>times in shift.</div></div><div class="class-card c-red"><div class="class-head">OvertimeRule</div><div class="class-body">Daily & weekly<br>hour thresholds.</div></div><div class="class-card c-purple"><div class="class-head">PTORequest</div><div class="class-body">Time off requests<br>with status tracking.</div></div><div class="class-card c-sky"><div class="class-head">PayrollReport</div><div class="class-body">Summary data for<br>payroll processing.</div></div></div></section>
<section class="system-section">
    <div class="section-meta">04 / activity diagrams</div>
    <h3 style="color:#15d5a0">Activity Diagrams</h3>
    <div class="activity-wrap">
        <div>
            <div class="flow-title" style="color:#2ee7e3">Clock In Flow</div>
            <div class="flow">
                <div class="flow-step">Authenticate</div>
                <div class="flow-step">Record Timestamp</div>
                <div class="flow-step">Capture Location</div>
                <div class="flow-step">Validate Schedule</div>
                <div class="flow-step">Confirm Punch</div>
            </div>
        </div>
        <div>
            <div class="flow-title" style="color:#9b78ff">Timesheet Approval</div>
            <div class="flow orange"><div class="flow-step">Submit Hours</div>
            <div class="flow-step">Manager Review</div>
            <div class="flow-step">Approve / Reject</div>
            <div class="flow-step">Forward to Payroll</div>
        </div>
    </div>
</div>
</section>
<div style="padding:24px 36px;background:#070c15">
    <a href="{{ route('dashboard') }}" class="btn btn-primary">← Back to application</a>
</div>
</body></html>
