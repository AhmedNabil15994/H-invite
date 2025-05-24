<style class="u-style">
    .u-section-2 {background-image: none;padding: 0;}
    .u-section-2 .u-image-1 {background-position: 50% 50%;border-radius: 8px;background-size: contain;background-repeat: no-repeat;}
    .u-section-2 .u-container-layout-1 {padding: 30px}
    .u-section-2 .u-container-layout-2 {padding: 30px}
    .u-section-2 .u-text-1 {text-transform: uppercase; font-weight: 300; letter-spacing: 2px; font-size: 1.5rem; margin: 0}
    .u-section-2 .u-text-2 {margin-bottom: 30px;}
    .u-section-2 .u-btn-1 {background-image: none; color: inherit; text-transform: uppercase; letter-spacing: 2px; font-size: 1.5rem; margin: 49px auto 0}
    @media (max-width: 1199px){ .u-section-2 {min-height: 110px}
        .u-section-2 .u-layout-wrap-1 {position: relative; margin-right: initial; margin-left: initial}

    @media (max-width: 991px){
        .u-section-2 .u-text-2 {font-size: 3.75rem} }
    @media (max-width: 767px){
        .u-section-2 .u-container-layout-1 {padding-left: 10px; padding-right: 10px}
        .u-section-2 .u-container-layout-2 {padding-left: 10px; padding-right: 10px}
        .u-section-2 .u-text-1 {font-size: 2.25rem}
        .u-section-2 .u-text-2 {font-size: 6rem} }
    @media (max-width: 575px){
        .u-section-2 .u-text-1 {font-size: 1.25rem}
        .u-section-2 .u-text-2 {font-size: 3.75rem}
    }
</style>
<style>
    .invitation{
        overflow: hidden;
        display: block;
        margin: auto;
        position: relative;
        height: 880px;
        width: 560px;
        background-repeat: no-repeat;
        background-size: cover;
    }
    .qrImage{
        border: 2px solid #000;
        border-radius: 5px;
        background: #FFF;
        padding: 10px;
        width: max-content;
        cursor: pointer;
        position: absolute;
        top: {{json_decode($party->dimensions)?->dist_y}}px;
        left: {{json_decode($party->dimensions)?->dist_x}}px;
    }
    .invitation .code{
        border: 2px solid #000;
        padding: 0 8px;
        text-align: center;
        margin-top: 5px;
        height: 25px;
        width: {{setting('qr_width')}}px;
    }

    .invitation .actions{
        position: absolute;
        text-align: center;
        margin-top: 5px;
        padding: 3px 8px;
        border: 2px solid #000;
        background: #fff;
        width: {{setting('qr_width')}}px;
        top: {{json_decode($party->dimensions)?->dist_y2}}px;
        left: {{json_decode($party->dimensions)?->dist_x2}}px;
    }
    .invitation .actions img{
        width: 50px;
        cursor: pointer;
    }
    .invitation .actions p{
        margin: 0;
        margin-top: 10px;
        font-weight: bold;
        font-size: 15px;
    }
</style>
<section class=" col-md-9 u-clearfix u-palette-5-base u-section-2" id="carousel_7318">
    <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
            <div class="u-layout">
                <div class="u-layout-row">
                    <div class="u-container-style u-layout-cell u-right-cell u-size-30 u-layout-cell-2">
                        <div class="u-container-layout u-valign-middle-lg u-valign-middle-md u-valign-middle-sm u-valign-middle-xl u-valign-top-xs u-container-layout-2">
                            <h2 class="u-align-center u-custom-font u-text u-text-1">{{implode(',',$party->invitees()->pluck('name')->toArray())}}</h2>
                            <h1 class="u-align-center u-custom-font u-text u-text-2">{{$party->title}}</h1>
                            <a href="#" class="u-border-2 u-border-grey-dark-1 u-btn u-btn-rectangle u-button-style u-none u-btn-1">{{date('Y-m-d',strtotime($party->start_at))}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="clearfix"></div>
