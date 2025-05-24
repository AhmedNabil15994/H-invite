@extends('apps::Frontend.layouts.app')
@push('css')
    <style>
        nav[role="navigation"]{
            display: none !important;
        }
        .relative svg{
            width: 50px;
            height: 25px;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="grid row">
            @foreach($offers as $offer)
                <div class="grid-item col-lg-4 col-md-4 col-sm-6 f1 f11 f21 f31 f41 f51">
                    @include('offer::frontend.partials.offer-card',['offer'=>$offer])
                </div>
            @endforeach
            {!! $offers->render() !!}
        </div>

    </div>

@endsection
