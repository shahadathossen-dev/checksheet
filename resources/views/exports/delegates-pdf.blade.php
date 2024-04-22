@extends('layouts.export')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th colspan="6" class="header">
                    <h1 class="">Delegates Export</h1>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Id</th>
                <th>Date</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
            </tr>

            @foreach ($models as $model)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $model->createdAt }}</td>
                <td>{{ $model->name }}</td>
                <td>{{ $model->phone }}</td>
                <td>{{ $model->email}}</td>
                <td>{{ Str::upper($model->status) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
