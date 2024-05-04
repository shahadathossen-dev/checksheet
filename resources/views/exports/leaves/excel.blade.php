<table class="table">
    <thead>
        <tr>
            <th colspan="7" class="header">
                <h1>Leaves Export</h1>
            </th>
        </tr>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Description</th>
            <th>User</th>
            <th>Date</th>
            <th>Approver</th>
            <th>Type</th>
            <!-- <th>Status</th> -->
        </tr>
    </thead>
    <tbody>
        @foreach ($models as $model)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $model->title }}</td>
            <td>{{ $model->description }}</td>
            <td>{{ $model->user->name}}</td>
            <td>{{ $model->startDate }}</td>
            <td>{{ $model->approver?->name}}</td>
            <td style="text-transform: capitalize;">{{ $model->type }}</td>
            <!-- <td style="text-transform: capitalize;">{{ $model->status }}</td> -->
        </tr>
        @endforeach
    </tbody>
</table>


