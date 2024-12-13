<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\ServiceAttributeValueItem;
use App\Invite;
use App\ServiceAttributeValue;
use App\AttributeValue;
use App\ServiceGallery;
use App\Attribute;
use App\Service;
use App\Packages;
use App\Category;
use App\Addon;
use App\ChildCategory;
use App\Setting;
use App\HomeSetting;
use App\Article;
use App\FaqModel;
use App\Search;
use App\RewardUser;

class ServiceController extends BaseController
{
    public function service_packages(Request $request)
    {
        $packages = Packages::where('service_id',$request->service_id)
                    ->orderBy('id', 'DESC')
                    ->get();
        return $this->sendResponse($packages, 'All Packages');
    }

    public function all_service()
    {
        $service = Service::where('status',1)->orderBy('id', 'DESC')->get();
        $datas = [];
        foreach ($service as $key => $value) {
            $data['id']     = (string) $value->id;
            $data['title']  = $value->name;
            $data['image']  = \URL::to('/').'/uploads/service/'.$value->thumbnail_img;
            $data['price']  = (string) $value->price;
            array_push($datas, $data);
        }
        return $this->sendResponse($datas, 'All Services');
    }

    public function get_service_by_category($category_id='')
    {
        $data = ServiceAttributeValueItem::where('sub_category_id', $category_id)
                ->distinct()
                ->get('service_id');
                
        $services = Service::where('status', '1')
                    ->whereIn('id', $data)
                    ->select(
                        '*'
                    )
                    ->get();
                
        return $this->sendResponse($services, 'Get Services');
    }

    public function attributes(Request $request, $service_id='')
    {
        $data['service_atr'] = ServiceAttributeValueItem::where('service_id',$service_id)->paginate(10);
        $data['request'] = $request;
        $data['service_id'] = $service_id;
        return $this->sendResponse($data, 'Get Services');
    }

    public function all_service_name()
    {
        $service = Service::where('status',1)->orderBy('id', 'DESC')->get();
        $datas = [];
        foreach ($service as $key => $value) {
            $data['id']     = $value->id;
            $data['title']  = $value->name;
            array_push($datas, $data);
        }
        return $this->sendResponse($datas, 'All Services Name');
    }

