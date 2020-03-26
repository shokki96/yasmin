<?php

namespace MY\Service\Models;

use Illuminate\Database\Eloquent\Model;
use MY\Service\Contracts\Service as ServiceContract;
use Webkul\Category\Models\CategoryProxy;
use Webkul\Core\Eloquent\TranslatableModel;

class Service extends TranslatableModel implements ServiceContract
{
    public $translatedAttributes = [
        'title',
        'organization',
        'description',
        'slug',
        'url_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $fillable = ['status','position','facebook','linkedin','instagram'];

    protected $with = ['translations'];

    /**
     * The categories that belong to the product.
     */
    public function categories()
    {
        return $this->belongsToMany(CategoryProxy::modelClass(), 'service_categories');
    }

    /**
     * The images that belong to the product.
     */
    public function images()
    {
        return $this->hasMany(ServiceImageProxy::modelClass(), 'service_id');
    }

}
