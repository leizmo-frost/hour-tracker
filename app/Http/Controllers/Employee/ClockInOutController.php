<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClockInOutController extends Controller
{
    public function index(Request $request)
    {
        return view('livewire.employee.clock-in-out-page', [
            'user' => $request->user()
        ]);
    }
}
