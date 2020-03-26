<?php


namespace Webkul\Product\Type;


class Service extends AbstractType
{
    /**
     * Skip attribute for virtual product type
     *
     * @var array
     */
    protected $skipAttributes = ['width', 'height', 'depth', 'weight','new','thumbnail','guest_checkout',
        'price','cost','special_price','special_price_from','special_price_to','color','color_label','size','channel',
        'brand','tax_category_id'];

    /**
     * These blade files will be included in product edit page
     *
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.categories',
    ];

    /**
     * Is a stokable product type
     *
     * @var bool
     */
    protected $isStockable = false;

    /**
     * Show quantity box
     *
     * @var bool
     */
    protected $showQuantityBox = false;

    /**
     * Return true if this product type is saleable
     *
     * @return bool
     */
    public function isSaleable()
    {
//        if (! $this->product->status) {
//            return false;
//        }
//
//        if ($this->haveSufficientQuantity(1)) {
//            return true;
//        }

        return false;
    }

    /**
     * @param  int  $qty
     * @return bool
     */
    public function haveSufficientQuantity($qty)
    {
        return false;
    }
}