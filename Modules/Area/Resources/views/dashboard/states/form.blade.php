@inject('cities' ,'Modules\Area\Entities\City')

{!! field()->langNavTabs() !!}

<div class="tab-content">
    @foreach (config('laravellocalization.supportedLocales') as $code => $lang)
        <div class="tab-pane fade in {{ ($code == locale()) ? 'active' : '' }}"
             id="first_{{$code}}">
            {!! field()->text('title['.$code.']',
            __('party::dashboard.parties.form.title').'-'.$code ,
                    $model->getTranslation('title' , $code),
                  ['data-name' => 'title.'.$code]
             ) !!}
        </div>
    @endforeach
</div>

{!! field()->select('city_id',__('area::dashboard.states.form.cities'),$cities->pluck('title','id')->toArray()) !!}
{!! field()->checkBox('status', __('party::dashboard.parties.form.status')) !!}
@if ($model->trashed())
    {!! field()->checkBox('trash_restore', __('party::dashboard.parties.form.restore')) !!}
@endif
