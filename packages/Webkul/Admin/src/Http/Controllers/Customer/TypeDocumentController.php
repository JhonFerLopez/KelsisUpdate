<?php

namespace Webkul\Admin\Http\Controllers\Customer;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\DataGrids\TypeDocumentDataGrid;
use Webkul\Admin\DataGrids\CustomerGroupDataGrid;
use Webkul\Customer\Repositories\TypeDocumentRepository;
use Webkul\Customer\Contracts\Validations\Prefix;

class TypeDocumentController extends Controller
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
     * @param  \Webkul\Customer\Repositories\TypeDocumentRepository  $typeDocumentRepository;
     * @return void
     */
    public function __construct(protected TypeDocumentRepository $typeDocumentRepository)
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
            return app(TypeDocumentDataGrid::class)->toJson();
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
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'prefijo' => ['required', 'unique:type_document,prefijo', new Prefix],
            'name' => 'required',
        ]);

        Event::dispatch('customer.type_document.create.before');

        $typeDocument = $this->typeDocumentRepository->create(array_merge(request()->all()));

        Event::dispatch('customer.type_document.create.after', $typeDocument);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Tipo de documento']));

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
        $typedocument = $this->typeDocumentRepository->findOrFail($id);

        return view($this->_config['view'], compact('typedocument'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'prefijo' => ['required', 'unique:type_document,prefijo,' . $id, new Prefix],
            'name' => 'required',
        ]);

        Event::dispatch('customer.type_document.update.before', $id);

        $typeDocument = $this->typeDocumentRepository->update(request()->all(), $id);

        Event::dispatch('customer.type_document.update.after', $typeDocument);

        session()->flash('success', trans('admin::app.customers.typedocument.update-success'));

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
        $customerGroup = $this->typeDocumentRepository->findOrFail($id);

        /*if (! $customerGroup->is_user_defined) {
            return response()->json([
                'message' => trans('admin::app.customers.customers.group-default'),
            ], 400);
        }

        if ($customerGroup->customers->count()) {
            return response()->json([
                'message' => trans('admin::app.customers.groups.customer-associate'),
            ], 400);
        }*/

        try {
            Event::dispatch('customer.type_document.delete.before', $id);

            $this->typeDocumentRepository->delete($id);

            Event::dispatch('customer.type_document.delete.after', $id);

            return response()->json(['message' => trans('admin::app.customers.typedocument.delete-success')]);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.customers.typedocument.delete-failed')], 500);
    }
}
