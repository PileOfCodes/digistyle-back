<?php

use App\Http\Controllers\Admin\AdminAttributeController;
use App\Http\Controllers\Admin\AdminBrandController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminChildCategoryController;
use App\Http\Controllers\Admin\AdminDemandController;
use App\Http\Controllers\Admin\AdminDiscountController;
use App\Http\Controllers\Admin\AdminFilterController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminSubCategoryController;
use App\Http\Controllers\Admin\TrashedCategoriesController;
use App\Http\Controllers\Admin\TrashedChildCategoriesController;
use App\Http\Controllers\Admin\TrashedSubCategoriesController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\TrashedAttributeController;
use App\Http\Controllers\Admin\TrashedProductController;
use App\Http\Controllers\Admin\ValueController;
use App\Http\Controllers\Admin\TrashedValueController;
use App\Http\Controllers\Admin\TrashedColorController;
use App\Http\Controllers\Admin\WarrantController;
use App\Http\Controllers\Admin\TrashedWarrantyController;
use App\Http\Controllers\Admin\AdminParentCategoryController;
use App\Http\Controllers\Admin\AdminSellerController;
use App\Http\Controllers\Admin\AdminSingleCategoryController;
use App\Http\Controllers\Admin\BrandCategoryController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\SliderController as AdminSliderController;
use App\Http\Controllers\Admin\TrashedBrandCategoryController;
use App\Http\Controllers\Admin\TrashedBrandController;
use App\Http\Controllers\Admin\TrashedCollectionController;
use App\Http\Controllers\Admin\TrashedDemandController;
use App\Http\Controllers\Admin\TrashedDiscountController;
use App\Http\Controllers\Admin\TrashedFilterController;
use App\Http\Controllers\Admin\TrashedParentCategoryController;
use App\Http\Controllers\Admin\TrashedSellerController;
use App\Http\Controllers\Admin\TrashedSingleCategoryController;
use App\Http\Controllers\Admin\TrashedSizeController;
use App\Http\Controllers\Admin\TrashedSliderController;
use App\Http\Controllers\Admin\WeekstyleController;
use App\Http\Controllers\AuthController as ControllersAuthController;
use App\Http\Controllers\FavoriteBrandsController;
use App\Http\Controllers\Front\BrandCategoryController as FrontBrandCategoryController;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\ChildCategoryController;
use App\Http\Controllers\Front\CollectionController as FrontCollectionController;
use App\Http\Controllers\Front\DiscountController;
use App\Http\Controllers\Front\NavbarBrandController;
use App\Http\Controllers\Front\ParentCategoryController;
use App\Http\Controllers\Front\SellerController;
use App\Http\Controllers\Front\SingleCategoryController;
use App\Http\Controllers\Front\SingleProductController;
use App\Http\Controllers\Front\SliderController;
use App\Http\Controllers\Front\SubCategoryController;
use App\Http\Controllers\landingController;
use App\Http\Controllers\MostVisitedController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\ShegeftController;
use App\Http\Resources\Front\ProductResource;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Discount;
use App\Models\ParentCategory;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Seller;
use App\Models\Size;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// admin routes
Route::prefix('admin')->group(function() {
    //======================= admin parentCategories ===================//
    Route::apiResource('parentCategory', AdminParentCategoryController::class);
    Route::get('parent/trashed', [TrashedParentCategoryController::class,'trashedParent']);
    Route::get('parent/{id}/restore', [TrashedParentCategoryController::class,'restoreParent']);
    Route::get('parent/{id}/forceDelete', [TrashedParentCategoryController::class,'forceDeleteParent']);
    Route::put('change-parentStatus/{parentCategory}', [AdminParentCategoryController::class, 'changeParentStatus']);
    //======================= admin categories ===================//
    Route::apiResource('categories', AdminCategoryController::class);
    Route::get('category/trashed', [TrashedCategoriesController::class,'trashedCategories']);
    Route::get('category/{id}/restore', [TrashedCategoriesController::class,'restoreCategory']);
    Route::get('category/{id}/forceDelete', [TrashedCategoriesController::class,'forceDeleteCategory']);
    Route::put('change-categoryStatus/{category}', [AdminCategoryController::class, 'changeCategoryStatus']);
    //======================= admin single categories ===================//
    Route::apiResource('singleCategories', AdminSingleCategoryController::class);
    Route::get('singleCategory/trashed', [TrashedSingleCategoryController::class,'trashedSingleCategories']);
    Route::get('singleCategory/{id}/restore', [TrashedSingleCategoryController::class,'restoreSingleCategory']);
    Route::get('singleCategory/{id}/forceDelete', [TrashedSingleCategoryController::class,'forceDeleteSingleCategory']);
    Route::put('change-singleCategoryStatus/{singleCategory}', [AdminSingleCategoryController::class, 'changeSingleCategoryStatus']);
    //======================= admin sub categories ===============//
    Route::apiResource('sub-categories', AdminSubCategoryController::class);
    Route::get('subCategory/trashed', [TrashedSubCategoriesController::class,'trashedSubCategories']);
    Route::get('subCategory/{id}/restore', [TrashedSubCategoriesController::class,'restoreSubCategory']);
    Route::get('subCategory/{id}/forceDelete', [TrashedSubCategoriesController::class,'forceDeleteSubCategory']);
    Route::put('change-subCategoryStatus/{subCategory}', [AdminSubCategoryController::class, 'changeSubCategoryStatus']);
    //======================= admin children categories ============//
    Route::apiResource('child-categories', AdminChildCategoryController::class);
    Route::put('change-childCategoryStatus/{childCategory}', [AdminChildCategoryController::class, 'changeChildCategoryStatus']);
    Route::get('childCategory/trashed', [TrashedChildCategoriesController::class,'trashedChildCategories']);
    Route::get('childCategory/{id}/restore', [TrashedChildCategoriesController::class,'restoreChildCategory']);
    Route::get('childCategory/{id}/forceDelete', [TrashedChildCategoriesController::class,'forceDeleteChildCategory']);
    //======================= admin filters ============//
    Route::apiResource('filters', AdminFilterController::class);
    Route::put('change-filterStatus/{filter}', [AdminFilterController::class, 'changefilterStatus']);
    Route::get('filter/trashed', [TrashedFilterController::class,'trashedFilters']);
    Route::get('filter/{id}/restore', [TrashedFilterController::class,'restoreFilter']);
    Route::get('filter/{id}/forceDelete', [TrashedFilterController::class,'forceDeleteFilter']);
    //======================= admin brands ============//
    Route::apiResource('brands', AdminBrandController::class);
    Route::put('change-brandStatus/{brand}', [AdminBrandController::class, 'changebrandStatus']);
    Route::get('brand/trashed', [TrashedBrandController::class,'trashedBrands']);
    Route::get('brand/{id}/restore', [TrashedBrandController::class,'restorebrand']);
    Route::get('brand/{id}/forceDelete', [TrashedBrandController::class,'forceDeleteBrand']);
    //======================= admin category brands ============//
    Route::apiResource('brand-categories', BrandCategoryController::class);
    Route::put('change-categoryBrandStatus/{brandCategory}', [BrandCategoryController::class, 'changeCategoryBrandStatus']);
    Route::get('brandCategory/trashed', [TrashedBrandCategoryController::class,'trashedCategoryBrands']);
    Route::get('brandCategory/{id}/restore', [TrashedBrandCategoryController::class,'restoreCategoryBrand']);
    Route::get('brandCategory/{id}/forceDelete', [TrashedBrandCategoryController::class,'forceDeleteCategoryBrand']);
    //======================= admin authentication =============// 
    Route::prefix('auth')->group(function() {
        Route::post('register',[AuthController::class, 'register']);
        Route::post('login',[AuthController::class, 'login']);
        Route::post('logout',[AuthController::class, 'logout']);
    });
    //========================= admin colors ====================//
    Route::apiResource('sizes',SizeController::class);
    Route::put('change-sizeStatus/{size}', [SizeController::class, 'changeSizeStatus']);
    Route::get('size/trashed', [TrashedSizeController::class,'trashedSizes']);
    Route::get('size/{id}/restore', [TrashedSizeController::class,'restoresize']);
    Route::get('size/{id}/forceDelete', [TrashedSizeController::class,'forceDeleteSize']); 
    //========================= admin sizes ====================//
    Route::apiResource('colors',ColorController::class);
    Route::put('change-colorStatus/{color}', [ColorController::class, 'changeColorStatus']);
    Route::get('color/trashed', [TrashedColorController::class,'trashedColors']);
    Route::get('color/{id}/restore', [TrashedColorController::class,'restoreColor']);
    Route::get('color/{id}/forceDelete', [TrashedColorController::class,'forceDeleteColor']); 
    //========================= admin attributes ====================//
    Route::apiResource('attributes', AdminAttributeController::class);
    Route::put('change-attributeStatus/{attribute}', [AdminAttributeController::class, 'changeAttributeStatus']);
    Route::get('attribute/trashed', [TrashedAttributeController::class,'trashedAttributes']);
    Route::get('attribute/{id}/restore', [TrashedAttributeController::class,'restoreAttribute']);
    Route::get('attribute/{id}/forceDelete', [TrashedAttributeController::class,'forceDeleteAttribute']);  
    //========================= admin attribute values ====================//
    Route::apiResource('attribute-values', ValueController::class);
    Route::put('change-valueStatus/{attributeValue}', [ValueController::class, 'changeValueStatus']);
    Route::get('attribute-value/trashed', [TrashedValueController::class,'trashedValues']);
    Route::get('attribute-value/{id}/restore', [TrashedValueController::class,'restoreValue']);
    Route::get('attribute-value/{id}/forceDelete', [TrashedValueController::class,'forceDeleteValue']);  
    //========================= admin warranties =====================//
    Route::apiResource('warranties', WarrantController::class); 
    Route::put('change-warrantyStatus/{warranty}', [WarrantController::class, 'changeWarrantyStatus']);
    Route::get('warranty/trashed', [TrashedWarrantyController::class,'trashedWarranties']);
    Route::get('warranty/{id}/restore', [TrashedWarrantyController::class,'restoreWarranty']);
    Route::get('warranty/{id}/forceDelete', [TrashedWarrantyController::class,'forceDeleteWarranty']); 
    //========================= admin discounts =====================//
    Route::apiResource('discounts', AdminDiscountController::class); 
    Route::put('change-discountStatus/{discount}', [AdminDiscountController::class, 'changeDiscountStatus']);
    Route::get('discount/trashed', [TrashedDiscountController::class,'trashedDiscounts']);
    Route::get('discount/{id}/restore', [TrashedDiscountController::class,'restoreDiscount']);
    Route::get('discount/{id}/forceDelete', [TrashedDiscountController::class,'forceDeleteDiscount']); 
    //========================= admin collections =====================//
    Route::apiResource('collections', CollectionController::class); 
    Route::put('change-collectionStatus/{collection}', [CollectionController::class, 'changeCollectionStatus']);
    Route::get('collection/trashed', [TrashedCollectionController::class,'trashedCollection']);
    Route::get('collection/{id}/restore', [TrashedCollectionController::class,'restoreCollection']);
    Route::get('collection/{id}/forceDelete', [TrashedCollectionController::class,'forceDeleteCollection']); 
    //========================= admin sellers =====================//
    Route::apiResource('sellers', AdminSellerController::class); 
    Route::put('change-sellerStatus/{seller}', [AdminSellerController::class, 'changeSellerStatus']);
    Route::get('seller/trashed', [TrashedSellerController::class,'trashedSeller']);
    Route::get('seller/{id}/restore', [TrashedSellerController::class,'restoreSeller']);
    Route::get('seller/{id}/forceDelete', [TrashedSellerController::class,'forceDeleteSeller']); 
    //========================= admin demands =====================//
    Route::apiResource('demands', AdminDemandController::class); 
    Route::put('change-DemandStatus/{demand}', [AdminDemandController::class, 'changeDemandStatus']);
    Route::get('demand/trashed', [TrashedDemandController::class,'trashedDemands']);
    Route::get('demand/{id}/restore', [TrashedDemandController::class,'restoreDemand']);
    Route::get('demand/{id}/forceDelete', [TrashedDemandController::class,'forceDeleteDemand']); 
    //========================= admin sliders =====================//
    Route::apiResource('sliders', AdminSliderController::class); 
    Route::put('change-sliderStatus/{slider}', [AdminSliderController::class, 'changeSliderStatus']);
    Route::get('slider/trashed', [TrashedSliderController::class,'trashedSlider']);
    Route::get('slider/{id}/restore', [TrashedSliderController::class,'restoreSlider']);
    Route::get('slider/{id}/forceDelete', [TrashedSliderController::class,'forceDeleteSlider']); 
    //========================= admin products =====================//
    Route::apiResource('products', AdminProductController::class); 
    Route::put('change-productStatus/{product}', [AdminProductController::class, 'changeProductStatus']);
    Route::get('product/trashed', [TrashedProductController::class,'trashedProduct']);
    Route::get('product/{id}/restore', [TrashedProductController::class,'restoreProduct']);
    Route::get('product/{id}/forceDelete', [TrashedProductController::class,'forceDeleteProduct']); 
    //========================= admin weekstyle =====================//
    Route::apiResource('weekstyles', WeekstyleController::class); 
});

