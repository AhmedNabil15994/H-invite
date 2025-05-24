<?php

namespace Modules\Party\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Area\Entities\City;
use Modules\Area\Entities\State;
use Modules\Contact\Entities\Contact;
use Modules\Core\Traits\Dashboard\CrudModel;
use Modules\Core\Traits\ScopesTrait;
use Modules\User\Entities\User;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Invitation extends Model
{
    use SoftDeletes, ScopesTrait;
    use CrudModel;
    use SchemalessAttributesTrait;
    use HasJsonRelationships {
        HasJsonRelationships::getAttributeValue as getAttributeValueJson;
    }
    public function getAttributeValue($key)
    {
        return $this->getAttributeValueJson($key);
    }

    protected $with = [];
    protected $fillable = ['code','party_id','contact_id','invitations','related_invitation_id','status','attended_at','scanned_at','invitation_number','reminded_at','sent_at','queue_data','step'];

    protected $schemalessAttributes = [
        'queue_data'
    ];

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function inviteeContact()
    {
        return $this->belongsTo(InviteeContact::class,'contact_id','id');
    }

    public function related_invitation()
    {
        return $this->belongsTo(self::class,'related_invitation_id');
    }

    public function children_invitations()
    {
        return $this->hasMany(self::class,'related_invitation_id');
    }

    public function getStatus($flag=null)
    {
        $text = '';
        if($this->status == 1 || $flag == 1){
            $text = '<span class="badge badge-primary">'.__('apps::dashboard.datatable.scanned').'</span>';
        }else if($this->status == 2 || $flag == 2){
            $text = '<span class="badge badge-dark">'.__('apps::dashboard.datatable.pending').'</span>';
        } else if($this->status == 3 || $flag == 3){
            $text = '<span class="badge badge-danger">'.__('apps::dashboard.datatable.rejected').'</span>';
        }else if($this->status == 4 || $flag == 4){
            $text = '<span class="badge badge-warning">'.__('apps::dashboard.datatable.accepted').'</span>';
        }

        if($flag == 10){
            $text = '<span class="badge badge-success">'.__('apps::dashboard.datatable.accepted').'</span>';
        }
        return $text;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            generateQrCode($invitation->code);
        });
    }
}
