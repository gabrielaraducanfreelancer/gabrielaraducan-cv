<?php

namespace App\Http\Controllers;

class ResumeController extends Controller
{
    public function __invoke()
    {
        return view('resume');
    }
}
