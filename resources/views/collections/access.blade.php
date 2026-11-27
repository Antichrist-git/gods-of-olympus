@extends('layouts.app')
@section('title', 'Доступ к API')
@section('content')
<main id="main" class="site-container main-content"><h1>Доступ к API</h1>
<p>Создайте персональный токен для запросов к API от своего имени.</p>
<form method="POST" action="{{ route('profile.api-token') }}">@csrf<button class="btn btn-primary">Создать API-токен</button></form>
</main>
@endsection
