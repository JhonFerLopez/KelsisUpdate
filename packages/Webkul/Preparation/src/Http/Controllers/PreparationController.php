<?php

namespace Webkul\Preparation\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\PreparationDataGrid;
use Webkul\Admin\DataGrids\PreparationProductDataGrid;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Preparation\Repositories\PreparationRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Preparation\Http\Requests\PreparationRequest;

class PreparationController extends Controller
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
     * @param  \Webkul\Preparation\Repositories\PreparationRepository  $preparationRepository
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @return void
     */
    public function __construct(
        protected ChannelRepository $channelRepository,
        protected PreparationRepository $preparationRepository,
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
        if (request()->ajax()) {
            return app(PreparationDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $preparations = $this->preparationRepository->getPreparationTree(null, ['id']);

        $attributes = $this->attributeRepository->findWhere(['is_filterable' => 1]);

        return view($this->_config['view'], compact('preparations', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Webkul\Preparation\Http\Requests\PreparationRequest  $preparationRequest
     * @return \Illuminate\Http\Response
     */
    public function store(PreparationRequest $preparationRequest)
    {
        Event::dispatch('catalog.preparation.create.before');

        $preparation = $this->preparationRepository->create($preparationRequest->all());

        Event::dispatch('catalog.preparation.create.after', $preparation);

        session()->flash('success', trans('admin::app.catalog.preparations.create-success'));

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
        $preparation = $this->preparationRepository->findOrFail($id);

        $preparations = $this->preparationRepository->getPreparationTreeWithoutDescendant($id);

        $attributes = $this->attributeRepository->findWhere(['is_filterable' => 1]);

        return view($this->_config['view'], compact('preparation', 'preparations', 'attributes'));
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
            return app(PreparationProductDataGrid::class)->toJson();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\Preparation\Http\Requests\PreparationRequest  $preparationRequest
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PreparationRequest $preparationRequest, $id)
    {
        Event::dispatch('catalog.preparation.update.before', $id);

        $preparation = $this->preparationRepository->update($preparationRequest->all(), $id);

        Event::dispatch('catalog.preparation.update.after', $preparation);

        session()->flash('success', trans('admin::app.catalog.preparations.update-success'));

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
        $preparation = $this->preparationRepository->findOrFail($id);

        if ($this->isPreparationDeletable($preparation)) {
            return response()->json(['message' => trans('admin::app.catalog.preparations.delete-preparation-root')], 400);
        }

        try {
            Event::dispatch('catalog.preparation.delete.before', $id);

            $this->preparationRepository->delete($id);

            Event::dispatch('catalog.preparation.delete.after', $id);

            return response()->json(['message' => trans('admin::app.catalog.preparations.delete-success')]);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.catalog.preparations.delete-failed')], 500);
    }

    /**
     * Remove the specified resources from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $suppressFlash = true;
        
        $preparationIds = explode(',', request()->input('indexes'));

        foreach ($preparationIds as $preparationId) {
            $preparation = $this->preparationRepository->find($preparationId);

            if (isset($preparation)) {
                if ($this->isPreparationDeletable($preparation)) {
                    $suppressFlash = false;

                    session()->flash('warning', trans('admin::app.response.delete-preparation-root', ['name' => 'Preparation']));
                } else {
                    try {
                        $suppressFlash = true;

                        Event::dispatch('catalog.preparation.delete.before', $preparationId);

                        $this->preparationRepository->delete($preparationId);

                        Event::dispatch('catalog.preparation.delete.after', $preparationId);
                    } catch (\Exception $e) {
                        session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Preparation']));
                    }
                }
            }
        }

        if (
            count($preparationIds) != 1
            || $suppressFlash == true
        ) {
            session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', ['resource' => 'Preparation']));
        }

        return redirect()->route($this->_config['redirect']);
    }

     /**
     * Mass update Preparation.
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

        $preparationIds = explode(',', $data['indexes']);

        foreach ($preparationIds as $preparationId) {
            Event::dispatch('catalog.preparations.mass-update.before', $preparationId);

            $preparation = $this->preparationRepository->find($preparationId);

            $preparation->status = $data['update-options'];
            $preparation->save();

            Event::dispatch('catalog.preparations.mass-update.after', $preparation);
        }

        session()->flash('success', trans('admin::app.catalog.preparations.mass-update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Get preparation product count.
     *
     * @return \Illuminate\Http\Response
     */
    public function preparationProductCount()
    {
        $productCount = 0;
        
        $indexes = explode(',', request()->input('indexes'));

        foreach ($indexes as $index) {
            $preparation = $this->preparationRepository->find($index);

            $productCount += $preparation->products->count();
        }

        return response()->json(['product_count' => $productCount]);
    }

    /**
     * Check whether the current preparation is deletable or not.
     *
     * This method will fetch all root preparation ids from the channel. If `id` is present,
     * then it is not deletable.
     *
     * @param  \Webkul\Preparation\Contracts\Preparation $preparation
     * @return bool
     */
    private function isPreparationDeletable($preparation)
    {
        static $channelRootPreparationIds;

        if (! $channelRootPreparationIds) {
            $channelRootPreparationIds = $this->channelRepository->pluck('root_preparation_id');
        }

        return $preparation->id === 1 || $channelRootPreparationIds->contains($preparation->id);
    }
}
