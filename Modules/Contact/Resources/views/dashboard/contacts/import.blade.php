@extends('apps::dashboard.layouts.app')
@section('title', __('contact::dashboard.contacts.routes.import'))
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
                        <a href="{{ url(route('dashboard.contacts.index')) }}">
                            {{__('contact::dashboard.contacts.routes.index')}}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{__('contact::dashboard.contacts.routes.import')}}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">

                {!! Form::model($model,[
                                'url'=> route('dashboard.contacts.import_file'),
                                'id'=>'form',
                                'role'=>'form',
                                'method'=>'POST',
                                'class'=>'form-horizontal form-row-seperated',
                                'files' => true
                                ])!!}

                <div class="col-md-12">

                    <div class="col-md-3">
                        <div class="panel-group accordion scrollable" id="accordion2">
                            <div class="panel panel-default">

                                <div id="collapse_2_1" class="panel-collapse in">
                                    <div class="panel-body">
                                        <ul class="nav nav-pills nav-stacked">

                                            <li class="active">
                                                <a href="#global_setting" data-toggle="tab">
                                                    {{ __('contact::dashboard.contacts.form.tabs.general') }}
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="tab-content">
                            <div class="tab-pane active fade in" id="global_setting">
                                <div class="form-group">
                                    <label class="col-md-2">
                                        {{ __('party::dashboard.parties.form.seller') }}
                                    </label>
                                    <div class="col-md-9">
                                        <select name="invitee_id[]" class="form-control select2" multiple>
                                            <option value=""></option>
                                            @foreach($invitees as $invitee)
                                                <option value="{{$invitee->id}}">{{$invitee->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                {!! field()->file('excel_file',__('contact::dashboard.contacts.excel_file'),null,['accept'=>".xlsx,.xls,application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",]) !!}
                                <div class="form-group" style="margin-top: -15px;">
                                    <label for="" class="col-md-2"></label>
                                    <div class="col-md-9">
                                        <p style="margin: 0">
                                            {{ __('apps::dashboard.buttons.excel_examples') }}
                                            <a target="_blank" href="{{asset('uploads/contacts.xlsx')}}">{{ __('apps::dashboard.buttons.download') }}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-actions">
                        @include('apps::dashboard.layouts._ajax-msg')
                        <div class="form-group">
                            <button type="submit" id="submit" class="btn btn-lg blue">
                                {{__('apps::dashboard.buttons.add')}}
                            </button>
                            <a href="{{url(route('dashboard.contacts.index')) }}" class="btn btn-lg red">
                                {{__('apps::dashboard.buttons.back')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close()!!}
        </div>
    </div>
    </div>
@stop
