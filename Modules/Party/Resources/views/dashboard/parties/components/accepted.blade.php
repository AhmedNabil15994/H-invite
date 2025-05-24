<!DOCTYPE html>
<html dir="rtl">
    <head>
        <meta charset="utf-8" />
        <title>{{ setting('app_name',locale()) }}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{__('party::dashboard.parties.statistics.title')}}</title>
        <link href="https://fonts.googleapis.com/css?family=Cairo" rel="stylesheet" type="text/css"/>
        <link href="{{asset('/admin/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/admin/assets/global/plugins/bootstrap/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css"/>
        <style>
            body{font-family: 'Cairo',Sans-Serif;background: #f9f9f9}
            .primary-color{
                background: #4d78e7;
                color: #FFF;
            }
            p.primary-color{
                width: 200px;
                margin: auto;
                padding: 10px;
                border-radius: 5px;
                font-size: 18px;
            }
            .primary-color span{
                border-right: 1px solid #fff;
                text-align: center;
                width: 50px;
                display: inline-block;
                float: left;
            }
            a.primary-color,
            a.primary-color:hover,
            a.primary-color:active,
            a.primary-color:focus{
                cursor: pointer;
                color: #fff;
                display: block;
                width: 100px;
                text-align: center;
                border-radius: 5px;
                padding: 10px;
                margin: auto;
                text-decoration: none;
            }
            .pd-20{
                padding: 5px 20px;
            }
            .mt-30{
                margin-top: 30px;
            }
            .message{
                width: 80%;
                margin: auto;
                color: #4d78e7;
                font-size: 20px;
                border: 1px solid #999;
                padding: 10px;
                border-radius: 10px;
                margin-top: 40px;
            }
            .message i{
                font-size: 40px;
                border: 1px solid #ccc;
                border-radius: 50%;
                padding: 10px;
                margin-bottom: 20px;
                position: absolute;
                top: 10px;
                left: 150px;
                z-index: 9999;
                background: #999;
            }
            .message p{
                margin-top: 40px;
            }
            .mt-20{
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1 class="text-center" style="margin-bottom: 25px">حياكم الله</h1>
            <h1 class="text-center" style="margin-bottom: 25px">{{$inviteeContact->display_name}}</h1>

            <p class="primary-color">اجمالي الدعوات  <span>{{$count ?? 0}}</span></p>
            <div class="col-xs-12 text-center">
                @if($count)
{{--                <div class="row pd-20 mt-30">--}}
{{--                    <a href="{{URL::current().'/download'}}" target="_blank" class="primary-color">تحميل الدعوات المقبولة والمرفوضة</a>--}}
{{--                </div>--}}
                <div class="row mt-20" style="position:relative;">
                    <div class="message">
                        <i class="fa fa-check"></i>
                        <p>تم تأكيد الحضور</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <script src="{{asset('/admin/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
        <script></script>
    </body>
</html>
