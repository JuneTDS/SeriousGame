@extends('layouts.auth_layout')

@section('content')

<div>
    <img src="../../assets/images/wannabee_logo.svg" />
    <div class="auth-box">
        <div style="margin-bottom: 37px;">
            <a class="cursor-pointer" href="/login" style="text-align: left; text-decoration: none;">
                <span>
                    <img src="../../assets/images/arrow_back_ios.svg" />
                    <label style="vertical-align: top;">Back</label>
                </span>
            </a>
        </div>

        <h4>Set Email & Password</h4>

        <form action="{{ route('user.register') }}" method="post">
            @csrf
            <div style="margin-bottom: 32px;">
                <label>In-Game Name*</label>
                <input type="text" name="ingame_name" placeHolder="Enter in-game name" value="{{old('ingame_name')}}">
                @error('ingame_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 32px;">
                <label>Full Name</label>
                <input type="text" name="fullname" placeHolder="Enter full name" value="{{old('fullname')}}">
                @error('fullname')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 32px;">
                <label>Personal Email*</label>
                <input type="text" name="email" placeHolder="Example@mail.com" value="{{old('email')}}">
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 32px;">
                <label>Password</label>
                <input type="password" name="password" placeHolder="Minimum 6 characters" value="{{old('password')}}">
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 32px;">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeHolder="Minimum 6 characters">
                @error('confirm_password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 32px;" id="classCodeField">
                <div style="display: flex; flex-direction: column;">
                    <label>Class Code</label>
                    <input type="text" name="class_code" placeHolder="E.g. LOMA123" value="{{old('classcode')}}">
                    @error('classcode')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>

@endsection