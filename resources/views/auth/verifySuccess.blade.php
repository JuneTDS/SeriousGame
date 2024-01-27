@extends('layouts.auth_layout')

@section('content')

<div>
    <img src="../../assets/images/wannabee_logo.svg" />
    <div class="auth-box">
        <div style="margin-bottom: 37px;">
            <a class="cursor-pointer" href="/login" style="text-align: left; text-decoration: none;">
                <span>
                    <img src="../../assets/images/arrow_back_ios.svg" />
                    <label style="vertical-align: top;">Login</label>
                </span>
            </a>
        </div>

        <h4>Verify Success</h4>

        <form>
            <div style="margin-bottom: 32px;">
                <p>Congratulation! your account is ready to use.</p>
            </div>
        </form>
    </div>
</div>

@endsection