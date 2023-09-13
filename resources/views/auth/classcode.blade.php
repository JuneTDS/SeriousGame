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

        <h4>Enter Class Code</h4>

        <form action="{{ route('user.classcode') }}" method="post">
            @csrf
            <div style="margin-bottom: 32px;">
                <label>Class Code</label>
                <input type="text" name="classcode" placeHolder="E.g. LOMA123" value="{{old('classcode')}}">
                @error('classcode')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <button type="submit">Continue</button>
                @if(isset($message))
                    <div class="alert alert-danger">{{ $message }}</div>
                @endif
            </div>
        </form>
    </div>
</div>

@endsection