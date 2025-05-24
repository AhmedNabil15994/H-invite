<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="col-md-5">
                {{ __('party::dashboard.parties.form.qr_width') }} ({{ __('party::dashboard.parties.form.in_pixel') }})
            </label>
            <div class="col-md-6">
                <input type="number" min="0" class="form-control" value="{{$dimensions['qrWidth'] ?? setting('qr_width')}}" readonly data-size="small" name="dimensions[qr_width]">
                <div class="help-block"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="col-md-5">
                {{ __('party::dashboard.parties.form.qr_height') }} ({{ __('party::dashboard.parties.form.in_pixel') }})
            </label>
            <div class="col-md-6">
                <input type="number" min="0" class="form-control" value="{{$dimensions['qrHeight'] ?? setting('qr_height')}}" readonly data-size="small" name="dimensions[qr_height]">
                <div class="help-block"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="col-md-5">
                {{ __('party::dashboard.parties.form.invitation_width') }} ({{ __('party::dashboard.parties.form.in_pixel') }})
            </label>
            <div class="col-md-6">
                <input type="number" min="0" class="form-control" value="{{$dimensions['invitationWidth'] ?? 500}}" data-size="small" name="dimensions[invitation_width]">
                <div class="help-block"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="col-md-5">
                {{ __('party::dashboard.parties.form.invitation_height') }} ({{ __('party::dashboard.parties.form.in_pixel') }})
            </label>
            <div class="col-md-6">
                <input type="number" min="0" class="form-control" value="{{$dimensions['invitationHeight'] ?? 500}}" data-size="small" name="dimensions[invitation_height]">
                <div class="help-block"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="col-md-5">
                QR {{ __('party::dashboard.parties.form.dist_x') }} ({{ __('party::dashboard.parties.form.in_pixel') }})
            </label>
            <div class="col-md-6">
                <input type="number" min="0" class="form-control" value="{{$dimensions['distX'] ?? 0}}" data-size="small" name="dimensions[dist_x]">
                <div class="help-block"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="col-md-5">
                QR {{ __('party::dashboard.parties.form.dist_y') }} ({{ __('party::dashboard.parties.form.in_pixel') }})
            </label>
            <div class="col-md-6">
                <input type="number" min="0" class="form-control" value="{{$dimensions['distY'] ?? 0}}" data-size="small" name="dimensions[dist_y]">
                <div class="help-block"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="col-md-5">
                Action {{ __('party::dashboard.parties.form.dist_x') }} ({{ __('party::dashboard.parties.form.in_pixel') }})
            </label>
            <div class="col-md-6">
                <input type="number" min="0" class="form-control" value="{{$dimensions['distX2'] ?? 0}}" data-size="small" name="dimensions[dist_x2]">
                <div class="help-block"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="col-md-5">
                Action {{ __('party::dashboard.parties.form.dist_y') }} ({{ __('party::dashboard.parties.form.in_pixel') }})
            </label>
            <div class="col-md-6">
                <input type="number" min="0" class="form-control" value="{{$dimensions['distY2'] ?? 0}}" data-size="small" name="dimensions[dist_y2]">
                <div class="help-block"></div>
            </div>
        </div>
    </div>

{{--    <div class="col-md-3">--}}
{{--        <div class="form-group">--}}
{{--            <label class="col-md-5">--}}
{{--                {{ __('party::dashboard.parties.form.apply_black') }}--}}
{{--            </label>--}}
{{--            <div class="col-md-6">--}}
{{--                <input type="checkbox" class="make-switch background" data-size="small" name="dimensions[background]" {{$dimensions['background'] == 1 ? 'checked' : ''}}>--}}
{{--                <div class="help-block"></div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="col-md-12">
        <div class="invitation">
            <div class="qrImage" id="draggable">
                <img src="{{$dimensions['qr']}}" alt="qrImage">
                <div class="code" id="draggable2">
                    <b>#{{$dimensions['invitationNumber']}}</b>
                </div>
            </div>
            <div class="actions" id="draggable3">
                <a href="#">أضغط هنا</a>
                <p>{{ __('party::dashboard.parties.form.accept_or_refuse') }}</p>
            </div>
        </div>
    </div>
</div>
