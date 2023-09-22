@extends('layouts.layout')

@section('content')

<div class="container page my-profile-page">
    <h3 class="page-header">My Profile</h3>
    <form action="{{ route('profile.update') }}" class="form" method="post">
        @csrf
        <div style="margin-bottom: 32px;">
            <label>Full Name</label>
            <input type="text" name="username" data-class="username" value="{{(old('username') !== null) ? old('username') : $user->username}}" />
            <input type="hidden" id="username" value="{{ $user->username }}" />
            <input type="hidden" class="check-edit" id="change-username" value="false" />
        </div>

        <div style="margin-bottom: 32px;">
            <label>Email Address</label>
            <input type="text" name="email" data-class="email" value="{{(old('email') !== null) ? old('email') : $user->email}}" />
            <input type="hidden" id="email" value="{{ $user->email }}" />
            <input type="hidden" class="check-edit" id="change-email" value="false" />
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 32px;">
            <label>Password</label>
            <input type="password" name="password" data-class="password" placeHolder="Minimum 6 characters">
            <input type="hidden" id="password" value="" />
            <input type="hidden" id="change-password" class="check-edit" value="false" />
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <button type="submit" class="update-btn disabled">Update</button>
        </div>
    </form>

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
</div>

<script>
    $(document).ready(function() {
        $("input").keyup(function(){
            triggerUpdate($(this))
        });
        $("input").keydown(function(){
            triggerUpdate($(this))
        });
    });

    function triggerUpdate(ele) {
        setTimeout(() => {
            let updatedValue = $(ele).val();
            let dbValue = $("#"+$(ele).attr("data-class")).val();

            if (updatedValue != dbValue) {
                $("#change-"+$(ele).attr("data-class")).val("true");
            } else {
                $("#change-"+$(ele).attr("data-class")).val("false");
            }

            let flag = false;
            $(".check-edit").each(function() {
                if ($(this).val() == "true") {
                    flag = true;
                }
            });

            if (flag) {
                $(".update-btn").removeClass("disabled")
            } else {
                $(".update-btn").addClass("disabled")
            }
        }, 10);
    }
</script>

@endsection