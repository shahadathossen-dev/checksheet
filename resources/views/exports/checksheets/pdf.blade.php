@extends('layouts.export')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th colspan="6" class="header">
                    <h1 class="">Check Sheet Export</h1>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>#</th>
                <th>Due Date</th>
                <th>Title</th>
                <th>Description</th>
                <th>Assignee</th>
                <th>Author</th>
                <th>Status</th>
            </tr>

            @foreach ($models as $model)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $model->dueDate }}</td>
                <td>{{ $model->title }}</td>
                <td>{{ $model->description }}</td>
                <td>{{ $model->assignee->name}}</td>
                <td>{{ $model->author->name}}</td>
                <td>{{ Str::upper($model->status) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
