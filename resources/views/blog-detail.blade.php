@extends('user_layouts.master')

@section('content')
<div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="card detail-box">
          <div class="img-post my-3">
            <img src="{{ $blog->image }}" class="card-img-top w-100" alt="" />
          </div>
          <div class="post-title">
            <h4>{{ $blog->title }}</h4>
            <div class="d-flex">
                <div>
                    <small><i class="fas fa-calendar me-2" style="color: #37507E;"></i>{{ $blog->created_at->format('M j, Y') }}</small>
                </div>
                <div class="ms-3">
                    <small><i class="fas fa-user-circle me-2" style="color: #37507E;"></i>{{ $blog->users->name }}</small>
                </div>
            </div>


          </div>
          <div class="post-icon my-3">
            @auth
                @php
                    $user_like = App\Models\Admin\Like::where('user_id', Auth::user()->id)->where('blog_id', $blog->id)->first();
                @endphp
                <a href="" onclick="event.preventDefault(); document.getElementById('like{{ $blog->id }}').submit();">
                    <i class="fa-{{ $user_like ? "solid" : "regular" }} fa-heart" style="font-size: 25px;"></i>
                </a>
                <form id="like{{ $blog->id }}" action="{{ url('/like/'.$blog->id) }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endauth
        @guest
        <a href="{{ route('login') }}">
            <i class="fa-regular fa-heart" style="font-size: 25px;"></i>
        </a>
        @endguest
        <span class="vertical-align-top fw-bold me-3">{{ $blog->likes_count }}</span>
            <a href="" style="padding-left: 15px">
                <i class="fa-regular fa-comment-dots" style="font-size: 25px;"></i>
            </a>
            <span class="vertical-align-top fw-bold me-3">{{ $blog->comments_count }}</span>
          </div>
          <div class="desp">
            <p>
              {!! $blog->description !!}
            </p>
          </div>
        </div>
        <div class="card">
          <div class="header">
            <h5>Comments {{ $blog->comments_count }}</h5>
          </div>
          <div class="body">
            <ul class="comment-reply list-unstyled">
                @foreach ($comments as $comment)
                    <li class="row clearfix mt-2">
                        <div class=" col-md-2 col-4">
                        <img class="rounded-circle" width="90px" height="90px" src="{{ $comment->users->profile }}" alt="Awesome Image" />
                        </div>
                        <div class="text-box col-md-10 col-8 p-l-0 p-r0">
                          <div class="d-flex">
                            <h5 class="">{{ $comment->users->name}}</h5>
                            @auth
                                @if ($comment->user_id === Auth::user()->id)
                                    <div>
                                    <div class="btn-group">
                                        <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical text-secondary"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                        <li class="dropdown-item">
                                            <button class="btn btn-sm text-success edit" data-id="{{ $comment->id }}" data-comment="{{ $comment->comment }}" data-bs-toggle="modal" data-bs-target="#editComment">
                                            <i class="fas fa-pen-to-square "></i>
                                            Edit
                                            </button>
                                        </li>
                                        <li class="dropdown-item">
                                            <button class="btn btn-sm text-danger delete" data-id="{{ $comment->id }}" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                            </button>
                                        </li>
                                        </ul>
                                    </div>
                                    </div>
                                @endif
                            @endauth

                          </div>

                          <p>
                              {{ $comment->comment }}
                          </p>
                          <ul class="list-inline">
                              <li><a href="javascript:void(0);">{{ $comment->created_at->format('M, j Y') }}</a></li>
                          </ul>
                        </div>
                    </li>
                    <hr />
                @endforeach
            </ul>
          </div>
        </div>
        <div class="card">
          <div class="header">
            <h4>
              Send Your Comment
            </h4>
          </div>
          <div class="body">
            <div class="comment-form">
              <form class="row clearfix" method="POST" action="{{ url('/comment/create/'.$blog->id) }}">
                @csrf
                <div class="col-sm-12 mt-3">
                  <div class="form-group">
                    <textarea
                      rows="4"
                      class="form-control no-resize"
                      placeholder="Please type what you want..."
                      name="comment"
                    ></textarea>
                    @error('comment')
                    <span class="text-danger">*{{ $message }}</span>
                    @enderror
                  </div>
                  <button
                    type="submit"
                    class="btn btn-block btn-primary mt-3"
                  >
                    SUBMIT
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      @auth
      <div class="col-md-4">
        <div class="card p-4 user">
          <div
            class="image d-flex flex-column justify-content-center align-items-center"
          >
            <div class="">
              <img src="{{ Auth::user()->profile }}" height="100" width="100" />
            </div>
            <span class="name mt-3">{{ Auth::user()->name }}</span>
            {{-- <span class="id">@emilly</span> --}}

            <div class="d-flex mt-2">
                <a href="{{ url('/home') }}">Edit Profile</a>
              {{-- <button class="btn btn-primary"></button> --}}
            </div>

            <div class="px-2 rounded mt-4 date">
              <span class="join">Joined {{ Auth::user()->created_at->format("M, j Y") }}</span>
            </div>
          </div>
        </div>
      </div>
      @endauth
    </div>
  </div>


<!-- Modal -->
<div class="modal fade" id="editComment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Comment</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ url('/comment/edit/') }}" method="post">
                @csrf
                <div class="mb-3">
                    <input type="hidden" name="id" id="edit_id">
                    <textarea name="comment" id="comment" cols="30" rows="5" class="form-control"></textarea>
                </div>
                <div class="text-end">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-pen-to-square me-2"></i>Edit</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <i class="fas fa-warning text-danger fa-2x mb-3"></i>
          <p class="modal-title fs-5" id="deleteModalLabel">Are you sure "Delete"?</p>
          <span class="badge badge-danger">*All chapters in the Book will be removed!.</span>
        </div>
        <div class="modal-footer m-auto">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fas fa-xmark me-2"></i>Cancle</button>
          <form action="{{ url('/comment/delete/') }}" method="post">
            @csrf
            <input type="hidden" name="id" id="delete_id" value="">
            <button class="btn btn-success" type="submit"><i class="fas fa-check me-2"></i>Confirm</button>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $(".edit").click(function(){
            $id = $(this).data('id');
            $comment = $(this).data('comment');

            $("#edit_id").val($id);
            $("#comment").val($comment);
        });
        $(".delete").click(function(){
            $id = $(this).data('id');
            $("#delete_id").val($id);
        })
    });
</script>
@endsection
