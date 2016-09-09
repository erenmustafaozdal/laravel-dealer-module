<?php

namespace ErenMustafaOzdal\LaravelDealerModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\DealerCategory;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseNodeController;
// events
use ErenMustafaOzdal\LaravelDealerModule\Events\DealerCategory\StoreSuccess;
use ErenMustafaOzdal\LaravelDealerModule\Events\DealerCategory\StoreFail;
use ErenMustafaOzdal\LaravelDealerModule\Events\DealerCategory\UpdateSuccess;
use ErenMustafaOzdal\LaravelDealerModule\Events\DealerCategory\UpdateFail;
use ErenMustafaOzdal\LaravelDealerModule\Events\DealerCategory\DestroySuccess;
use ErenMustafaOzdal\LaravelDealerModule\Events\DealerCategory\DestroyFail;
use ErenMustafaOzdal\LaravelDealerModule\Events\DealerCategory\MoveSuccess;
use ErenMustafaOzdal\LaravelDealerModule\Events\DealerCategory\MoveFail;
// requests
use ErenMustafaOzdal\LaravelDealerModule\Http\Requests\DealerCategory\ApiStoreRequest;
use ErenMustafaOzdal\LaravelDealerModule\Http\Requests\DealerCategory\ApiUpdateRequest;
use ErenMustafaOzdal\LaravelDealerModule\Http\Requests\DealerCategory\ApiMoveRequest;
// services
use LMBCollection;


class DealerCategoryApiController extends BaseNodeController
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @param integer|null $id
     * @return array
     */
    public function index(Request $request, $id = null)
    {
        return $this->getNodes(DealerCategory::class, $id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ApiStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApiStoreRequest $request)
    {
        $this->setDefineValues(['type']);
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        return $this->storeNode(DealerCategory::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  DealerCategory $dealer_category
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, DealerCategory $dealer_category)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        $this->updateModel($dealer_category);

        return [
            'id'        => $dealer_category->id,
            'name'      => $dealer_category->name_uc_first
        ];
    }

    /**
     * Move the specified node.
     *
     * @param  ApiMoveRequest $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function move(ApiMoveRequest $request, $id)
    {
        $dealer_category = DealerCategory::findOrFail($id);
        $this->setDefineValues(['type']);
        $this->setEvents([
            'success'   => MoveSuccess::class,
            'fail'      => MoveFail::class
        ]);
        return $this->moveModel($dealer_category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DealerCategory  $dealer_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(DealerCategory $dealer_category)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($dealer_category);
    }

    /**
     * get roles with query
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function models(Request $request)
    {
        if($request->has('id')) {
            $dealer_category = DealerCategory::find($request->input('id'));
            $models = $dealer_category->descendants()->where('name', 'like', "%{$request->input('query')}%");

        } else {
            $models = DealerCategory::where('name', 'like', "%{$request->input('query')}%");
        }

        $models = $models->get(['id','parent_id','lft','rgt','depth','name'])
            ->toHierarchy();
        return LMBCollection::relationRender($models, 'children', '/', ['name']);
    }
}
