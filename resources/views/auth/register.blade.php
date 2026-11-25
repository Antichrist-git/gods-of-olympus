@extends('layouts.app')
@section('title', 'Регистрация')
@section('content')
    <main id="main" class="container py-5">
        <div class="col-md-7 col-lg-5 mx-auto">
            <h1>Регистрация</h1>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Имя</label>
                    <input id="name" name="name" type="text" autocomplete="name" required
                           value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="username">Имя в адресе коллекции</label>
                    <input id="username" name="username" autocomplete="username" required maxlength="40"
                           pattern="[a-z][a-z0-9_\-]{2,39}" value="{{ old('username') }}"
                           class="form-control @error('username') is-invalid @enderror">
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <small>От 3 до 40 символов. Начните с латинской буквы в нижнем регистре. Можно добавить цифры, дефис и подчёркивание.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">Электронная почта</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Пароль</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password_confirmation">Повторите пароль</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                           class="form-control @error('password_confirmation') is-invalid @enderror">
                    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                <a class="ms-2" href="{{ route('login') }}">Уже есть аккаунт?</a>
            </form>
        </div>
    </main>
@endsection