    public function service_details_new($id)
    {

        //Log::info('This is an informational message');

        $value = Service::find($id);
        if($id && $value){
           
            $currencies = \DB::table('currencies')->where('default', '1')->first();

            $service_categorys = [];
            if(ServiceAttributeValueItem::where('service_id',$value->id)->with('sub_category')->first()->sub_category){

                $data['sub_cate_yes'] = 'Yes';
                
            } else {
                $data['sub_cate_yes'] = 'No';
                foreach (ServiceAttributeValueItem::where('service_id',$value->id)->get() as $key => $val) {
                    $cate = [];
                    if($val->sub_category_id){
                        $cate['sub_category_id'] = $val->sub_category_id;
                        $cate['sub_category_name'] = $val->sub_category?$val->sub_category->name:'';
                    } 
                    else {
                        $cate['category_id']    = $val->category_id;
                        $cate['category_name']  = $val->category->name;
                    }

                    $service_category_atr = [];
                    
                    $service_cat_atr = ServiceAttributeValue::where('service_id',$value->id)->where('ser_attr_val_item_id',$val->id)->get();
                
                    foreach ($service_cat_atr as $key => $atr_item) {

                        $cat_atr_item['attribute_id']       = $atr_item->attribute_id;
                        $cat_atr_item['attribute_name']     = $atr_item->attribute?$atr_item->attribute->name:'';
                        $cat_atr_item['attribute_item_id']  = $atr_item->attribute_item_id;
                        $cat_atr_item['attribute_item']     = AttributeValue::where('id',$atr_item->attribute_item_id)->value('value');
                        $cat_atr_item['attribute_price']    = $atr_item->attribute_price;

                        $addons = Addon::where('service_id',$id)->where('ser_attr_val_item_id',$val->id)->where('attribute_item_id',$atr_item->attribute_item_id)->first();
                        if($addons){
                            $addon['name'] = $addons?$addons->name:'';
                            $addon['price'] = $addons?$addons->value:'';
                            $addon['percentage'] = $addons?$addons->percentage:'';
                            
                            $cat_atr_item['addon'] = $addon;
                        }
                       
                        array_push($service_category_atr, $cat_atr_item);
                    }

                    $cate['category_attribute'] = $service_category_atr;
                    
                    if(empty($val->sub_category_id)){
                        array_push($service_categorys, $cate);
                    }

                }
            }


            $data['id']                 = $value->id;
            $data['user_name']          = $value->user?$value->user->name:'';
            $data['user_id']            = $value->user_id;
            $data['category_id']        = $value->parent_id;
            $data['category_name']      = Category::where('id',$value->parent_id)->value('name');
            $data['title']              = $value->name;
            $data['image']              = \URL::to('/').'/uploads/service/'.$value->thumbnail_img;
            $data['price_currency']     = $currencies?$currencies->symbol:'';
            $data['price']              = $value->price;
            $data['material_status']    = $value->material_status;
            $data['material_price']     = $value->material_price;
      
            $data['status']             = $value->status=='1'?'Active':'Inactive';
            $data['featured']           = $value->featured=='1'?'Yes':'No';
            
            $data['short_description']  = $value->short_description;
            $data['description']        = $value->description;
 
            $data['service_category']   = $service_categorys;
                
          //  Log::info('This is an informational message 2');
            return $this->sendResponse($data, 'Service Details');
        } else {
            return $this->sendError('Invalid service id!');
            //Log::info('This is an informational message 3');
        }
        
    }

