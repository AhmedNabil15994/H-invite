<?php

namespace Modules\Category\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Offer\Entities\Offer;
use Modules\Offer\Repositories\Frontend\OfferRepository;
use Illuminate\Http\Request;
class ShowCategoryController extends Controller
{
    public function __construct(OfferRepository $offer,Category $category)
    {
        $this->offer = $offer;
        $this->category = $category;
    }
    public function index(Request $request){
        $offers = $this->offer->getAll($request);
        return view('apps::Frontend.index',compact('offers'));
    }
    public function show(Category $category)
    {
        $ids_arr[$category->id] = $category->id;
        $ids = $category->children()->active()->orderBy('order','asc')->pluck('id');
        $ids = reset($ids);
        $ids_arr = !count($ids) ? [$category->id] : array_merge($ids_arr,$ids);
        $arr =[];

        foreach ($ids_arr as $category_id) {
            $newCategory = Category::find($category_id);
            $arr[$category_id] = [
                'offers'    => $newCategory->validOffers ?? [],
                'banner'    => $newCategory->getBanner($newCategory->id),
            ];
        }

        return view('category::frontend.categories.show', ['category' => $category->load('children'),'offers'=>$arr]);
    }
}
