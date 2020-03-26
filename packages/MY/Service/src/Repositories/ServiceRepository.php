<?php


namespace MY\Service\Repositories;


use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use MY\Service\Models\ServiceTranslation;
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
    public function findByPath(string $urlPath)
    {
        return $this->model->whereTranslation('slug', $urlPath)->first();
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

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \MY\Service\Contracts\Service
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $service = $this->find($id);

        Event::dispatch('catalog.service.update.before', $id);



        $route = request()->route() ? request()->route()->getName() : "";

        if ($route != 'admin.catalog.service.massupdate') {
            if  (isset($data['categories'])) {
                $service->categories()->sync($data['categories']);
            }

            $data['status'] = isset($data['status']) && $data['status'] ? 1 : 0;
            $this->serviceImageRepository->uploadImages($data, $service);
        }

        $service->update($data);

        Event::dispatch('catalog.service.update.after', $id);

        return $service;
    }

    /**
     * @param  int  $id
     * @return void
     */
    public function delete($id)
    {
        Event::dispatch('catalog.service.delete.before', $id);

        parent::delete($id);

        Event::dispatch('catalog.service.delete.after', $id);
    }

    /**
     * Checks slug is unique or not based on locale
     *
     * @param  int  $id
     * @param  string  $slug
     * @return bool
     */
    public function isSlugUnique($id, $slug)
    {
        $exists = ServiceTranslation::where('service_id', '<>', $id)
            ->where('slug', $slug)
            ->limit(1)
            ->select(DB::raw(1))
            ->exists();

        return $exists ? false : true;
    }

    public function getAll($categoryId = null){
        $params = request()->input();

        $results = app(ServiceTranslationRepository::class)->scopeQuery(function($query) use($params, $categoryId) {

            $locale = request()->get('locale') ?: app()->getLocale();

            $qb = $query->distinct()
                ->addSelect('service_translations.*')
                ->leftJoin('services', 'service_translations.service_id', '=', 'services.id')
                ->leftJoin('service_categories', 'services.id', '=', 'service_categories.service_id')
                ->where('service_translations.locale', $locale)
                ->whereNotNull('service_translations.slug');

            if ($categoryId) {
                $qb->where('service_categories.category_id', $categoryId);
            }

            if (is_null(request()->input('status'))) {
                $qb->where('services.status', 1);
            }

            if (isset($params['search']))
                $qb->where('service_translations.title', 'like', '%' . urldecode($params['search']) . '%');


            return $qb->groupBy('service_translations.id');
        })->paginate(isset($params['limit']) ? $params['limit'] : 9);

        return $results;
    }
}