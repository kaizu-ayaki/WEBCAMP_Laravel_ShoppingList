@extends('admin.layout')


@section('contets')

<h1>ユーザー一覧</h1>

<table border="1">
        <tr>
            <th>ユーザID
            <th>ユーザ名
            <th>購入した「買うもの」の数

        @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}
            <td>{{ $user->name }}
            <td>{{ $user->comp_num }}
@endforeach

@endsection