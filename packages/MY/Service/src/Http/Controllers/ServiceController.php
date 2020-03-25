<?php

namespace MY\Service\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;
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
     * ServiceFlatRepository object
     *
     * @var \MY\Service\Repositories\ServiceRepository
     */
    protected $serviceFlatRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository
     * @param  \MY\Service\Repositories\ServiceRepository

     * @return void
     */
    public function __construct(CategoryRepository $categoryRepository,
                                ServiceRepository $serviceRepository,
                                ServiceTranslationRepository $serviceFlatRepository)
    {
        $this->_config = request('_config');

        $this->categoryRepository = $categoryRepository;

        $this->serviceRepository = $serviceRepository;

        $this->serviceFlatRepository = $serviceFlatRepository;
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
    public function edit(Service $advert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $advert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $advert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $advert
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $advert)
    {
        //
    }

    public function massUpdate(){

    }

    public function massDestroy(){

    }
}
