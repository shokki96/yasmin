<?php


namespace MY\Service\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use MY\Service\Contracts\ServiceImage as ServiceImageContract;

class ServiceImage extends Model implements ServiceImageContract
{
    public $timestamps = false;

    protected $fillable = [
        'path',
        'service_id',
    ];


    public function service(){
        return $this->belongsTo(ServiceProxy::modelClass());
    }

    /**
     * Get image url for the product image.
     */
    public function url()
    {
        return Storage::url($this->path);
    }

    /**
     * Get image url for the product image.
     */
    public function getUrlAttribute()
    {
        return $this->url();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $array['url'] = $this->url;

        return $array;
    }
}