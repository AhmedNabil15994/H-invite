<!DOCTYPE html>
<html dir="rtl">
    <head>
        <meta charset="utf-8" />
        <title>Statistics || {{ setting('app_name',locale()) }}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{__('party::dashboard.parties.statistics.title')}}</title>
        @include('party::dashboard.parties.components.pdf_styles')

    </head>
    <body>
        <div class="container">
            <h1 class="text-center" style="margin-bottom: 25px">{{__('party::dashboard.parties.statistics.stats',['party'=> ucwords($data['party']->title)])}}</h1>
    {{--        <div class="col-xs-6">--}}
    {{--            @include('party::dashboard.parties.components.party-card',['party' => $data['party']])--}}
    {{--        </div>--}}
            <div class="col-xs-12 row" style="margin: 0;padding: 0">
                <div class="u-container-style u-layout-cell u-right-cell u-size-30 u-layout-cell-2 text-center">
                    <div class="u-container-layout u-valign-middle-lg u-valign-middle-md u-valign-middle-sm u-valign-middle-xl u-valign-top-xs u-container-layout-2">
                        <h2 class="u-align-center u-custom-font u-text u-text-1">{{implode(',',$data['party']->invitees()->pluck('name')->toArray())}}</h2>
                        <a href="#" class="u-border-2 u-border-grey-dark-1 u-btn u-btn-rectangle u-button-style u-none u-btn-1">{{date('Y-m-d',strtotime($data['party']->start_at)) . ' - ' . date('Y-m-d',strtotime($data['party']->expired_at))}}</a>
                    </div>
                </div>
                <div class="col-xs-12 card limit text-center">
                    <div class="pie">
                        <div class="text-center limit">
                            {{$data['remaining_invitations']}}<br>
                            <small class="limit">{{__('party::dashboard.parties.statistics.package_limit',['limit' => $data['package_limit']])}}</small>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 card accepted">
                    <h3>{{__('party::dashboard.parties.statistics.accepted')}}</h3>
                    <div class="row">
                        <div class="col-xs-8">
                            <p>{{$data['accepted']}}</p>
                        </div>
                        <div class="col-xs-4">
                            <span class="stats-icon pull-right">
                                <i class="fa fa-check"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 card rejected">
                    <h3>{{__('party::dashboard.parties.statistics.rejected')}}</h3>
                    <div class="row">
                        <div class="col-xs-8">
                            <p>{{$data['rejected']}}</p>
                        </div>
                        <div class="col-xs-4">
                            <span class="stats-icon pull-right">
                                <i class="fa fa-times" style="margin: 2px 6px;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 card attended">
                    <h3>{{__('party::dashboard.parties.statistics.attended')}}</h3>
                    <div class="row">
                        <div class="col-xs-8">
                            <p>{{$data['active']}}</p>
                        </div>
                        <div class="col-xs-4">
                            <span class="stats-icon pull-right">
                                <i class="fa fa-users"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 card pending">
                    <h3>{{__('party::dashboard.parties.statistics.pending')}}</h3>
                    <div class="row">
                        <div class="col-xs-8">
                            <p>{{$data['pending']}}</p>
                        </div>
                        <div class="col-xs-4">
                            <span class="stats-icon pull-right">
                                <i class="fa fa-user-times"></i>
                            </span>
                        </div>
                    </div>
                </div>
{{--                <div class="col-xs-12 card active">--}}
{{--                    <h3>{{__('party::dashboard.parties.statistics.scanned')}}</h3>--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-xs-8">--}}
{{--                            <p>{{$data['active']}}</p>--}}
{{--                        </div>--}}
{{--                        <div class="col-xs-4">--}}
{{--                            <span class="stats-icon pull-right">--}}
{{--                                <i class="fa fa-qrcode" style="margin: 3px 6px;"></i>--}}
{{--                            </span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
            <div class="clearfix"></div>
            <div class="details" style="padding: 20px 0;margin-top: 70px">
                <hr>
                <h2 style="margin-bottom: 25px;margin-top: 25px">{{__('contact::dashboard.contacts.routes.index')}}</h2>
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table">
                            <thead>
                                <th>#</th>
                                <th>{{ __('party::dashboard.invitations.form.seller') }}</th>
                                <th>{{__('contact::dashboard.contacts.datatable.name')}}</th>
                                <th>{{__('contact::dashboard.contacts.datatable.mobile')}}</th>
                                <th>{{ __('party::dashboard.invitations.form.invitations') }}</th>
                                <th>{{ __('party::dashboard.invitations.form.details') }}</th>
                            </thead>
                            <tbody>
                            @foreach($data['party']->invitations()->groupBy('contact_id')->get() as $key => $invitation)
                                @php
                                    $count = $data['party']->invitations()->where([['party_id',$invitation->party_id],['contact_id',$invitation->contact_id]])->count() ?? 1;
                                @endphp
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$invitation->inviteeContact->invitee->name}}</td>
                                    @php
                                        $inviteeContact = $invitation->inviteeContact;
                                        $invitee_id = $inviteeContact?->invitee_id ?? null;
                                    @endphp
                                    <td>{{$invitation->inviteeContact->display_name}}</td>
                                    <td style="direction: ltr">{{$invitation->inviteeContact->contact->mobile}}</td>
                                    <td>{{$count}}</td>
                                    <td>
                                        <div class="row invite_row">
                                            <div class="col-xs-2">{{ __('party::dashboard.invitations.form.number') }}</div>
                                            <div class="col-xs-4">{{ __('party::dashboard.invitations.form.code') }}</div>
                                            <div class="col-xs-4">{{ __('party::dashboard.invitations.form.scanned_at') }}</div>
                                            <div class="col-xs-2">{{ __('party::dashboard.invitations.form.status') }}</div>
                                        </div>
                                        @foreach($data['party']->invitations()->where([['party_id',$invitation->party_id],['contact_id',$invitation->contact_id]])->get() as $related)
                                            <div class="row invite_row">
                                                <div class="col-xs-2">{{$related->invitation_number}}</div>
                                                <div class="col-xs-4">{{$related->code}}</div>
                                                <div class="col-xs-4">{{$related->scanned_at ?? '-----'}}</div>
                                                <div class="col-xs-2" style="padding:0">{!! $related->getStatus() !!}</div>
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{asset('/admin/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
        <script>

               $(function (){
                   window.print();
               })

        </script>
    </body>
</html>
