@extends('auth.master')
@section('content')
<div class="col-lg-6">
    <div>
        <h4>Welcome Back</h4>
        <p class="login-subtitle">Sign in to manage your store and access your dashboard.</p>
        <form action="{{route('login')}}" method="post">
            @csrf
            @if($errors->any())
                @foreach ($errors->all() as $error)
                    <div>
                        <small class="text-danger">{{ $error }}</small>
                    </div>
                @endforeach
            @endif
            <div class="input-div mt-4">
                <div class="input-i"><i class="fa-solid fa-envelope"></i></div>
                <div>
                    <input type="email" name="email" placeholder="Email address">
                </div>
            </div>
            <div class="input-div">
                <div class="input-i"><i class="fas fa-lock"></i></div>
                <div>
                    <input type="password" name="password" placeholder="Password">
                </div>
            </div>

            <div class="w-100 text-end">
                <a href="#">Forgot Password?</a>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</div>
@endsection
