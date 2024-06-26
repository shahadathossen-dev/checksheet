<table class="table">
    <tbody>
        <tr>
            <td colspan="6" class="text-center">
                <h1>Delegates Export</h1>
            </td>
        </tr>
        <tr>
            <td>#</td>
            <td>Date</td>
            <td>Name</td>
            <td>Phone</td>
            <td>Email</td>
            <td>Status</td>
        </tr>

        @foreach ($models as $model)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $model->createdAt }}</td>
            <td>{{ $model->name }}</td>
            <td>{{ $model->phone }}</td>
            <td>{{ $model->email}}</td>
            <td>{{Str::upper($model->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


