<?php

namespace Modules\Party\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Contact\Entities\Contact;
use Modules\Core\Traits\Dashboard\CrudModel;
use Modules\User\Entities\User;

class InviteeContact extends Model
{
    use CrudModel;
   protected $table = 'invitees_contacts';
   public $fillable = ['contact_id','invitee_id','display_name'];

   public function partyInvitee()
   {
       return $this->hasOne(PartyInvitee::class,'invitee_id','invitee_id');
   }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function invitee()
    {
        return $this->belongsTo(User::class,'invitee_id','id');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class,'contact_id','id');
    }
}
