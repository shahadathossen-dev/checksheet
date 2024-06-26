<table class="table">
    <thead>
        <tr>
            <th colspan="7" class="header">
                <h1>Check Sheet Export</h1>
            </th>
        </tr>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Description</th>
            <th>Type</th>
            <th>Due By</th>
            <th>Assignee</th>
            <th>Author</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($models as $model)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $model->title }}</td>
            <td>{{ $model->description }}</td>
            <td style="text-transform: capitalize;">{{ $model->type }}</td>
            <td>{{ $model->due_by }}</td>
            <td>{{ $model->assignee->name}}</td>
            <td>{{ $model->author?->name}}</td>
        </tr>
        @endforeach
    </tbody>
</table>


