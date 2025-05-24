@extends('apps::Frontend.layouts.app')

@section('title',$category->title)
@push('css')
    <style>
        .imgBanner{
            border-radius: 5px;
            height: 400px;
            display: block;
            margin: auto;
            margin-bottom: 50px;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .imgBanner img{
            width: 100%;
            height: 100%;
        }
        .hidden{
            display: none !important;
        }
    </style>
@endpush
@section( 'content')
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="filters-button-group">
                    @foreach($category->children()->active()->get() as $key => $child)
                    <button class="filter" data-filter=".f{{$child->id}}">
                        <span class="d-block">{{$child->title}}</span>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        @foreach($offers as $key=> $keyOffers)
        @if($keyOffers['banner'] != '' && $keyOffers['banner']->id == $key )
            <div class="section-block ads-grid d-desk {{ array_key_first($offers) == $key ? '' : 'hidden'  }} f{{$key}} {{$keyOffers['banner']->id}}" >
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-12 col-12">
                            <a class="ads-block" href='#'>
                                <div class="img-block">
                                    <img class="img-fluid" src="{{$keyOffers['banner']->getFirstMediaUrl('banners')}}" alt="" />
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-block ads-grid d-mobile {{ array_key_first($offers) == $key ? '' : 'hidden'  }} f{{$key}} {{$keyOffers['banner']->id}}">
                <div class="">
                    <div class="">
                        <div class="item">
                            <a class="ads-block" href='#'>
                                <div class="img-block">
                                    <img class="img-fluid" src="{{$keyOffers['banner']->getFirstMediaUrl('mobile_banners')}}" alt="" />
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @endforeach

        <div class="grid row">
            @foreach($offers as $key=> $keyOffers)
                @foreach($keyOffers['offers'] as $offer)
                <div class="grid-item col-lg-4 col-md-4 col-sm-6 {{ array_key_first($offers) == $key ? '' : 'hidden'  }} f{{$key}} ">
                    @include('offer::frontend.partials.offer-card',['offer'=>$offer])
                </div>
                @endforeach
            @endforeach
        </div>
    </div>

@endsection
@push('js')
    <script>
        $(function (){
            $('.filter.active').click()
            $('.filter').on('click',function (){
                // $($(this).data('filter')).siblings('.ads-grid').addClass('hidden')
                $($(this).data('filter')).removeClass('hidden');
                $('.ads-grid').not($(this).data('filter')).addClass('hidden')
            });
        });
    </script>
@endpush
