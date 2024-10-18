@section('styles')
<link href="{{ url('css/carousel.css') }}" rel="stylesheet">
@endsection

<div id="carouselExampleIndicators" class="carousel slide">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    @foreach($images as $index => $image)
        @if($index > 0)
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
        @endif
    @endforeach
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="{{ asset('post/'.$images[0]) }}" class="d-block w-100" alt="...">
    </div>
    @foreach($images as $index => $image)
        @if($index > 0)
            <div class="carousel-item">
                <img src="{{ asset('post/'.$image) }}" class="d-block w-100" alt="...">
            </div>
        @endif
    @endforeach
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>