Route::get('category-slider',[SliderController::class, 'getCategorySlider']);
Route::get('category-cards',[SingleCategoryController::class, 'getAllCategories']);
Route::get('category-firstCard',[SingleCategoryController::class, 'getFirstCardProducts']);
Route::get('/singleCategory/getDetails',[SingleCategoryController::class, 'getDetails']);
Route::get('/singleCategory/get-sliderChildren',[SingleCategoryController::class, 'getSliderChildren']);
Route::get('/singleCategory/getFilters',[SingleCategoryController::class, 'getFilters']);
Route::get('/singleCategory/getColors',[SingleCategoryController::class, 'getColors']);
Route::get('/singleCategory/getSizes',[SingleCategoryController::class, 'getSizes']);
Route::get('/singleCategory/getBrands',[SingleCategoryController::class, 'getBrands']);
Route::get('/singleCategory/women-watches',[SingleCategoryController::class, 'womenWatches']);
Route::get('/singleCategory/get-makeup',[SingleCategoryController::class, 'getMakeup']);
Route::get('/singleCategory/men-underwear',[SingleCategoryController::class, 'getUnderwear']);
Route::get('/singleCategory/get-childCategories',[SingleCategoryController::class, 'getChildCategories']);
// landing
Route::get('/landing/get-allbrands', [landingController::class, 'getAllBrands']);
Route::get('/landing/search', [landingController::class, 'searchItems']);
Route::get('all-brand-categories', [FrontBrandCategoryController::class, 'allBrandCategories']);
Route::get('navbar-brands',[NavbarBrandController::class, 'navbarBrands']);
Route::get('all-parent-categories', [ParentCategoryController::class, 'allParentCategories']);
Route::get('/landing/slider',[SliderController::class, 'getSlider']);
Route::get('/landing/parentCategories', [ParentCategoryController::class, 'parentCategories']);
Route::get('/landing/iranian-designers', [landingController::class, 'iranianDesigners']);
Route::get('/landing/weekstyle-products', [landingController::class, 'weekstyleProducts']);
Route::get('/landing/fbrands', [landingController::class, 'fbrands']);
Route::get('/shegeft', [ShegeftController::class, 'discounted']);
Route::get('/mostVisited',[MostVisitedController::class, 'mostVisited']);
Route::get('/mostSells',[MostVisitedController::class, 'mostSells']);
Route::get('/newest',[MostVisitedController::class, 'newest']);