    public function service_details($id)
    {
        $service = Service::find($id);
    
        if (!$service) {
            return $this->sendError('Invalid service id!');
        }
    
        // Fetch all service attribute items with related categories, subcategories, and child categories
        $serviceAttributes = ServiceAttributeValueItem::where('service_id', $id)
        ->with(['category', 'sub_category', 'child_category', 'serviceAttributeValues.attribute', 'serviceAttributeValues.attributeItem'])
        ->get();

        // Group subcategories and child categories properly under their respective categories
        $serviceAttributes = ServiceAttributeValueItem::where('service_id', $id)
        ->with(['category', 'sub_category', 'child_category', 'serviceAttributeValues.attribute', 'serviceAttributeValues.attributeItem'])
        ->get();
    
        $categories = $serviceAttributes->groupBy('category.id')->map(function ($items, $categoryId) use ($id) {
            $category = $items->first()->category;
        
            // Group sub-categories
            $subCategories = $items->groupBy('sub_category.id')->map(function ($subItems, $subCategoryId) use ($id) {
                $subCategory = $subItems->first()->sub_category;
        
                if (!$subCategory) {
                    return null; // Skip entry if sub_category is null
                }
        
                // Group child categories
                $childCategories = $subItems->groupBy('child_category.id')->map(function ($childItems) {
                    $childCategory = $childItems->first()->child_category;
        
                    if ($childCategory) {
                        return [
                            'id' => (string) $childCategory->id,
                            'name' => $childCategory->name,
                            'price' => $childCategory->price ?? "0",
                            'icon' => \URL::to('/') . '/uploads/category/' . $childCategory->icon ?? "",
                            'attributes' => $childItems->flatMap(function ($item) {
                                return $item->serviceAttributeValues->map(function ($attr) {
                                    return [
                                        'attribute_id' => (string) $attr->attribute_id,
                                        'attribute_name' => $attr->attribute ? $attr->attribute->name : '',
                                        'attribute_item_id' => (string) $attr->attribute_item_id,
                                        'attribute_item' => $attr->attributeItem ? $attr->attributeItem->value : '',
                                        'attribute_price' => (string) $attr->attribute_price,
                                    ];
                                });
                            }),
                        ];
                    }
        
                    // If no child category, return attributes instead
                    return $childItems->flatMap(function ($item) {
                        return $item->serviceAttributeValues->map(function ($attr) {
                            return [
                                'attribute_id' => (string) $attr->attribute_id,
                                'attribute_name' => $attr->attribute ? $attr->attribute->name : '',
                                'attribute_item_id' => (string) $attr->attribute_item_id,
                                'attribute_item' => $attr->attributeItem ? $attr->attributeItem->value : '',
                                'attribute_price' => (string) $attr->attribute_price,
                            ];
                        });
                    });
                })->filter()->values();
        
                // Check if child categories are empty, return attributes if so
                if ($childCategories->isEmpty()) {
                    $attributes = $subItems->flatMap(function ($item) {
                        return $item->serviceAttributeValues->map(function ($attr) {
                            return [
                                'attribute_id' => (string) $attr->attribute_id,
                                'attribute_name' => $attr->attribute ? $attr->attribute->name : '',
                                'attribute_item_id' => (string) $attr->attribute_item_id,
                                'attribute_item' => $attr->attributeItem ? $attr->attributeItem->value : '',
                                'attribute_price' => (string) $attr->attribute_price,
                            ];
                        });
                    });
        
                    return [
                        'id' => (string) $subCategory->id,
                        'name' => $subCategory->name,
                        'price' => $subCategory->price,
                        'icon' => \URL::to('/') . '/uploads/category/' . $subCategory->icon,
                        'meta_title' => $subCategory->meta_title ?? "",
                        'meta_description' => $subCategory->meta_description ?? "",
                        'attributes' => $attributes,
                    ];
                }
        
                // Return sub-category with child categories if they exist
                return [
                    'id' => (string) $subCategory->id,
                    'name' => $subCategory->name,
                    'price' => $subCategory->price,
                    'icon' => \URL::to('/') . '/uploads/category/' . $subCategory->icon,
                    'meta_title' => $subCategory->meta_title ?? "",
                    'meta_description' => $subCategory->meta_description ?? "",
                    'child_categories' => $childCategories,
                ];
            })->filter()->values();
        
            // Determine whether to return attributes or sub-categories
            $attributes = $items->flatMap(function ($item) {
                return $item->serviceAttributeValues->map(function ($attr) {
                    return [
                        'attribute_id' => (string) $attr->attribute_id,
                        'attribute_name' => $attr->attribute ? $attr->attribute->name : '',
                        'attribute_item_id' => (string) $attr->attribute_item_id,
                        'attribute_item' => $attr->attributeItem ? $attr->attributeItem->value : '',
                        'attribute_price' => (string) $attr->attribute_price,
                    ];
                });
            });
        
            return [
                'id' => (string) $category->id,
                'name' => $category->name,
                'icon' => \URL::to('/') . '/uploads/category/' . $category->icon,
                $subCategories->isNotEmpty() ? 'sub_categories' : 'attributes' => $subCategories->isNotEmpty() ? $subCategories : $attributes,
            ];
        })->values();
        
        // Service gallery
        $service_gallery = ServiceGallery::where('service_id', $service->id)
            ->get()
            ->map(function ($gallery) {
                return ['photo' => \URL::to('/') . '/uploads/service/gallery/' . $gallery->photos];
            });
    
        // Fetch default currency
        $currency = \DB::table('currencies')->where('default', '1')->first();
    
        // Prepare response data
        $data = [
            'id' => (string) $service->id,
            'user_name' => $service->user ? $service->user->name : '',
            'video' => $service->video ?? '',
            'info' => $service->info ?? '',
            'video_title' => $service->video_title ?? '',
            'video_description' => $service->video_description ?? '',
            'user_id' => (string) $service->user_id,
            'category_id' => (string) $service->parent_id,
            'category_name' => Category::where('id', $service->parent_id)->value('name'),
            'title' => $service->name,
            'image' => \URL::to('/') . '/uploads/service/' . $service->thumbnail_img,
            'price_currency' => $currency ? $currency->symbol : '',
            'price' => (string) $service->price,
            'material_status' => $service->material_status,
            'material_price' => (string) $service->material_price,
            'recommended' => $service->recommended,
            'status' => $service->status == '1' ? 'Active' : 'Inactive',
            'featured' => $service->featured == '1' ? 'Yes' : 'No',
            'featured_banner' => \URL::to('/') . '/uploads/service/featured_banner/' . $service->featured_banner,
            'short_description' => $service->short_description,
            'description' => $service->description,
            'meta_title' => $service->meta_title,
            'meta_description' => $service->meta_description,
            'service_gallery' => $service_gallery,
            'categories' => $categories,
            'packages' => Packages::where('service_id', $service->id)->orderBy('id', 'DESC')->get(),
        ];
    
        return $this->sendResponse($data, 'Service Details');
    }    
    
