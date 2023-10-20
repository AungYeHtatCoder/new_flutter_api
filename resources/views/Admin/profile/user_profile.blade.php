@extends('layouts.admin_app')
@section('content')
<div class="content-header row">
 <div class="content-header-light col-12">
  <div class="row">
   <div class="content-header-left col-md-9 col-12 mb-2">
    <h3 class="content-header-title">Dashboard</h3>
    <div class="row breadcrumbs-top">
     <div class="breadcrumb-wrapper col-12">
      <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="index.html">Home</a>
       </li>
       {{-- <li class="breadcrumb-item"><a href="#">DataTables</a>
       </li> --}}
       <li class="breadcrumb-item active">Dashboard
       </li>
      </ol>
     </div>
    </div>
   </div>
   {{-- <div class="content-header-right col-md-3 col-12">
    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
     <button class="btn btn-primary round dropdown-toggle dropdown-menu-right box-shadow-2 px-2 mb-1" id="btnGroupDrop1"
      type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
     <div class="dropdown-menu"><a class="dropdown-item" href="component-alerts.html"> Alerts</a><a
       class="dropdown-item" href="material-component-cards.html"> Cards</a><a class="dropdown-item"
       href="component-progress.html"> Progress</a>
      <div class="dropdown-divider"></div><a class="dropdown-item" href="register-with-bg-image.html"> Register</a>
     </div>
    </div>
   </div> --}}
  </div>
 </div>
</div>
<div class="content-overlay"></div>

<section id="user-profile-cards" class="row mt-2">
 <div class="col-12">
  <h4 class="text-uppercase">User Profile Cards</h4>
  <p>User profile cards with border & shadow variant.</p>
 </div>
 <div class="col-xl-4 col-md-6 col-12">
  <div class="card">
   <div class="text-center">
    <div class="card-body">
     <img src="{{ Auth::user()->profile }}" class="rounded-circle" width="150px"
      height="150px" alt="Card image">
    </div>
    <div class="card-body">
     <h4 class="card-title">@if(Auth::check())
      {{ Auth::user()->name }}
      @else
      Welcome, Guest
      @endif</h4>
     <h6 class="card-subtitle text-muted">Technical Lead</h6>
    </div>
    <div class="card-body">
     <!-- <button type="button" class="btn btn-danger mr-1"><i class="la la-plus"></i> Follow</button>
     <button type="button" class="btn btn-primary mr-1"><i class="ft-user"></i> Profile</button> -->

     <!-- Profile Photo Update Form -->
     <div class="card-body">
      <form action="{{ route('admin.profiles.update', Auth::user()->id) }}" method="post" enctype="multipart/form-data">
       @csrf
       @method('PUT')
       <div class="form-group">
        <label for="photo">Change Profile Photo</label>
        <input type="file" id="profile" class="form-control" name="profile">
       </div>
       <button type="submit" class="btn btn-primary">Change Profile Photo</button>
      </form>
     </div>
     <!-- end form -->

    </div>
   </div>
   <div class="list-group list-group-flush">
    <div class="card">
     <div class="card-header">
      <h4 class="card-title">Change Phone & Address</h4>
     </div>
     <div class="card-body">
      <form action="{{ route('admin.changePhoneAddress', Auth::user()->id) }}" method="post">
       @csrf
       @method('PUT')
       <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" id="phone" class="form-control" name="phone" value="{{ Auth::user()->phone }}">
        @error('phone')
        <span class="invalid-feedback" role="alert">
         <strong>{{ $message }}</strong>
        </span>
        @enderror
       </div>
       <div class="form-group">
        <label for="address">Address</label>
        <input type="text" id="address" class="form-control" name="address" value="{{ Auth::user()->address }}">

        @error('address')
        <span class="invalid-feedback" role="alert">
         <strong>{{ $message }}</strong>
        </span>
        @enderror
       </div>
       <button type="submit" class="btn btn-primary">Change Phone & Address</button>
      </form>
     </div>
    </div>
   </div>
  </div>
 </div>
 <!-- next card -->
 <div class="col-xl-4 col-md-6 col-12">
  <div class="card card border-teal border-lighten-2">
   <div class="card-body">
    <h4 class="card-title">Customer Information</h4>
    <div class="list-group list-group-flush">
     <a href="#" class="list-group-item"><i class="la la-briefcase"></i>Customer : {{ Auth::user()->name }}</a>
     <a href="#" class="list-group-item"><i class="ft-mail"></i> Email : {{ Auth::user()->email }}</a>
     <a href="#" class="list-group-item"><i class="ft-check-square"></i> Phone :
      @if(Auth::user()->phone == null)
      <span class="badge badge-danger">Not Set</span>
      @else
      {{ Auth::user()->phone }}
      @endif
     </a>
     <a href="#" class="list-group-item"> <i class="ft-message-square"></i> Address :
      @if(Auth::user()->address == null)
      <span class="badge badge-danger">Not Set</span>
      @else
      {{ Auth::user()->address }}
      @endif
     </a>
     <!-- daily point -->
     <a href="#" class="list-group-item"> <i class="ft-message-square"></i> Daily Point :
      @if($user)
      <span class="badge badge-danger">
       {{ $user->points }}
      </span>
      @else
      <p>
       <span class="badge badge-danger">
        0
       </span>
      </p>
      @endif
     </a>
     <a href="#" class="list-group-item"> <i class="ft-message-square"></i> Last Login :
      @if($user)
      <span class="badge badge-danger">
       <strong>Last Login: </strong>
       {{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('Y-m-d H:i:s') : 'Never' }}
      </span>
      @else
      <p>
       <span class="badge badge-danger">
        Never
       </span>
      </p>
      @endif
     </a>
    </div>
   </div>
   <div class="text-center">
    <h4 class="card-title badge badge-primary">Change Password</h4>

    <!-- <div class="card-body">
     <img src="../../../app-assets/images/portrait/small/avatar-s-5.png" class="rounded-circle  height-150"
      alt="Card image">
    </div> -->

    <div class="card-body">
     <!-- change password form - old_password / new_password -->

     <form action="{{ route('admin.changePassword', Auth::user()->id) }}" method="post">
      @csrf
      @method('PUT')
      <div class="form-group">
       <label for="old_password">Old Password</label>
       <input type="password" id="old_password" class="form-control" name="old_password">
       @error('old_password')
       <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
       </span>
       @enderror
      </div>
      <div class="form-group">
       <label for="password">New Password</label>
       <input type="password" id="password" class="form-control" name="password">

       @error('password')
       <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
       </span>
       @enderror
      </div>
      <div class="form-group">
       <label for="password_confirmation">Confirm New Password</label>
       <input type="password" id="password_confirmation" class="form-control" name="password_confirmation">
      </div>
      <button type="submit" class="btn btn-primary">Change Password</button>

     </form>

    </div>
   </div>


  </div>
 </div>
 </div>
 @include('sweetalert::alert')

</section>
@endsection