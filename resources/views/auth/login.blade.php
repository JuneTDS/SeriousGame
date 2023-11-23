@extends('layouts.auth_layout')

@section('content')

<div>
    <img src="../../assets/images/wannabee_logo.svg" />
    <div class="auth-box">
        <h4>Log In</h4>

        <form action="{{ route('user.login') }}" method="post">
            @csrf
            <div style="margin-bottom: 32px;">
                <label>Email Address <span class="require-span">*</span></label>
                <input type="text" name="email" placeHolder="Example@mail.com" value="{{old('email')}}">
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 32px;">
                <label>Password <span class="require-span">*</span></label>
                <input type="password" name="password" placeHolder="Minimum 6 characters">
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Class Code Field (Start)-->
            <div style="margin-bottom: 32px;">
                <span>
                    <input type="checkbox" name="showClassCode" id="showClassCode">
                    <label for="showClassCode">Join Class</label>
                </span>
            </div>

            <div class="form-group" style="margin-bottom: 32px;" id="classCodeField" style="display: none;">
                <div style="display: flex; flex-direction: column;">
                    <label>Class Code</label>
                    <input type="text" name="class_code" placeHolder="E.g. LOMA123" value="{{old('classcode')}}">
                    @error('classcode')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <!-- Class Code Field (End)-->

            <div style="margin-bottom: 40px;">
                <span>
                    <input type="checkbox" name="remember" id="remember-me">
                    <label for="remember-me">Remember Me</label>
                </span>
            </div>

            <div>
                <button type="submit">Log In</button>
                @if(isset($message))
                    <div class="alert alert-danger">{{ $message }}</div>
                @endif
            </div>
        </form>

        

        <div style="margin-top: 0px;">
            <label for="" class="text-center">Logging in as a student? <a href="/register" style="text-decoration: none;display: contents;">Click Here</a></label>
        </div>
    </div>
</div>

<script>
    const showClassCodeCheckbox = document.getElementById('showClassCode');
    const classCodeField = document.getElementById('classCodeField');

    classCodeField.style.display = 'none'; // Hide by default

    showClassCodeCheckbox.addEventListener('change', () => {
        classCodeField.style.display = showClassCodeCheckbox.checked ? 'block' : 'none';
    });
</script>
@endsection