    public function featured_service()
    {
        $service = Service::where('status',1)->where('featured',1)->orderBy('id', 'DESC')->get();
        $datas = [];
        foreach ($service as $key => $value) {
            $data['id']                 = (string) $value->id;
            $data['title']              = $value->name;
            $data['featured_banner']    = \URL::to('/').'/uploads/service/featured_banner/'.$value->featured_banner;
            array_push($datas, $data);
        }
        return $this->sendResponse($datas, 'Featured Services');
    }

    public function all_category_old($service_id='')
    {
        // if($category_id){
        //     $category = Category::where('status','1')->where('parent_id',$category_id)->orderBy('id', 'DESC')->get();
        // } else {
        //     $category = Category::where('status','1')->where('parent_id','0')->orderBy('id', 'DESC')->get();
        // }
        
        // $datas = [];
        // foreach ($category as $key => $value) {
        //     $data['id']                 = $value->id;
        //     $data['name']               = $value->name;
        //     $data['icon']               = \URL::to('/').'/uploads/category/'.$value->icon;
        //     $data['price']              = $value->price;
        //     $data['meta_title']         = $value->meta_title;
        //     $data['meta_description']   = $value->meta_description;
        //     array_push($datas, $data);
        // }
        // if($category_id){
        //     return $this->sendResponse($datas, 'Sub Categories');
        // } else {
        //     return $this->sendResponse($datas, 'All Categories');
        // }

        $data = ServiceAttributeValueItem::where('service_id',$service_id)->groupBy('sub_category_id')->get();
        $sub_Cate_ids = [];
        foreach ($data as $key => $value) {
            array_push($sub_Cate_ids, $value->sub_category_id);
        }

        $category = Category::where('status','1')->whereIn('id',$sub_Cate_ids)->get();
       
        $cat_info = [];
        foreach ($category as $key => $cat) {
            $info['id']                 = $cat->id;
            $info['name']               = $cat->name;
            $info['icon']               = \URL::to('/').'/uploads/category/'.$cat->icon;
            $info['price']              = $cat->price;
            $info['meta_title']         = $cat->meta_title;
            $info['meta_description']   = $cat->meta_description;
            array_push($cat_info, $info);
        }
       
        return $this->sendResponse($cat_info, 'Sub Categories');
    }

    public function all_category($service_id='')
    {
        $category = Category::where('status', '1')
                    ->select(
                        'id',
                        'name',
                        \DB::raw("CONCAT('" . url('/') . "', '/uploads/category/', icon) as icon"),
                        'price',
                        'meta_title',
                        'meta_description'
                    )
                    ->get();
                
        return $this->sendResponse($category, 'Categories');
    }

