<table class="table">
    <tbody>
        <tr>
            <td colspan="6" class="text-center">
                <h1>Check Sheet Export</h1>
            </td>
        </tr>
        <tr>
            <td>#</td>
            <td>Due Date</td>
            <td>Title</td>
            <td>Description</td>
            <td>Assignee</td>
            <td>Author</td>
            <td>Type</td>
        </tr>

        @foreach ($models as $model)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $model->dueDate }}</td>
            <td>{{ $model->title }}</td>
            <td>{{ $model->description }}</td>
            <td>{{ $model->assignee->name}}</td>
            <td>{{ $model->author->name}}</td>
            <td>{{Str::upper($model->type) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


