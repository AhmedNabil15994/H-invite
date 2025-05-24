@extends('apps::dashboard.layouts.app')
@section('title', __('party::dashboard.invitations.routes.create'))
@section('css')
    <link href="{{asset('/admins/css/bootstrap-datetimepicker.min.css')}}">
    <style>
        textarea{
            min-height: 150px;
            max-height: 200px;
        }
        .mb-30{
            margin-bottom: 30px;
        }
        .u-section-2{
            width: 100%;
        }
    </style>
@endsection
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
                        <a href="#">{{ __('party::dashboard.invitations.routes.create') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="form" role="form" class="form-horizontal form-row-seperated" method="post"
                    enctype="multipart/form-data" action="{{ route('dashboard.invitations.store') }}">
                    @csrf
                    <div class="col-md-12">

                        {{-- RIGHT SIDE --}}
                        <div class="col-md-3">
                            <div class="panel-group accordion scrollable" id="accordion2">
                                <div class="panel panel-default">
                                    {{-- <div class="panel-heading">
                                        <h4 class="panel-title"><a class="accordion-toggle"></a></h4>
                                    </div> --}}
                                    <div id="collapse_2_1" class="panel-collapse in">
                                        <div class="panel-body">
                                            <ul class="nav nav-pills nav-stacked">
                                                <li class="active">
                                                    <a href="#global_setting" data-toggle="tab">
                                                        {{ __('party::dashboard.invitations.form.tabs.general') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- PAGE CONTENT --}}
                        <div class="col-md-9">
                            <div class="tab-content">

                                {{-- CREATE FORM --}}
                                <div class="tab-pane active fade in" id="global_setting">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('party::dashboard.invitations.form.party') }}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="party_id" class="form-control select2" >
                                                    <option value=""></option>
                                                    @foreach($parties as $party)
                                                        <option value="{{$party->id}}">{{$party->title}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block"></div>
                                                <div class="party"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('party::dashboard.invitations.form.seller') }}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="invitee_id" class="form-control select2" >
                                                    <option value=""></option>

                                                </select>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('party::dashboard.invitations.form.contact') }}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="contact_id" class="form-control select2" >
                                                    <option value=""></option>

                                                </select>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('party::dashboard.invitations.form.invitations') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="number" min="1" max="25" name="invitations" class="form-control">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('party::dashboard.invitations.form.attended_at') }}
                                            </label>
                                            <div class="col-md-9">
                                                <div class="input-append date input-group input-medium">
                                                    <input data-format="dd/MM/yyyy hh:mm:ss" class="form-control datetimepicker" type="text" name="attended_at" data-name="attended_at">
                                                    <span class="input-group-btn">
                                                        <button class="btn default" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('party::dashboard.invitations.form.scanned_at') }}
                                            </label>
                                            <div class="col-md-9">
                                                <div class="input-append date input-group input-medium">
                                                    <input data-format="dd/MM/yyyy hh:mm:ss" class="form-control datetimepicker" type="text" name="scanned_at" data-name="scanned_at">
                                                    <span class="input-group-btn">
                                                        <button class="btn default" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('party::dashboard.invitations.form.status') }}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="inv_status" class="form-control select2" >
                                                    <option value=""></option>
                                                    <option value="1">{{__('apps::dashboard.datatable.active')}}</option>
                                                    <option value="2">{{__('apps::dashboard.datatable.pending')}}</option>
                                                    <option value="3">{{__('apps::dashboard.datatable.rejected')}}</option>
                                                    <option value="4">{{__('apps::dashboard.datatable.attended')}}</option>
                                                </select>
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
                                    <button type="submit" id="submit" class="btn btn-lg blue">
                                        {{ __('apps::dashboard.buttons.add') }}
                                    </button>
                                    <a href="{{ url(route('dashboard.invitations.index')) }}" class="btn btn-lg red">
                                        {{ __('apps::dashboard.buttons.back') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@stop


@section('scripts')
    <script src="{{asset('/admins/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $('input[name="attended_at"],input[name="scanned_at"]').datetimepicker();
            $('select[name="party_id"]').on('change',function (){
                if($(this).val()){
                    $.ajax({
                        type:'get',
                        url: "{{route('dashboard.parties.getContacts')}}",
                        data:{
                            'party_id': $('select[name="party_id"]').val(),
                            'invitee_id': $('select[name="invitee_id"]').val(),
                        },
                        success: function (data){
                            buildSelect($('select[name="invitee_id"]'),data.invitees,'name');
                            $('.party').empty().html(data.party_card)
                        },
                    });
                }
            });

            $('select[name="invitee_id"]').on('change',function (){
                if($(this).val()){
                    $.ajax({
                        type:'get',
                        url: "{{route('dashboard.parties.getContacts')}}",
                        data:{
                            'party_id': $('select[name="party_id"]').val(),
                            'invitee_id': $('select[name="invitee_id"]').val(),
                        },
                        success: function (data){
                            buildSelect($('select[name="contact_id"]'),data.contacts);
                            $('.party').empty().html(data.party_card)
                        },
                    });
                }
            });

            function buildSelect(item , data,name){
                item.empty().select2('destroy');
                let x = '<option value=""></option>';

                $.each(data,function(index,item){
                    let optionName = name ? item.name : ( item.contact.mobile + (item.display_name ? ' --- ' + item.display_name : '' ));
                    x+="<option value='"+item.id+"'>"+optionName+"</option>";
                });
                item.append(x).select2({
                    'placeholder': "Select"
                });
            }
        });
    </script>

@endsection