    public function sub_category($service_id='')
    {
        // $data = ServiceAttributeValueItem::where('service_id',$service_id)->groupBy('sub_category_id')->get('sub_category_id');
        $data = ServiceAttributeValueItem::where('service_id', $service_id)
                ->distinct()
                ->get('sub_category_id');

        //$category = Category::where('status','1')->whereIn('id',$data)->get();
        $category = Category::where('status', '1')
                    ->whereIn('id', $data)
                    ->select(
                        'id',
                        'name',
                        \DB::raw("CONCAT('" . url('/') . "', '/uploads/category/', icon) as icon"),
                        'price',
                        'meta_title',
                        'meta_description'
                    )
                    ->get();
                
        return $this->sendResponse($category, 'Sub Categories');
    }

    public function child_category($service_id='',$sub_cat='')
    {
        // $category = ChildCategory::where('status','1')->where('sub_category_id',$category_id)->orderBy('id', 'DESC')->get();
        
        // $datas = [];
        // foreach ($category as $key => $value) {
            
        //     $data['id']                 = $value->id;
        //     $data['name']               = $value->name;
        //     $data['icon']               = \URL::to('/').'/uploads/child-category/'.$value->icon;
        //     $data['price']              = $value->price;
        //     array_push($datas, $data);
        // }
        // return $this->sendResponse($datas, 'Child Categories');

        $data = ServiceAttributeValueItem::where('service_id',$service_id)->where('sub_category_id', $sub_cat)->get();

        $child_Cate_ids = [];
        foreach ($data as $key => $value) {
            array_push($child_Cate_ids, $value->child_category_id);
        }

        $child = ChildCategory::where('status','1')->whereIn('id',$child_Cate_ids)->get();
    
        $chid_info = [];
        foreach ($child as $key => $cat) {
            $info['id']                 = $cat->id;
            $info['name']               = $cat->name;
            $info['icon']               = \URL::to('/').'/uploads/child-category/'.$cat->icon;
            $info['price']              = $cat->price;
            array_push($chid_info, $info);
        }
       
        return $this->sendResponse($chid_info, 'Child Categories');
    }

    public function cate_attr(Request $request)
    {
        $where_con ='';
        if($request->data_from=='category'){
            $data = ServiceAttributeValueItem::where('service_id',$request->service_id)->where('category_id', $request->category_id)->groupBy('sub_category_id')->get();
        } elseif ($request->data_from=='sub category') {
            $data = ServiceAttributeValueItem::where('service_id',$request->service_id)->where('sub_category_id', $request->category_id)->get();
        } else {
            $data = ServiceAttributeValueItem::where('service_id',$request->service_id)->where('child_category_id', $request->category_id)->get();
        }
        $ids = [];
        foreach ($data as $key => $value) {
            array_push($ids, $value->id);
        }
        $service_cat_atr = ServiceAttributeValue::whereIn('ser_attr_val_item_id',$ids)->get();
  
        $service_category_atr = [];
        foreach ($service_cat_atr as $key => $atr_item) {

            $cat_atr_item['id']                 = $atr_item->id;
            $cat_atr_item['ser_attr_val_item_id']       = $atr_item->ser_attr_val_item_id;
            $cat_atr_item['attribute_id']       = $atr_item->attribute_id;
            $cat_atr_item['attribute_name']     = $atr_item->attribute?$atr_item->attribute->name:'';
            $cat_atr_item['attribute_item_id']  = $atr_item->attribute_item_id;
            $cat_atr_item['attribute_item']     = AttributeValue::where('id',$atr_item->attribute_item_id)->value('value');
            $cat_atr_item['attribute_price']    = $atr_item->attribute_price;
           
            array_push($service_category_atr, $cat_atr_item);
        }
        return $this->sendResponse($service_category_atr, 'Category Attribute');
    }