// sub category
Route::get('/parent/getDetails',[ParentCategoryController::class, 'getDetails']);
Route::get('/parent/get-sliderChildren',[ParentCategoryController::class, 'getSliderChildren']);
Route::get('/parent/getFilters',[ParentCategoryController::class, 'getFilters']);
Route::get('/parent/get-childCategories',[ParentCategoryController::class, 'getCategories']);
Route::get('/parent/getColors',[ParentCategoryController::class, 'getColors']);
Route::get('/parent/getSizes',[ParentCategoryController::class, 'getSizes']);
Route::get('/parent/getBrands',[ParentCategoryController::class, 'getBrands']);
Route::get('/parent/women-watches',[ParentCategoryController::class, 'womenWatches']);
Route::get('/parent/get-makeup',[ParentCategoryController::class, 'getMakeup']);
Route::get('/parent/men-underwear',[ParentCategoryController::class, 'getUnderwear']);

// child category
Route::get('getChildCategory',[ChildCategoryController::class, 'getChildCategory']);
Route::get('getFilters',[ChildCategoryController::class, 'getFilters']);
Route::get('getProducts',[ChildCategoryController::class, 'getProducts']);
Route::get('child-category-types', [ChildCategoryController::class, 'getTypes']);
Route::get('child-category-fasten', [ChildCategoryController::class, 'getFastenType']);
Route::get('child-category-designs', [ChildCategoryController::class, 'getDesigns']);
Route::get('child-category-clothType', [ChildCategoryController::class, 'getClothType']);
Route::get('child-category-heights', [ChildCategoryController::class, 'getHeight']);
Route::get('child-category-startCountry', [ChildCategoryController::class, 'getStartCountry']);
Route::get('child-category-materials', [ChildCategoryController::class, 'getMaterial']);
Route::get('child-category-seasons', [ChildCategoryController::class, 'getSeasons']);
Route::get('child-category-special', [ChildCategoryController::class, 'getSpecial']);
Route::get('child-category-brands',[ChildCategoryController::class, 'allBrands']);
Route::get('child-category-sizes',[ChildCategoryController::class, 'allSizes']);
Route::get('child-category-colors',[ChildCategoryController::class, 'allColors']);
Route::get('child-category-prices',[ChildCategoryController::class, 'allPrices']);
Route::get('child-category-specialAttributes',[ChildCategoryController::class, 'specialAttributes']);

