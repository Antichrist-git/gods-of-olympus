@extends('layouts.app')
@section('title', 'Коллекции пользователей')
@section('content')
<main id="main" class="site-container main-content"><h1>Коллекции пользователей</h1>
<div class="people-grid">@foreach($users as $user)
<a class="person-tile" href="{{ route('collections.show', $user) }}"><span class="avatar"><i class="fa-solid fa-user" aria-hidden="true"></i></span><strong>{{ $user->name }}</strong><small>Карточек: {{ $user->gods_count }}</small></a>
@endforeach</div></main>
@endsection
