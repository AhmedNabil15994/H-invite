<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$number}}</title>
    <style>
        html,body{
            padding:0;
            margin:0;
            page-break-inside: avoid !important;
        }
        .invitation{
            overflow: hidden;
            display: block;
            margin: auto;
            background-image: url("{{$image}}");
            position: relative;
            /*height: 100%;*/
            /*height: 297mm;*/
            /*width: 100%;*/
{{--            height: {{$height}}px;--}}
            /*width: 793px;*/
            width: 148mm;
            height: 234mm;
            {{--width: {{$width}}px;--}}
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
            page-break-inside: avoid;
        }
        .qrImage{
            border: 2px solid #000;
            border-radius: 5px;
            background: #FFF;
            padding: 10px;
            width: max-content;
            cursor: pointer;
            position: absolute;
            top: {{$distY}}px;
            left: {{$distX}}px;
            {{--top: calc({{$distY/$height * 100}}% + 50px);--}}
            {{--left: calc({{$distX/$width * 100}}% + 50px);--}}
        }
        .invitation .code{
            border: 2px solid #000;
            padding: 0 8px;
            text-align: center;
            margin-top: 5px;
            height: 25px;
        }
        .code a{
            text-decoration: none;
            color: #000;
        }
        .invitation .actions{
            position: absolute;
            text-align: center;
            margin-top: 5px;
            padding: 3px 0;
            background: transparent;
            width: {{$qr_width}}px;
            top: {{$distY2}}px;
            left: {{$distX2}}px;
        }
        .invitation .actions p{
            margin: 0;
            margin-top: 15px;
            font-weight: bold;
            font-size: 15px;
            color: #777;
        }
        .actions a{
            border: 1px solid #000;
            border-radius: 20px;
            color: #777;
            background: #FFF;
            margin: 10px 0;
            padding: 6px 0;
            display: block;
            font-weight: bold;
            text-decoration: none;
        }
        .actions a:hover,
        .actions a:active,
        .actions a:focus{
            text-decoration: none;
        }
        /*@page {size: 595px 890px; margin:0!important; padding:0!important}*/
    </style>
</head>
<body>
    <div class="invitation">
        <div class="qrImage" id="draggable">
            <img src="{{$qr}}" alt="qrImage">
            <div class="code" id="draggable2">
                <b>#{{$number}}</b>
            </div>
        </div>
        <div class="actions" id="draggable3">
            <a href="{{$url}}" target="_blank">أضغط هنا</a>
            <p>{{ __('party::dashboard.parties.form.accept_or_refuse') }}</p>
        </div>
    </div>
</body>
</html>
