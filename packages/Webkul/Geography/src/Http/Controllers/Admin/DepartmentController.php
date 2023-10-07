<?php

namespace Webkul\Geography\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\GeographyDataGrid;
use Webkul\Admin\DataGrids\GeographyProductDataGrid;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Geography\Repositories\DepartmentRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Geography\Http\Requests\DepartmentRequest;

class DepartmentController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\ChannelRepository  $channelRepository
     * @param  \Webkul\Geography\Repositories\DepartmentRepository  $departmentRepository
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @return void
     */
    public function __construct(
        protected ChannelRepository $channelRepository,
        protected DepartmentRepository $departmentRepository,
        protected AttributeRepository $attributeRepository
    )
    {
        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        echo "hola mundo HAHAHAHAHAHAH";
        /*if (request()->ajax()) {
            return app(DepartmentDataGrid::class)->toJson();
        }*/

        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $departments = $this->departmentRepository->getDepartmentTree(null, ['id']);

        $attributes = $this->attributeRepository->findWhere(['is_filterable' => 1]);

        return view($this->_config['view'], compact('departments', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Webkul\Geography\Http\Requests\DepartmentRequest  $departmentRequest
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $departmentRequest)
    {
        Event::dispatch('catalog.geography.create.before');

        $department = $this->departmentRepository->create($departmentRequest->all());

        //Event::dispatch('catalog.geography.create.after', $department);

        session()->flash('success', trans('admin::app.catalog.department.create-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $department = $this->departmentRepository->findOrFail($id);

        $departments = $this->departmentRepository->getDepartmentTreeWithoutDescendant($id);

        $attributes = $this->attributeRepository->findWhere(['is_filterable' => 1]);

        return view($this->_config['view'], compact('department', 'departments', 'attributes'));
    }

    /**
     * Show the products of specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function products($id)
    {
        if (request()->ajax()) {
            return app(GeographyProductDataGrid::class)->toJson();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\Geography\Http\Requests\DepartmentRequest  $departmentRequest
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentRequest $departmentRequest, $id)
    {
        Event::dispatch('catalog.geography.update.before', $id);

        $department = $this->departmentRepository->update($departmentRequest->all(), $id);

        //Event::dispatch('catalog.geography.update.after', $department);

        session()->flash('success', trans('admin::app.catalog.departments.update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = $this->departmentRepository->findOrFail($id);

        if ($this->isDepartmentDeletable($department)) {
            return response()->json(['message' => trans('admin::app.catalog.geographys.delete-geography-root')], 400);
        }

        try {
            Event::dispatch('catalog.geography.delete.before', $id);

            $this->departmentRepository->delete($id);

            Event::dispatch('catalog.geography.delete.after', $id);

            return response()->json(['message' => trans('admin::app.catalog.geographys.delete-success')]);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.catalog.geographys.delete-failed')], 500);
    }

    /**
     * Remove the specified resources from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $suppressFlash = true;
        
        $geographyIds = explode(',', request()->input('indexes'));

        foreach ($geographyIds as $geographyId) {
            $geography = $this->departmentRepository->find($geographyId);

            if (isset($geography)) {
                if ($this->isGeographyDeletable($geography)) {
                    $suppressFlash = false;

                    session()->flash('warning', trans('admin::app.response.delete-geography-root', ['name' => 'Geography']));
                } else {
                    try {
                        $suppressFlash = true;

                        Event::dispatch('catalog.geography.delete.before', $geographyId);

                        $this->departmentRepository->delete($geographyId);

                        Event::dispatch('catalog.geography.delete.after', $geographyId);
                    } catch (\Exception $e) {
                        session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Geography']));
                    }
                }
            }
        }

        if (
            count($geographyIds) != 1
            || $suppressFlash == true
        ) {
            session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', ['resource' => 'Geography']));
        }

        return redirect()->route($this->_config['redirect']);
    }

     /**
     * Mass update Geography.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate()
    {
        $data = request()->all();

        if (
            ! isset($data['mass-action-type'])
            || $data['mass-action-type'] != 'update'
        ) {
            return redirect()->back();
        }

        $geographyIds = explode(',', $data['indexes']);

        foreach ($geographyIds as $geographyId) {
            Event::dispatch('catalog.geographys.mass-update.before', $geographyId);

            $geography = $this->departmentRepository->find($geographyId);

            $geography->status = $data['update-options'];
            $geography->save();

            Event::dispatch('catalog.geographys.mass-update.after', $geography);
        }

        session()->flash('success', trans('admin::app.catalog.geographys.mass-update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Get geography product count.
     *
     * @return \Illuminate\Http\Response
     */
    public function geographyProductCount()
    {
        $productCount = 0;
        
        $indexes = explode(',', request()->input('indexes'));

        foreach ($indexes as $index) {
            $geography = $this->departmentRepository->find($index);

            $productCount += $geography->products->count();
        }

        return response()->json(['product_count' => $productCount]);
    }

    /**
     * Check whether the current geography is deletable or not.
     *
     * This method will fetch all root geography ids from the channel. If `id` is present,
     * then it is not deletable.
     *
     * @param  \Webkul\Geography\Contracts\Geography $geography
     * @return bool
     */
    private function isGeographyDeletable($geography)
    {
        static $channelRootGeographyIds;

        if (! $channelRootGeographyIds) {
            $channelRootGeographyIds = $this->channelRepository->pluck('root_geography_id');
        }

        return $geography->id === 1 || $channelRootGeographyIds->contains($geography->id);
    }
}
