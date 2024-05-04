<table class="table">
    <thead>
        <tr>
            <th colspan="7" class="header">
                <h1>Task List Export</h1>
            </th>
        </tr>
        <tr>
            <th>Submit Date</th>
            <th>Title</th>
            <th>Assignee</th>
            <th>Due Date</th>
            <th>Author</th>
            <th>Type</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($models as $model)
        <tr>
            <td>{{ $model->submit_date }}</td>
            <td>{{ $model->checksheet->title }}</td>
            <td>{{ $model->assignee->name}}</td>
            <td>{{ $model->due_date }}</td>
            <td>{{ $model->author?->name}}</td>
            <td style="text-transform: capitalize;">{{ $model->type }}</td>
            <td style="text-transform: capitalize;">{{ $model->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