    public function get_addon(Request $request)
    {
        if($request->service_id && $request->attribute_item_id){
            $addons = Addon::where('service_id',$request->service_id)->whereIn('attribute_item_id',$request->attribute_item_id)->get();
            $resp = [];
            foreach ($addons as $key => $addon) {
                $res['id'] = $addon->id;
                $res['service_id'] = $addon->service_id;
                $res['attribute_item_id'] = $addon->attribute_item_id;
                $res['name'] = $addon->name;
                $res['value'] = $addon->value;
                $res['percentage'] = $addon->percentage;
                $res['icon'] = $addon->icon?\URL::to('/').'/uploads/addon/'.$addon->icon:'';
                $res['short_description'] = $addon->short_description;
                array_push($resp, $res);
            }
            return $this->sendResponse($resp, 'Addons');
        } else {
            return $this->sendError('Required field is empty');
        }
    }

    public function get_page(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];

        $page_data = Article::where(['id' => $request->id])->get(['id','title_en','desc_en']);
        if ($page_data->count() > 0) {
            $status = "1";
            $message = trans('validation.data_fetched_successfully');
            $o_data = $page_data->first();
        }
        // $o_data                     = convert_all_elements_to_string($o_data);
        return response()->json([
            'status' => $status,
            'message' => $message,
            'errors' => (object)[],
            'oData' => (object)$o_data,
        ], 200);
    }
    public function get_faq(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];

        $page_data = FaqModel::where('active',1)->orderBy('id', 'asc')->get(['id','title','description']);
        if ($page_data->count() > 0) {
            $status = "1";
            $message = trans('validation.data_fetched_successfully');
            $o_data['list'] = $page_data;
        }
        // $o_data                     = convert_all_elements_to_string($o_data);
        return response()->json([
            'status' => $status,
            'message' => $message,
            'errors' => (object)[],
            'oData' => (object)$o_data,
        ], 200);
    }

    public function privacy_policy()
    {
        // sendNotification(['34654363464'], array(
        //     "title" => 'Registration Successfully!', 
        //     "body" => 'Successfully Register.',
        //     "type" => "customer",
        //     "id"=> 1,
        // ));
        $setting = Setting::where('title','privacy-policy')->where('setting_from','Customer')->first();
        return $this->sendResponse($setting, 'Privacy Policy');
    }

    public function terms_condition()
    {
        $setting = Setting::where('title','terms-of-use')->where('setting_from','Customer')->first();
        return $this->sendResponse($setting, 'Terms Condition');
    }

    public function contact_us()
    {
        $setting = Setting::where('title','contact-us')->where('setting_from','Customer')->first();
        return $this->sendResponse($setting, 'Contact Us');
    }

    public function vendor_privacy_policy()
    {
        $setting = Setting::where('title','privacy-policy')->where('setting_from','Vendor')->first();
        return $this->sendResponse($setting, 'Privacy Policy');
    }

    public function vendor_terms_condition()
    {
        $setting = Setting::where('title','terms-of-use')->where('setting_from','Vendor')->first();
        return $this->sendResponse($setting, 'Terms Condition');
    }

    public function search_service(Request $request)
    {
        $limit = $request->input('limit', 10); 
        $page = $request->input('page', 1); 
        $offset = ($page - 1) * $limit; 
    
        $searchTerm = $request->input('search', '');
    
        $attr_items = AttributeValue::where('value', 'LIKE', "%$searchTerm%")->get();
        $attributeItemIds = $attr_items->pluck('id')->toArray();
        $attributeIds = Attribute::where('name', 'LIKE', "%$searchTerm%")->pluck('id')->toArray();
    
        $serviceIds = [];
        if (!empty($attributeItemIds) || !empty($attributeIds)) {
            $serv_attr = ServiceAttributeValue::whereIn('attribute_id', $attributeIds)
                ->orWhereIn('attribute_item_id', $attributeItemIds)
                ->get();
    
            $serviceIds = $serv_attr->pluck('service_id')->toArray();
        }
    
        $services = !empty($serviceIds) 
            ? Service::where('status', '1')->whereIn('id', $serviceIds)
                ->orderBy('position')
                ->skip($offset)
                ->take($limit)
                ->get()
            : Service::where('status', '1')
                ->where('name', 'LIKE', "%$searchTerm%")
                ->orderBy('position')
                ->skip($offset)
                ->take($limit)
                ->get();
        
        $datas = [];
        foreach ($services as $service) {
            $this->storeSearchTerm($service->name); 
    
            $sub_cate_check = ServiceAttributeValueItem::where('service_id', $service->id)
                ->with('sub_category')
                ->first();
    
            $datas[] = [
                'id' => $service->id,
                'title' => $service->name,
                'image' => \URL::to('/') . '/uploads/service/' . $service->thumbnail_img,
                'sub_cate_yes' => $sub_cate_check && $sub_cate_check->sub_category ? 'Yes' : 'No',
            ];
        }
    
        // Prepare pagination response
        $total = !empty($serviceIds)
            ? Service::where('status', '1')->whereIn('id', $serviceIds)->count()
            : Service::where('status', '1')->where('name', 'LIKE', "%$searchTerm%")->count();
    
        return $this->sendResponse([
            'services' => $datas,
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'page' => $page,
                'total_pages' => ceil($total / $limit),
            ]
        ], 'Services');
    }
    
    /**
     * Store or update the service name as a search term.
     */
    protected function storeSearchTerm($term)
    {
        $search = Search::where('term', $term)->first();
    
        if ($search) {
            $search->increment('count'); // Increment the count if the term exists
        } else {
            Search::create(['term' => $term]); // Store the service name if it doesn't exist
        }
    }
    
    /**
     * API to get trending and recent searched services.
     */
    public function getTrendingAndRecentSearches()
    {
        $trending = Search::orderBy('count', 'desc')->take(3)->get();
        $recent = Search::orderBy('created_at', 'desc')->take(3)->get();
    
        return response()->json([
            'status' => '1',
            'data' => [
                'trending' => $trending,
                'recent' => $recent,
            ],
            'message' => 'Search trends fetched successfully'
        ]);
    }
    
    public function my_rewards()
    {
        $userId = auth()->user()->id;

        // Get the rewards for the authenticated user
        $rewards = RewardUser::where('user_id', $userId)
        ->where(function ($query) {
            $query->where('amounts', '>', 0)
                  ->orWhere('points', '>', 0);
        })
        ->orderBy('id', 'desc')
        ->get();

        // Calculate the total amount and points
        $totalAmount = $rewards->sum('amounts');
        $totalPoints = $rewards->sum('points');
        $invite = Invite::where('user_id', $userId)->first();
        return response()->json([
            'status' => '1',
            'data' => [
                'rewards' => $rewards,
                'totals' => [
                    'total_amount' => (string) $totalAmount,
                    'total_points' => (string) $totalPoints,
                    'last_date' => (string) $rewards[0]->date ?? "",
                    'invite_code' => (string) $invite->invite_code,
                ],
            ],
            'message' => 'Rewards fetched successfully'
        ]);
    }

    // Invite friends - Generate invite code
    public function generateInviteCode()
    {
        $user = Auth::user();

        // Check if the user already has an invite code
        $exist = Invite::where('user_id', $user->id)->first();

        if ($exist) {
            $invite = $exist; // If invite exists, use the existing one
        } else {
            $invite = Invite::create([
                'user_id' => $user->id,
                'invite_code' => strtoupper(uniqid('INV-'))
            ]);
        }

        return response()->json([
            'status' => '1',
            'data' => ['code'=>$invite->invite_code],
            'message' => 'Invite code generated successfully'
        ]);
    }

    // Get invite history and rewards
    public function inviteHistory()
    {
        $user = Auth::user();

        $invites = Invite::where('user_id', $user->id)->with('invited_user')->get();

        return response()->json([
            'status' => '1',
            'invites' => $invites,
            'message' => 'Invite history fetched successfully'
        ]);
    }
}
