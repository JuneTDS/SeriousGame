@extends('layouts.auth_layout')

@section('content')

<div>
    <img src="../../assets/images/wannabee_logo.svg" />
    <div class="auth-box">
        <h4>Log In</h4>

        <form action="{{ route('user.login') }}" method="post">
            @csrf
            <div style="margin-bottom: 32px;">
                <label>Email Address *</label>
                <input type="text" name="email" placeHolder="Example@mail.com" value="{{old('email')}}">
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 32px;">
                <label>Password *</label>
                <input type="password" name="password" placeHolder="Minimum 6 characters">
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <button type="submit">Log In</button>
                @if(isset($message))
                    <div class="alert alert-danger">{{ $message }}</div>
                @endif
            </div>

            <div>
                <span>
                    <input type="checkbox" name="remember" id="remember-me">
                    <label for="remember-me">Remember Me</label>
                </span>
            </div>
        </form>

        

        <div style="margin-top: 80px;">
            <label for="" class="text-center">Logging in as a student? <a href="/classcode" style="text-decoration: none;">Click Here</a></label>
        </div>
    </div>
</div>

@endsection