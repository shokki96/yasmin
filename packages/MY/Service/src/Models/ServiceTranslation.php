<?php


namespace MY\Service\Models;


use Illuminate\Database\Eloquent\Model;
use MY\Service\Contracts\ServiceTranslation as ServiceTranslationContract;

class ServiceTranslation extends Model implements ServiceTranslationContract
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'organization',
        'description',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'locale_id',
    ];


    public function parent(){
        return $this->belongsTo(ServiceProxy::modelClass(),'service_id');
    }
}