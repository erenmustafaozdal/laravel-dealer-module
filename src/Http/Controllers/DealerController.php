<?php

namespace ErenMustafaOzdal\LaravelDealerModule\Http\Controllers;

use App\Http\Requests;
use App\Dealer;
use App\DealerCategory;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseController;
// events
use ErenMustafaOzdal\LaravelDealerModule\Events\Dealer\StoreSuccess;
use ErenMustafaOzdal\LaravelDealerModule\Events\Dealer\StoreFail;
use ErenMustafaOzdal\LaravelDealerModule\Events\Dealer\UpdateSuccess;
use ErenMustafaOzdal\LaravelDealerModule\Events\Dealer\UpdateFail;
use ErenMustafaOzdal\LaravelDealerModule\Events\Dealer\DestroySuccess;
use ErenMustafaOzdal\LaravelDealerModule\Events\Dealer\DestroyFail;
use ErenMustafaOzdal\LaravelDealerModule\Events\Dealer\PublishSuccess;
use ErenMustafaOzdal\LaravelDealerModule\Events\Dealer\PublishFail;
use ErenMustafaOzdal\LaravelDealerModule\Events\Dealer\NotPublishSuccess;
use ErenMustafaOzdal\LaravelDealerModule\Events\Dealer\NotPublishFail;
// requests
use ErenMustafaOzdal\LaravelDealerModule\Http\Requests\Dealer\StoreRequest;
use ErenMustafaOzdal\LaravelDealerModule\Http\Requests\Dealer\UpdateRequest;

class DealerController extends BaseController
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
            return view(config('laravel-dealer-module.views.dealer.index'));
        }

        $dealer_category = DealerCategory::findOrFail($id);
        return view(config('laravel-dealer-module.views.dealer.index'), compact('dealer_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        $operation = 'create';
        if (is_null($id)) {
            return view(config('laravel-dealer-module.views.dealer.create'), compact('operation'));
        }

        $dealer_category = DealerCategory::findOrFail($id);
        return view(config('laravel-dealer-module.views.dealer.create'), compact('dealer_category','operation'));
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
        if (is_null($id)) {
            $redirect = 'index';
        } else {
            $redirect = 'dealer_category.dealer.index';
            $this->setRelationRouteParam($id, config('laravel-dealer-module.url.dealer'));
        }

        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        return $this->storeModel(Dealer::class,$redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param integer|Dealer $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function show($firstId, $secondId = null)
    {
        $dealer = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-dealer-module.views.dealer.show'), compact('dealer'));
        }

        $dealer_category = DealerCategory::findOrFail($firstId);
        return view(config('laravel-dealer-module.views.dealer.show'), compact('dealer', 'dealer_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer|Dealer $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function edit($firstId, $secondId = null)
    {
        $operation = 'edit';
        $dealer = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-dealer-module.views.dealer.edit'), compact('dealer','operation'));
        }

        $dealer_category = DealerCategory::findOrFail($firstId);
        return view(config('laravel-dealer-module.views.dealer.edit'), compact('dealer', 'dealer_category','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param integer|Dealer $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $firstId, $secondId = null)
    {
        $dealer = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'dealer_category.dealer.show';
            $this->setRelationRouteParam($firstId, config('laravel-dealer-module.url.dealer'));
        }

        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($dealer,$redirect, true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer|Dealer $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function destroy($firstId, $secondId = null)
    {
        $dealer = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'index';
        } else {
            $redirect = 'dealer_category.dealer.index';
            $this->setRelationRouteParam($firstId, config('laravel-dealer-module.url.dealer'));
        }

        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($dealer,$redirect);
    }

    /**
     * publish model
     *
     * @param integer|Dealer $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function publish($firstId, $secondId = null)
    {
        $dealer = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'dealer_category.dealer.show';
            $this->setRelationRouteParam($firstId, config('laravel-dealer-module.url.dealer'));
        }

        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => true ] ]
        ]);
        return $this->updateAlias($dealer, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ],$redirect);
    }

    /**
     * not publish model
     *
     * @param integer|Dealer $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function notPublish($firstId, $secondId = null)
    {
        $dealer = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'dealer_category.dealer.show';
            $this->setRelationRouteParam($firstId, config('laravel-dealer-module.url.dealer'));
        }

        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => false ] ]
        ]);
        return $this->updateAlias($dealer, [
            'success'   => NotPublishSuccess::class,
            'fail'      => NotPublishFail::class
        ],$redirect);
    }
}
