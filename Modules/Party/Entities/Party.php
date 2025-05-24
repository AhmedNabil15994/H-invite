<?php

namespace Modules\Party\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Area\Entities\City;
use Modules\Area\Entities\State;
use Modules\Core\Traits\Dashboard\CrudModel;
use Modules\Core\Traits\ScopesTrait;
use Modules\Package\Entities\Package;
use Modules\User\Entities\User;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Party extends Model  implements HasMedia
{
    use HasTranslations, SoftDeletes, ScopesTrait,InteractsWithMedia;

    use CrudModel;
    protected $with = [];
    protected $guarded = ['id'];
    public $translatable = ['title','description','whatsapp_msg','acceptance_reply','rejection_reply','reminder_msg'];
    protected $appends = ['image'];

    public function getImageAttribute(){
        return $this->getFirstMediaUrl('images') ?? '';
    }

    public function invitees()
    {
        return $this->belongsToMany(User::class,'parties_invitees','party_id','invitee_id')->withPivot(['count']);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
