@extends('layouts.app')
@section('title', 'Вход')
@section('content')
    <main id="main" class="container py-5">
        <div class="col-md-7 col-lg-5 mx-auto">
            <h1>Вход</h1>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="email">Электронная почта</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Пароль</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-primary">Войти</button>
                <a class="ms-2" href="{{ route('register') }}">Создать аккаунт</a>
            </form>
        </div>
    </main>
@endsection
