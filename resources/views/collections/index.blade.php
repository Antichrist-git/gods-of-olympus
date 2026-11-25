@extends('layouts.app')
@section('title', 'Коллекции пользователей')
@section('content')
    <main id="main" class="container py-5">
        <h1>Коллекции пользователей</h1>
        <ul class="list-group mt-4">
            @foreach ($users as $user)
                <li class="list-group-item">
                    <a href="{{ route('collections.show', $user) }}">{{ $user->name }}</a>
                </li>
            @endforeach
        </ul>
    </main>
@endsection
