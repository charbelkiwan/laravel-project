<?php

namespace App\Http\Controllers;

use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Project;
use App\Exports\ProjectsExport;
use App\Imports\ProjectsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function index()
    {
        $cache_key = 'projects.index';

        Log::info('cash key:' . $cache_key);

        $projects = Cache::remember($cache_key, 10, function () {
            Log::info('Fetching data from the database');
            return QueryBuilder::for(Project::class)
                ->with(['users', 'tasks'])
                ->allowedFilters('title', AllowedFilter::exact('id'))
                ->allowedSorts('created_at', 'id', 'title')
                ->defaultSort('-created_at')
                ->paginate(request('per_page', 10))
                ->appends(request()->query());
        });
        if (Cache::has($cache_key)) {
            Log::info('Data fetched from cache');
        } else {
            Log::info('Data generated and cached');
        }

        return response(['success' => true, 'data' => $projects]);
    }

    public function show(Project $project)
    {
        $cache_key = 'projects.show.' . $project->id;

        $cached_project = Cache::remember($cache_key, now()->addMinutes(10), function () use ($project) {
            $project->load('users', 'tasks');
            return $project;
        });

        return response(['success' => true, 'data' => $cached_project]);
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        $project = Project::create($validated_data);
        return response(['success' => true, 'data' => $project]);
    }

    public function update(Request $request, Project $project)
    {
        $validated_data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        $project->update($validated_data);
        return response(['success' => true, 'data' => $project]);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response(['data' => $project], Response::HTTP_NO_CONTENT);
    }

    public function export(Request $request)
    {
        $filters = $this->validate($request, [
            'id'       => 'nullable|integer',
            'title'    => 'nullable|string',
            'due_date' => 'nullable|integer|digits:4',
        ]);

        return Excel::download(new ProjectsExport($filters), 'projects.xlsx');
    }
    public function import(Request $request)
    {
        Validator::make($request->all(), [
            'import_file' => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);

        $imported_file = $request->file('import_file');
        Excel::import(new ProjectsImport, $imported_file);
        return response(['success' => true, 'message' => 'Projects imported successfully']);
    }
}
