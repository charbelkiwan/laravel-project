<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProjectsExport implements WithHeadings, FromQuery, WithMapping
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Project::query()->with('tasks:id,title');

        if ($this->filters) {
            if (isset($this->filters['due_date'])) {
                $query->whereYear('due_date', $this->filters['due_date']);
            }
        }

        return $query;
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
