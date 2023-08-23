<?php

namespace App\Exports;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProjectsExport implements FromView
{
    public function view(): View
    {
        $projects = Project::with('tasks')->get();
        return view('exports.projects', compact('projects'));
    }
}
