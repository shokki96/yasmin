<?php


namespace MY\Service\Repositories;


use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;

class ServiceRepository extends Repository
{
    protected $serviceImageRepository;
    public function __construct(
        ServiceImageRepository $imageRepository,
        App $app
    )
    {
        $this->serviceImageRepository = $imageRepository;

        parent::__construct($app);
    }
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return "MY\Service\Contracts\Service";
    }

    public function create(array $data)
    {
        Event::dispatch('catalog.service.create.before');

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

        $service = $this->model->create($data);

        $this->serviceImageRepository->uploadImages($data, $service);

        if  (isset($data['categories'])) {
            $service->categories()->sync($data['categories']);
        }

        Event::dispatch('catalog.service.create.after',$service);

        return $service;

    }
}