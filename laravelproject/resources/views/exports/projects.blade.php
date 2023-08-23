<table>
    <thead>
        <tr>
            <th>Project Name</th>
            <th>Task Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($projects as $project)
        <tr>
            <td>{{ $project->title }}</td>
            <td>
                @foreach ($project->tasks as $task)
                {{ $task->title }}<br>
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>