<?php

namespace Modules\Contact\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\Dashboard\CrudModel;
use Modules\Core\Traits\ScopesTrait;
use Modules\Channel\Entities\Channel;
use Modules\Party\Entities\Invitation;
use Modules\Party\Entities\InviteeContact;
use Modules\User\Entities\User;

class Contact extends Model
{
    use SoftDeletes ;
    use ScopesTrait ;
    use CrudModel;


    protected $fillable = [
        'name',
        'email',
        'mobile',
        'max_invitations',
        'status',
    ];

    public $appends = ['display_name'];

    public function invitees()
    {
        return $this->belongsToMany(User::class,'invitees_contacts','contact_id','invitee_id')->withPivot(['invitee_id','contact_id','created_at','updated_at','display_name']);
    }

    public function invitations()
    {
        return $this->hasManyThrough(Invitation::class,InviteeContact::class,'contact_id','contact_id','id');
    }

    public function invitees_contacts()
    {
        return $this->hasMany(InviteeContact::class);
    }

    public function getDisplayNameAttribute($invitee_id = null){
        return $this->invitees_contacts()->where('invitee_id',$invitee_id ?? auth('sanctum')->id())->first()?->display_name ?? $this->name;
    }
}
