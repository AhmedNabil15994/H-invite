<div class="form-group">
    <label class="col-md-2">
        {{ __('party::dashboard.parties.form.seller') }}
    </label>
    <div class="col-md-9">
        <select name="invitee_id[]" class="form-control select2" multiple>
            <option value=""></option>
            @foreach($invitees as $invitee)
                <option value="{{$invitee->id}}" {{$model->invitees->contains($invitee->id) ? 'selected' : ''}}>{{$invitee->name}}</option>
            @endforeach
        </select>
        <div class="help-block"></div>
    </div>
</div>

@if($model?->id)
    @php
        $parties = array_unique($model->invitations()->pluck('party_id')->toArray());
    @endphp
@endif

<div class="form-group">
    <label class="col-md-2">
        {{ __('apps::dashboard._layout.aside.parties') }}
    </label>
    <div class="col-md-9">
        <select name="party_id[]" class="form-control party_id select2" multiple>
            <option value=""></option>
            @if($model?->id)
                @foreach($model->invitees->flatMap->parties->unique('id') as $party)
                    <option value="{{$party->id}}"  {{in_array($party->id,$parties) ? 'selected' : ''}}>{{$party->title}}</option>
                @endforeach
            @endif

        </select>
        <div class="help-block"></div>
    </div>
</div>
{!! field()->text('name', __('contact::dashboard.contacts.form.name')) !!}
{!! field()->text('mobile', __('contact::dashboard.contacts.form.mobile')) !!}
{!! field()->email('email', __('contact::dashboard.contacts.form.email')) !!}
<div class="form-group hidden">
    <label class="col-md-2">
        {{ __('contact::dashboard.contacts.form.max_invitations') }}
    </label>
    <div class="col-md-9">
        <input type="number" class="form-control" readonly min="1" value="1" name="max_invitations" placeholder="{{__('contact::dashboard.contacts.form.max_invitations')}}">
        <div class="help-block"></div>
    </div>
</div>

{!! field()->checkBox('status', __('contact::dashboard.contacts.form.status')) !!}


@if ($model->trashed())
    {!! field()->checkBox('trash_restore', __('contact::dashboard.contacts.form.restore')) !!}
@endif

@section('additional_scripts')
    <script>
        $(function(){
            $('select[name="invitee_id[]"]').on('change',function (){
                let invitee_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: "{{route('dashboard.parties.getByInvitee')}}",
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'ids'   : invitee_id,
                    },
                    success:function (data){
                        let x = '<option value=""></option>';
                        $.each(data.parties ,function (index,item){
                            x+= `<option value="${item.id}" {{}}>${item.title}</option>`;
                        });
                        $('select.party_id').empty();
                        $('select.party_id').append(x);
                        $('select.party_id').select2('refresh');
                    },
                    error:function (error){
                    }
                })
            });
        })
    </script>
@endsection
