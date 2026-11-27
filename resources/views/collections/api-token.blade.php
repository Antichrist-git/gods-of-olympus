@extends('layouts.app')
@section('title', 'API-токен')
@section('content')
    <main id="main" class="container py-5">
        <h1>API-токен</h1>
        <label for="api-token">Скопируйте токен для Postman. Он показывается один раз.</label>
        <textarea id="api-token" class="form-control my-3" rows="5" readonly>{{ $token }}</textarea>
        <a href="{{ route('collections.show', auth()->user()) }}">Вернуться в профиль</a>
    </main>
@endsection
