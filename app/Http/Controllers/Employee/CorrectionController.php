<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CorrectionController extends Controller
{
    public function index(Request $request)
    {
        return view('livewire.employee.request-correction-page', [
            'user' => $request->user()
        ]);
    }
}