// single product
Route::get('getSingleProduct',[SingleProductController::class, 'getSingleProduct']);
Route::get('singleProductCategory',[SingleProductController::class, 'singleProductCategory']);
Route::get('getOtherProductDesigns',[SingleProductController::class, 'getOtherProductDesigns']);

// payment
Route::post('/payment/verify', [PaymentController::class, 'verify']);
Route::post('/payment/send', [PaymentController::class, 'send']);

// auth
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/singleProduct/updateLike',[SingleProductController::class, 'updateLike']);
    Route::post('/singleProduct/unLike',[SingleProductController::class, 'unLike']);
    Route::post('/me', [ControllersAuthController::class, 'me']);
    Route::post('/auth/logout', [ControllersAuthController::class, 'logout']);
    Route::prefix('profile')->group(function() {
        Route::post('/userInfo', [ProfileController::class, 'userInfo']);
        Route::get('/addresses', [ProfileController::class, 'getAddresses']);
        Route::post('/address/create', [ProfileController::class, 'createAddress']);
        Route::post('/address/edit', [ProfileController::class, 'editAddress']);
        Route::post('/address/delete', [ProfileController::class, 'deleteAddress']);
    });
});
Route::post('/auth/login', [ControllersAuthController::class, 'login']);
Route::post('/auth/check', [ControllersAuthController::class, 'checkOtp']);
Route::get('getProvinces', [ProvinceController::class, 'getProvinces']);
Route::get('getCities', [ProvinceController::class, 'getCities']);

