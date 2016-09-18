<?php

namespace ErenMustafaOzdal\LaravelDealerModule\Http\Controllers;

use Illuminate\Http\Request;

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
use ErenMustafaOzdal\LaravelDealerModule\Http\Requests\Dealer\ApiStoreRequest;
use ErenMustafaOzdal\LaravelDealerModule\Http\Requests\Dealer\ApiUpdateRequest;


class DealerApiController extends BaseController
{
    /**
     * default urls of the model
     *
     * @var array
     */
    private $urls = [
        'publish'       => ['route' => 'api.dealer.publish', 'id' => true],
        'not_publish'   => ['route' => 'api.dealer.notPublish', 'id' => true],
        'edit_page'     => ['route' => 'admin.dealer.edit', 'id' => true]
    ];

    /**
     * default realtion urls of the model
     *
     * @var array
     */
    private $relationUrls = [
        'edit_page' => [
            'route'     => 'admin.dealer_category.dealer.edit',
            'id'        => 0,
            'model'     => ''
        ],
        'show' => [
            'route'     => 'admin.dealer_category.dealer.show',
            'id'        => 0,
            'model'     => ''
        ]
    ];

    /**
     * Display a listing of the resource.
     *
     * @param Request  $request
     * @param integer|null $id
     * @return Datatables
     */
    public function index(Request $request, $id = null)
    {
        // query
        if (is_null($id)) {
            $dealers = Dealer::with('category');
        } else {
            $dealers = DealerCategory::findOrFail($id)->dealers();
        }
        $dealers->select(['id', 'category_id', 'name', 'is_publish', 'created_at']);

        // if is filter action
        if ($request->has('action') && $request->input('action') === 'filter') {
            $dealers->filter($request);
        }

        // urls
        $addUrls = $this->urls;
        if( ! is_null($id)) {
            $this->relationUrls['edit_page']['id'] = $id;
            $this->relationUrls['edit_page']['model'] = config('laravel-dealer-module.url.dealer');
            $this->relationUrls['show']['id'] = $id;
            $this->relationUrls['show']['model'] = config('laravel-dealer-module.url.dealer');
            $addUrls = array_merge($addUrls, $this->relationUrls);
        }
        $addColumns = [
            'addUrls'           => $addUrls,
            'status'            => function($model) { return $model->is_publish; }
        ];
        $editColumns = [
            'name'              => function($model) { return $model->name_uc_first; },
            'category.name'     => function($model) { return $model->category->name_uc_first; },
            'created_at'        => function($model) { return $model->created_at_table; }
        ];
        $removeColumns = ['is_publish'];
        return $this->getDatatables($dealers, $addColumns, $editColumns, $removeColumns);
    }

    /**
     * get detail
     *
     * @param integer $id
     * @param Request $request
     * @return Datatables
     */
    public function detail($id, Request $request)
    {
        $dealer = Dealer::with(['category', 'province', 'county', 'district', 'neighborhood', 'postalCode'])
            ->where('id',$id)
            ->select(['id','category_id','name','province_id','county_id','district_id','neighborhood_id','postal_code_id','address','land_phone','mobile_phone','url','created_at','updated_at']);

        $editColumns = [
            'name'          => function($model) { return $model->name_uc_first; },
            'created_at'    => function($model) { return $model->created_at_table; },
            'updated_at'    => function($model) { return $model->updated_at_table; },
            'address'       => function($model) { return $model->full_address; },
            'category.name' => function($model) { return $model->category->name_uc_first; },
        ];
        $removeColumns = ['province_id','province','county_id','county','district_id','district','neighborhood_id','neighborhood','postal_code_id','postal_code'];
        return $this->getDatatables($dealer, [], $editColumns, $removeColumns);
    }

    /**
     * get model data for edit
     *
     * @param integer $id
     * @param Request $request
     * @return Dealer
     */
    public function fastEdit($id, Request $request)
    {
        return Dealer::with(['category','province','county','district','neighborhood','postalCode'])
            ->where('id',$id)
            ->first(['id','category_id','name','province_id','county_id','district_id','neighborhood_id','postal_code_id']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ApiStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApiStoreRequest $request)
    {
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        return $this->storeModel(Dealer::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Dealer $dealer
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, Dealer $dealer)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($dealer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Dealer  $dealer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dealer $dealer)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($dealer);
    }

    /**
     * publish model
     *
     * @param Dealer $dealer
     * @return \Illuminate\Http\Response
     */
    public function publish(Dealer $dealer)
    {
        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => true ] ]
        ]);
        return $this->updateAlias($dealer, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ]);
    }

    /**
     * not publish model
     *
     * @param Dealer $dealer
     * @return \Illuminate\Http\Response
     */
    public function notPublish(Dealer $dealer)
    {
        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => false ] ]
        ]);
        return $this->updateAlias($dealer, [
            'success'   => NotPublishSuccess::class,
            'fail'      => NotPublishFail::class
        ]);
    }

    /**
     * group action method
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function group(Request $request)
    {
        if ( $this->groupAlias(Dealer::class) ) {
            return response()->json(['result' => 'success']);
        }
        return response()->json(['result' => 'error']);
    }
}
