<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        return view('livewire.manager.timesheet-approvals-page', [
            'user' => $request->user()
        ]);
    }

    public function corrections(Request $request)
    {
        return view('livewire.manager.review-corrections-page', [
            'user' => $request->user()
        ]);
    }
}
