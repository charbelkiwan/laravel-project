<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProjectsExport implements WithHeadings, FromQuery, WithMapping
{
    public function __construct()
    {
    }

    public function query()
    {
        return Project::query()->with('tasks:id,title');
    }

    public function headings(): array
    {
        return [
            'Project Name',
            'Task Name',
            'Project Due Date',
        ];
    }

    public function map($project): array
    {
        $project->load('tasks');
        return [
            $project->title,
            implode(" | ", $project->tasks->pluck('title')->toArray()),
            $project->due_date,
        ];
    }
}
