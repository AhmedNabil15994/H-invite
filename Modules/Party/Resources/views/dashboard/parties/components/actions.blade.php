<!DOCTYPE html>
<html dir="rtl">
    <head>
        <meta charset="utf-8" />
        <title>Actions || {{ setting('app_name',locale()) }}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{__('party::dashboard.parties.statistics.title')}}</title>
        <link href="https://fonts.googleapis.com/css?family=Cairo" rel="stylesheet" type="text/css"/>
        <link href="{{asset('/admin/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/admin/assets/global/plugins/bootstrap/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css"/>
        <style>
            body{font-family: 'Cairo',Sans-Serif;}
            .u-clearfix.col-md-9{
                display: block;width: 100%;
            }
            .actionsRow{
                padding-bottom: 20px;
                border-bottom: 1px solid #ddd;
            }
            .actionsRow input[type='radio']{
                margin: 0 10px;
            }
            .btn{
                display: block;
                margin: 15px 0;
            }
            .pd-20{
                padding: 5px 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1 class="text-center" style="margin-bottom: 25px">{{__('party::dashboard.parties.actions.title')}}</h1>
            <div class="col-md-6 col-xs-12 text-center">
                <img src="{{setting('logo') ? url(setting('logo')) : null}}" alt="logo" style="width: 50%">
                @include('party::dashboard.parties.components.party-actions-card',['party' => $party,'qr'=> \URL::to('/uploads/qr/'.$invitations[0]->code.'.png') , 'number'   => $invitations[0]->invitation_number,'url' => '#'])

                <div class="row text-left pd-20">
                    <form action="{{URL::current()}}" method="post">
                        @csrf
                        @foreach($invitations as $invitation)
                            <h3>{{__('party::dashboard.parties.actions.invitation',['number' => $invitation->invitation_number])}}</h3>
                            <div class="row actionsRow">
                                <input type="radio" name="actions[{{$invitation->id}}]" value="1"> <b>{{__('party::dashboard.parties.actions.accept')}}</b>
                                <input type="radio" name="actions[{{$invitation->id}}]" value="0"> <b>{{__('party::dashboard.parties.actions.refuse')}}</b>
                            </div>
                        @endforeach
                        <div class="clearfix"></div>
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-send"></i> {{__('party::dashboard.parties.actions.send')}}</button>
                    </form>
                </div>
            </div>
        </div>
        <script src="{{asset('/admin/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
        <script>



        </script>
    </body>
</html>