// collection
Route::get('/collection/getProducts', [FrontCollectionController::class, 'getProducts']);
Route::get('/collection/getFilters', [FrontCollectionController::class, 'getFilters']);
Route::get('/collection/get-colors', [FrontCollectionController::class, 'allColors']);
Route::get('/collection/get-sizes', [FrontCollectionController::class, 'allSizes']);
Route::get('/collection/get-weights', [FrontCollectionController::class, 'allWeights']);
// discounted
Route::get('/discounted/get-collection', [DiscountController::class, 'getCollection']);
Route::get('/discounted/get-categories', [DiscountController::class, 'getCategories']);
// watches
Route::get('/watch/brand-sliders', [landingController::class, 'getBrandSliders']);
Route::get('/watches/get-brands', [landingController::class, 'getBrands']);
Route::get('/watch/get-watches', [landingController::class, 'getWatches']);
Route::get('/watch/get-discounted-watches', [landingController::class, 'discountedWatches']);
Route::get('/watch/weekstyle-watches', [landingController::class, 'getWeekstyle']);
Route::get('/watch/get-shegeft', [landingController::class, 'getShegeft']);
// brands
Route::get('/brand/all-brands', [landingController::class, 'allBrands']);
Route::get('/brand/single-brand/getDetails', [landingController::class, 'getDetails']);
Route::get('/brand/single-brand/brandCategories', [landingController::class, 'brandCategories']);
Route::get('/brand/single-brand/getFilters', [landingController::class, 'getFilters']);
Route::get('/brand/single-brand/getColors', [landingController::class, 'getColors']);
Route::get('/brand/single-brand/getSizes', [landingController::class, 'getSizes']);
Route::get('/brand/single-brand/get-childCategories', [landingController::class, 'getChildCategories']);
// paidar fashion
Route::get('/paidar/products', [landingController::class, 'getPaidarProducts']);
// promotion
Route::get('/promotion/getProducts', [landingController::class, 'getProducts']);
// seller
Route::get('/seller/getProducts', [SellerController::class, 'getProducts']);
Route::get('/seller/get-sizes', [SellerController::class, 'getSizes']);
Route::get('/seller/get-brands', [SellerController::class, 'getBrands']);
Route::get('/seller/get-colors', [SellerController::class, 'getColors']);
Route::get('/seller/get-categories', [SellerController::class, 'getCategories']);
Route::get('/seller/get-seller', [SellerController::class, 'getSeller']);