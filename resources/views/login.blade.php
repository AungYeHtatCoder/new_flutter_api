@extends('user_layouts.master')


@section('content')
<div class="row d-flex justify-content-center pt-5 pb-4">
    <div class="col-md-6">
      <div class="container login-card">
        <div class="mt-5">
          <a href="#" class="logo">Delight <span>Myanmar</span></a>
        </div>
        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="input-name">
                <i class="fas fa-envelope material-icons me-2"></i>
                {{-- <span class="material-icons fa-bold">mail</span> --}}
                <input type="email" name="email" placeholder="Enter Email" />
              </div>
              @error('email')
              <span class="text-danger">*{{ $message }}</span>
              @enderror
              <div class="input-name">
                {{-- <span class="material-icons fa-bold">lock</span> --}}
                <i class="fas fa-lock material-icons me-2"></i>
                <input
                  type="password"
                  name="password"
                  placeholder="Enter Password"
                />
              </div>
              @error('password')
              <span class="text-danger">*{{ $message }}</span>
              @enderror
              {{-- <div class="forgot-pw">
                <a href="">Forgot Password?</a>
              </div> --}}
              <button class="btn btn-outline-primary mt-5 login-btn" type="submit">
                Login
              </button>
              <div class="mt-2">
                <p style="font-size: 14px">
                  If you haven't account please <a href="{{ route('register') }}">SignUp</a>
                </p>
              </div>
        </form>

      </div>
    </div>
  </div>
@endsection
