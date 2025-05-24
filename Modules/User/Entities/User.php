<?php

namespace Modules\User\Entities;

use Illuminate\Support\Carbon;
use Modules\Contact\Entities\Contact;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\Note;
use Modules\DeviceToken\Entities\PersonalAccessToken;
use Modules\Exam\Entities\UserExam;
use Modules\Offer\Entities\Offer;
use Modules\Order\Entities\Address;
use Modules\Order\Entities\NoteOrder;
use Modules\Order\Entities\Order;
use Modules\Package\Entities\Package;
use Modules\Party\Entities\Invitation;
use Modules\Party\Entities\InviteeContact;
use Modules\Party\Entities\Party;
use Modules\Party\Entities\PartyInvitee;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerProfile;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Traits\ScopesTrait;
use Spatie\Permission\Traits\HasRoles;
use Modules\Order\Entities\OrderItem;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\Core\Traits\Dashboard\CrudModel;
use Modules\DeviceToken\Traits\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Staudenmeir\EloquentJsonRelations\Relations\Postgres\HasOne;

class User extends Authenticatable implements HasMedia,\Tocaan\FcmFirebase\Contracts\IFcmFirebaseDevice
{
    use CrudModel{
        __construct as private CrudConstruct;
    }
    use \Tocaan\FcmFirebase\Traits\FcmDeviceTrait;

    use Notifiable , HasRoles , InteractsWithMedia,HasApiTokens;

    use SoftDeletes {
      restore as private restoreB;
    }
    protected $guard_name = 'web';
    protected $appends = ['image_file'];
    protected $dates = [
      'deleted_at'
    ];

    protected $fillable = [
        'name', 'email', 'password', 'mobile' , 'image','academic_year_id','first_login','gender','birthday','delete_reason',
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setLogAttributes(['name', 'email', 'password', 'mobile' , 'image']);

    }

    public function setImageAttribute($value)
    {
        if (!$value) {
            $this->attributes['image'] = '/uploads/users/user.png';
        }
        $this->attributes['image'] = $this->getImageFileAttribute();
    }

    public function getImageFileAttribute()
    {
        return $this->hasMedia('images') ? $this->getFirstMediaUrl('images') : '/uploads/users/user.png';
    }

    public function setPasswordAttribute($value)
    {
        if ($value === null || !is_string($value)) {
            return;
        }
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function restore()
    {
        $this->restoreB();
    }

    public function fcmTokens()
    {
        return $this->hasMany(FirebaseToken::class);
    }

    public function tokens()
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }

    public function invitee_contacts()
    {
        return $this->belongsToMany(Contact::class,'invitees_contacts','invitee_id','contact_id')->withPivot(['invitee_id','contact_id','created_at','updated_at','display_name']);
    }

    public function inviteeContacts()
    {
        return $this->hasMany(InviteeContact::class,'invitee_id','id');
    }

    public function parties()
    {
        return $this->belongsToMany(Party::class,'parties_invitees','invitee_id','party_id')->withTimestamps();
    }

    public function invitations()
    {
        return $this->parties()->withCount([
            'invitations as invitations_count' => function ($q) {
                $q->where('id','!=',null);
            }
        ]);
    }


}
