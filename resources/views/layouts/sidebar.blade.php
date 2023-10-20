<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
 {{-- @foreach (Auth::user()->roles as $role)
 @if ($role->title === "Admin") --}}
 <li class=""><a href="{{ url('/home') }}"><i class="la la-home"></i><span class="menu-title"
    data-i18n="eCommerce Dashboard">Dashboard</span></a>
 </li>
 {{-- <li class=" navigation-header"><span data-i18n="Ecommerce">Delight</span><i class="la la-ellipsis-h"
   data-toggle="tooltip" data-placement="right" data-original-title="Ecommerce"></i>
 </li>
 <li class=" nav-item"><a href="#"><i class="la la-calendar"></i><span class="menu-title" data-i18n="Shop">My Content
    Calendar</span></a>
 </li>
 <li class=" nav-item"><a href="#"><i class="la la-calendar"></i><span class="menu-title" data-i18n="Shop">Client
    Calendar</span></a>
 </li> --}}

 <li class=" navigation-header"><span data-i18n="User Interface">User Interface</span><i class="la la-ellipsis-h"
   data-toggle="tooltip" data-placement="right" data-original-title="User Interface"></i>
 </li>
 @can('user_management_access')
 <li class=" nav-item">
  <a href="#">
   <i class="la la-server"></i>
   <span class="menu-title" data-i18n="Components">UserManagement</span>
  </a>
  <ul class="menu-content">
   @can('permission_access')
   <li><a class="menu-item" href="{{ route('admin.permissions.index') }}"><i></i><span
      data-i18n="Alerts">Permission</span></a>
   </li>
   @endcan
   @can('role_access')
   <li><a class="menu-item" href="{{ route('admin.roles.index') }}"><i></i><span data-i18n="Callout">Role</span></a>
   </li>
   @endcan
   @can('user_access')
   <li><a class="menu-item" href="{{ route('admin.users.index') }}"><i></i><span data-i18n="Basic Buttons">Users
     </span></a>
   </li>
   @endcan
   @can('user_access')
   <li><a class="menu-item" href="{{ route('admin.logActivity') }}"><i></i><span data-i18n="Basic Buttons">Users
      LogActivities
     </span></a>
   </li>
   @endcan
   <!-- <li><a class="menu-item" href="#"><i></i><span data-i18n="Basic Buttons">Our
      Clients
     </span></a>
   </li>
   <li><a class="menu-item" href="#"><i></i><span data-i18n="Basic Buttons">Our
      Teams
     </span></a>
   </li> -->
  </ul>
 </li>
 @endcan
@can('content_management_access')
 <li class=" nav-item"><a href="#"><i class="la la-clipboard"></i><span class="menu-title"
    data-i18n="Invoice">User Interface</span></a>
  <ul class="menu-content">
   <li><a class="menu-item" href="{{ url('/admin/banners/') }}"><i></i><span data-i18n="Invoice Summary">Ads Banner</span></a>
   </li>
   <li><a class="menu-item" href="{{ url('/admin/blogs/') }}"><i></i><span data-i18n="Invoice Template">Blog</span></a>
   </li>
   </li>
   @endcan
  </ul>
 </li>

</ul>
