<?php


namespace MY\Service\Repositories;


use Webkul\Core\Eloquent\Repository;

class ServiceTranslationRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return 'MY\Service\Contracts\ServiceTranslation';
    }

    public function create(array $data)
    {
        if (isset($data['locale']) && $data['locale'] == 'all') {
            $model = app()->make($this->model());

            foreach (core()->getAllLocales() as $locale) {
                foreach ($model->translatedAttributes as $attribute) {
                    if (isset($data[$attribute])) {
                        $data[$locale->code][$attribute] = $data[$attribute];
                        $data[$locale->code]['locale_id'] = $locale->id;
                    }
                }
            }
        }
    }
}