@extends('layouts.export', ['title' => 'Export Tasklist'])

@section('content')
<table class="table">
    <thead>
        <tr>
            <th colspan="7" class="header">
                <h1>Task List Export</h1>
            </th>
        </tr>
        <tr>
            <th style="width: 12%">Submit Date</th>
            <th style="width: 20%">Title</th>
            <th style="width: 15%">Assignee</th>
            <th style="width: 12%">Due Date</th>
            <th style="width: 15%">Author</th>
            <th style="width: 8%">Type</th>
            <th style="width: 8%">Status</th>
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
@endsection
