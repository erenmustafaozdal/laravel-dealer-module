<?php

namespace ErenMustafaOzdal\LaravelDealerModule\Http\Controllers;


use App\Http\Requests;
use Illuminate\Http\Request;
use App\DealerCategory;
use App\Dealer;
use Laracasts\Flash\Flash;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseNodeController;
// events
use ErenMustafaOzdal\LaravelDealerModule\Events\DealerCategory\StoreSuccess;
use ErenMustafaOzdal\LaravelDealerModule\Events\DealerCategory\StoreFail;
use ErenMustafaOzdal\LaravelDealerModule\Events\DealerCategory\UpdateSuccess;
use ErenMustafaOzdal\LaravelDealerModule\Events\DealerCategory\UpdateFail;
use ErenMustafaOzdal\LaravelDealerModule\Events\DealerCategory\DestroySuccess;
use ErenMustafaOzdal\LaravelDealerModule\Events\DealerCategory\DestroyFail;
// requests
use ErenMustafaOzdal\LaravelDealerModule\Http\Requests\DealerCategory\StoreRequest;
use ErenMustafaOzdal\LaravelDealerModule\Http\Requests\DealerCategory\UpdateRequest;

class DealerCategoryController extends BaseNodeController
{
    /**
     * Display a listing of the resource.
     *
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        if (is_null($id)) {
            return view(config('laravel-dealer-module.views.dealer_category.index'));
        }

        $parent_dealer_category = DealerCategory::findOrFail($id);
        return view(config('laravel-dealer-module.views.dealer_category.index'), compact('parent_dealer_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id = null)
    {
        // eğer dealer ids var ve token hatalı ise hata döndür
        if ( $request->has('dealer_ids') && ( ! $request->has('_token') || session('_token') !== $request->input('_token') ) ) {
            abort(403);
        }

        // dealer ids var ise oluştur
        $dealers = $request->has('dealer_ids') ? Dealer::with('video','photo')->whereIn('id', explode(',', $request->dealer_ids))->get() : collect();

        $operation = 'create';
        if (is_null($id)) {
            return view(config('laravel-dealer-module.views.dealer_category.create'), compact('operation','dealers'));
        }

        $parent_dealer_category = DealerCategory::findOrFail($id);
        $type = $parent_dealer_category->type;
        $types = $dealers->groupBy('type');

        // kategori olduğu için; gelen medyalar kategorinin tipine uygun olmalı
        if ( $type !== 'mixed' && ($types->count() === 1 && $types->keys()->first() !== $type) ) {
            Flash::error(lmcTrans('laravel-dealer-module/admin.flash.dealer_incompatible', [
                'type' => lmcTrans("laravel-dealer-module/admin.fields.dealer.{$type}")
            ]));
            return back();
        }
        return view(config('laravel-dealer-module.views.dealer_category.create'), compact('parent_dealer_category','operation','dealers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $id = null)
    {
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        if (is_null($id)) {
            $redirect = 'index';
            return $this->storeModel(DealerCategory::class,$redirect);
        }
        $redirect = 'dealer_category.dealer_category.index';
        $this->setRelationRouteParam($id, config('laravel-dealer-module.url.dealer_category'));
        $this->setDefineValues(['type']);
        return $this->storeNode(DealerCategory::class,$redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param integer|DealerCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function show($firstId, $secondId = null)
    {
        $dealer_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-dealer-module.views.dealer_category.show'), compact('dealer_category'));
        }

        $parent_dealer_category = DealerCategory::findOrFail($firstId);
        return view(config('laravel-dealer-module.views.dealer_category.show'), compact('parent_dealer_category','dealer_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer|DealerCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function edit($firstId, $secondId = null)
    {
        $operation = 'edit';
        $dealer_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-dealer-module.views.dealer_category.edit'), compact('dealer_category','operation'));
        }

        $parent_dealer_category = DealerCategory::findOrFail($firstId);
        return view(config('laravel-dealer-module.views.dealer_category.edit'), compact('parent_dealer_category','dealer_category','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param integer|DealerCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $firstId, $secondId = null)
    {
        $dealer_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'dealer_category.dealer_category.show';
            $this->setRelationRouteParam($firstId, config('laravel-dealer-module.url.dealer_category'));
        }

        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($dealer_category, $redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer|DealerCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function destroy($firstId, $secondId = null)
    {
        $dealer_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'index';
        } else {
            $redirect = 'dealer_category.dealer_category.index';
            $this->setRelationRouteParam($firstId, config('laravel-dealer-module.url.dealer_category'));
        }

        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($dealer_category, $redirect);
    }
}
