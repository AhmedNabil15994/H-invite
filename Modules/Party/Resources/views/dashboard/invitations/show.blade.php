@extends('apps::dashboard.layouts.app')
@section('css')
    <style>
        svg{
            width: 250px;
            height: 250px;
            display: block;
            margin: 15px;
        }
        textarea{
            min-height: 150px;
            max-height: 200px;
        }
        .mb-30{
            margin-bottom: 30px;
        }
        .select2-container--bootstrap{
            width: 100% !important;
        }
    </style>
@endsection
@section('title', __('party::dashboard.invitations.routes.show'))
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.index.title') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ url(route('dashboard.invitations.index')) }}">
                            {{ __('party::dashboard.invitations.routes.index') }}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('party::dashboard.invitations.routes.show') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>
            <div class="row">
                <div class="col-md-12">

                    {{-- PAGE CONTENT --}}
                    <div class="col-md-9">
                        <div class="tab-content">
                            {{-- CREATE FORM --}}
                            <div class="tab-pane active fade in" id="global_setting">
                                <div class="col-md-10">
                                    @if(!in_array($model->status,[2,3]))
                                    <div class="form-group">
                                        {!! QrCode::size(460)->generate( route('frontend.invitations.redeem',['code'=>$model->code]) ) !!}
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{ __('party::dashboard.invitations.form.number') }}
                                        </label>
                                        <div class="col-md-9">
                                            <label><b>{{$model->invitation_number}}</b></label>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{ __('party::dashboard.invitations.form.code') }}
                                        </label>
                                        <div class="col-md-9">
                                            <label><b>{{$model->code}}</b></label>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{ __('party::dashboard.invitations.form.party') }}
                                        </label>
                                        <div class="col-md-9">
                                            <label><b>{{$model->party->title}}</b></label>
                                            <div class="help-block"></div>
                                        </div>
                                        <div class="party">
                                            @include('party::dashboard.parties.components.party-card',['party'=>$model->party])
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{ __('party::dashboard.invitations.form.contact') }}
                                        </label>
                                        <div class="col-md-9">
                                            <label><b>{{$model->inviteeContact->contact->name}} <br> {{$model->inviteeContact->contact->mobile}}</b></label>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{ __('party::dashboard.invitations.form.invitations') }}
                                        </label>
                                        <div class="col-md-9">
                                            <label><b>{{$model->invitations}}</b></label>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                    @if($model->invitations > 1)
                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{ __('party::dashboard.invitations.form.related_invitation') }}
                                        </label>
                                        <div class="col-md-9">
                                            <label>
                                                <b>
                                                    @if($model->related_invitation)
                                                        {{$model->related_invitation->code}} <br>
                                                        {{$model->related_invitation->contact->name}} <br>
                                                        {{$model->related_invitation->contact->mobile}}
                                                    @else
                                                        --------
                                                    @endif
                                                </b>
                                            </label>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{ __('party::dashboard.invitations.form.status') }}
                                        </label>
                                        <div class="col-md-9">
                                            <label><b>{{strip_tags($model->getStatus())}}</b></label>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{ __('party::dashboard.invitations.form.attended_at') }}
                                        </label>
                                        <div class="col-md-9">
                                            <label><b>{{$model->attended_at ?? '--------'}}</b></label>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{ __('party::dashboard.invitations.form.scanned_at') }}
                                        </label>
                                        <div class="col-md-9">
                                            <label><b>{{$model->scanned_at ?? '--------'}}</b></label>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            {{-- END CREATE FORM --}}
                        </div>
                    </div>

                    {{-- PAGE ACTION --}}
                    <div class="col-md-12">
                        <div class="form-actions">
                            @include('apps::dashboard.layouts._ajax-msg')
                            <div class="form-group">
                                <a href="{{ url(route('dashboard.invitations.index')) }}" class="btn btn-lg red">
                                    {{ __('apps::dashboard.buttons.back') }}
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('select[name="party_id"]').on('change',function (){
                if($(this).val()){
                    $.ajax({
                        type:'get',
                        url: "{{route('dashboard.parties.getContacts',['party_id'=>':id'])}}".replace(':id',$(this).val()),
                        success: function (data){
                            $('select[name="contact_id"]').empty().select2('destroy');
                            let x = '<option value=""></option>';
                            $.each(data,function(index,item){
                                let extraString = "{{$model->contact_id}}" == item.id ? 'selected' : '';
                                x+="<option value='"+item.id+"'  "+extraString+">"+item.name+"</option>";
                            });
                            $('select[name="contact_id"]').append(x).select2();
                        },
                    });
                }
            });

            $('select[name="party_id"]').val('{{$model->party_id}}').trigger('change')

        });
    </script>

@endsection
