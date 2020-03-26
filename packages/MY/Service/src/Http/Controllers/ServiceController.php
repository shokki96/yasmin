<?php

namespace MY\Service\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use MY\Service\Repositories\ServiceTranslationRepository;
use MY\Service\Repositories\ServiceRepository;
use Webkul\Category\Repositories\CategoryRepository;

class ServiceController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CategoryRepository object
     *
     * @var \Webkul\Category\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * ServiceRepository object
     *
     * @var \MY\Service\Repositories\ServiceRepository
     */
    protected $serviceRepository;


    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository
     * @param  \MY\Service\Repositories\ServiceRepository

     * @return void
     */
    public function __construct(CategoryRepository $categoryRepository,
                                ServiceRepository $serviceRepository)
    {
        $this->_config = request('_config');

        $this->categoryRepository = $categoryRepository;

        $this->serviceRepository = $serviceRepository;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->categoryRepository->getCategoryTree();

        return view($this->_config['view'], compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'slug'        => ['required', 'unique:category_translations,slug', new \Webkul\Core\Contracts\Validations\Slug],
            'title'        => 'required',
            'organization'        => 'required',
            'position'        => 'required',
            'image.*'     => 'mimes:jpeg,jpg,bmp,png',
            'description' => 'required_if:display_mode,==,description_only,products_and_description',
        ]);

        $data = \request()->all();

//        dd($data);
        $this->serviceRepository->create($data);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Service']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $advert
     * @return \Illuminate\Http\Response
     */
    public function show(Service $advert)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Service  $advert
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = $this->serviceRepository->findOrFail($id);

        $categories = $this->categoryRepository->getCategoryTree();

        return view($this->_config['view'], compact('service', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $advert
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $locale = request()->get('locale') ?: app()->getLocale();

        $this->validate(request(), [
            $locale . '.slug' => ['required', new \Webkul\Core\Contracts\Validations\Slug, function ($attribute, $value, $fail) use ($id) {
                if (! $this->serviceRepository->isSlugUnique($id, $value)) {
                    $fail(trans('admin::app.response.already-taken', ['name' => 'Service']));
                }
            }],
            $locale . '.title' => 'required',
            $locale . '.organization' => 'required',
            'image.*'         => 'mimes:jpeg,jpg,bmp,png',
        ]);

        $this->serviceRepository->update(\request()->all(),$id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Service']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $advert
     * @return \Illuminate\Http\Response
     */
    public function destroy($id )
    {
        $service = $this->serviceRepository->findOrFail($id);

        try {
            $this->categoryRepository->delete($id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Service']));

            return response()->json(['message' => true], 200);
        } catch(\Exception $e) {
            report($e);

            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Service']));
        }

        return response()->json(['message' => false], 400);
    }

    public function massUpdate(){
        $data = request()->all();

        if (! isset($data['massaction-type'])) {
            return redirect()->back();
        }

        if (! $data['massaction-type'] == 'update') {
            return redirect()->back();
        }

        $serviceIds = explode(',', $data['indexes']);

        foreach ($serviceIds as $srvsId) {
            $this->serviceRepository->update([
                'locale'  => null,
                'status'  => $data['update-options'],
            ], $srvsId);
        }

        session()->flash('success', trans('service::app.catalog.services.mass-update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    public function massDestroy(){
        $serviceIds = explode(',', request()->input('indexes'));

        foreach ($serviceIds as $srvsId) {
            $service = $this->serviceRepository->find($srvsId);

            if (isset($service)) {
                $this->serviceRepository->delete($srvsId);
            }
        }

        session()->flash('success', trans('service::app.catalog.services.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }
}
