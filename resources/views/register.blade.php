@extends('user_layouts.master')


@section('content')
<div class="row d-flex justify-content-center" style="padding-top: 90px;">
    <div class="col-md-6">
      <div class="container register-card">
        <div class="mt-5">
          <a href="#" class="logo">Delight <span>Myanmar</span></a>
        </div>
        <form action="{{ route('register') }}" method="post">
            @csrf
            <div class="input-name">
                <span class="material-icons fa-bold">person</span>
                <input type="text" name="name" placeholder="Enter User Name" />
              </div>
              @error('name')
              <span class="text-danger">*{{ $message }}</span>
              @enderror
              <div class="input-name">
                <span class="material-icons fa-bold">mail</span>
                <input type="email" name="email" placeholder="Enter Email" />
              </div>
              @error('email')
              <span class="text-danger">*{{ $message }}</span>
              @enderror
              <div class="input-name">
                <span class="material-icons fa-bold">lock</span>
                <input
                  type="password"
                  name="password"
                  placeholder="Enter Password"
                />
              </div>
              @error('password')
              <span class="text-danger">*{{ $message }}</span>
              @enderror
              <div class="input-name">
                <span class="material-icons fa-bold">lock</span>
                <input
                  type="password"
                  name="password_confirmation"
                  placeholder="Enter Confirm Password"
                />
              </div>
              @error('password_confirmation')
              <span class="text-danger">*{{ $message }}</span>
              @enderror
              <button class="btn btn-outline-primary mt-3 login-btn" type="submit">
                Register
              </button>
              <div class="mt-2">
                <p style="font-size: 14px">
                  If you already have an account <a href="{{ route('login') }}">Login</a>
                </p>
              </div>
        </form>

      </div>
    </div>
  </div>
@endsection

