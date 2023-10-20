<div class="container-fluid post" style="margin-top: 50px">
    <h4 style="text-align: center; color: #31a9e1;font-weight: 800;">
        @isset($search)
        {{ "Search Results of " }}
        @endisset
        "{{ $search ?? "ALL POST" }}"

    </h4>
    <div class="text-center">
        @isset($search)
        <a href="{{ url('/') }}">Back To Home</a>
        @endisset
    </div>

    <div class="container">
        <div class="input-container">
            <form action="{{ url('/search') }}" method="post">
                @csrf
                <i class="fas fa-magnifying-glass search-btn" type="submit" style="cursor: pointer"></i>
                <input type="text" name="search" class="form-control" placeholder="Search" required>
            </form>
        </div>
    </div>

    <div class="row mt-5">
        @foreach ($blogs as $blog)
        <div class="col-lg-4 col-sm-12 mt-5">
            <div class="card">
              <img src="{{ $blog->image }}" alt="" />
              <h6>
                <a class="mb-2 d-block" href="{{ url('/blog-detail/'.$blog->id) }}">{{ $blog->title }} </a>
              </h6>
              <div class="d-flex mb-1">
                <div>
                    <small><i class="fas fa-calendar me-2"></i>{{ $blog->created_at->format('M, j Y') }}</small>
                </div>
                <div class="ms-3">
                    <small><i class="fas fa-user-circle me-2"></i>{{ $blog->users->name }}</small>
                </div>
              </div>
              <p>
                {!! $blog->desc !!}
              </p>
              <div class="row mx-0 mt-3 icon-section">
                <div class="col-7 col-md-7 text-right mt-2">
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
                    <a href="{{ url('/blog-detail/'.$blog->id) }}">
                        <i class="fa-regular fa-comment-dots" style="font-size: 25px;"></i>
                    </a>
                  <span class="vertical-align-top fw-bold">{{ $blog->comments_count }}</span>
                </div>
                <div class="col-5 col-md-4 see-more mt-1">
                  <a href="{{ url('/blog-detail/'.$blog->id) }}" class="btn btn-primary rounded"
                    >See More</a
                  >
                </div>
              </div>
            </div>
          </div>
        @endforeach
    </div>
  </div>

      <!-- Pagination -->
      <div class="mt-5" style="display: flex; justify-content: center">
        {{ $blogs->links() }}
        {{-- <ul class="pagination">
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </li>
        </ul> --}}
      </div>
