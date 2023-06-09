<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Image;
use DataTables;
use File;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Brand;
use App\Models\pickup\Pickup;
use App\Models\setting\Warehouse;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
        //index method
        public function index()
        {
            $product=Product::all();
            return view('admin.product.index', compact('product'));
        }
    public function create()
    {
        $category=Category::all();
        $subcategory=Subcategory::all();
        $childcategory=Childcategory::all();
        $brand=Brand::all();
        $pickup_point=Pickup::all();
        $warehouse=Warehouse::all();
        return view('admin.product.create', compact('category','subcategory','childcategory','brand','pickup_point','warehouse'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required|unique:products|max:55',
            'subcategory_id' => 'required',
            'brand_id' => 'required',
            'unit' => 'required',
            'selling_price' => 'required',
            'color' => 'required',
            'description' => 'required',
        ]);
    //subcategory call for category id


       $slug=Str::slug($request->name, '-');


       $data=array();
       $data['name']=$request->name;
       $data['slug']=Str::slug($request->name, '-');
       $data['code']=$request->code;
       $data['category_id']=$request->category_id;
       $data['subcategory_id']=$request->subcategory_id;
       $data['childcategory_id']=$request->childcategory_id;
       $data['brand_id']=$request->brand_id;
       $data['pickup_point_id']=$request->pickup_point_id;
       $data['unit']=$request->unit;
       $data['tags']=$request->tags;
       $data['purchase_price']=$request->purchase_price;
       $data['selling_price']=$request->selling_price;
       $data['discount_price']=$request->discount_price;
       $data['warehouse']=$request->warehouse;
       $data['stock_quantity']=$request->stock_quantity;
       $data['color']=$request->color;
       $data['size']=$request->size;
       $data['description']=$request->description;
       $data['video']=$request->video;
       $data['featured']=$request->featured;
       $data['today_deal']=$request->today_deal;
       $data['product_slider']=$request->product_slider;
       $data['status']=$request->status;
       $data['trendy']=$request->trendy;
       $data['admin_id']=Auth::id();
       $data['date']=date('d-m-Y');
       $data['month']=date('F');

       //single thumbnail
       if ($request->thumbnail) {
             $thumbnail=$request->thumbnail;
             $photoname=$slug.'.'.$thumbnail->getClientOriginalExtension();
             Image::make($thumbnail)->resize(600,600)->save('files/product/'.$photoname);
             $data['thumbnail']=$photoname;  
       }

       //multiple images
       $images = array();
       if($request->hasFile('images')){
           foreach ($request->file('images') as $key => $image) {
               $imageName= hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
               Image::make($image)->resize(600,600)->save('files/product/'.$imageName);
               array_push($images, $imageName);
           }
           $data['images'] = json_encode($images);
       }
       DB::table('products')->insert($data);
       $notification=array('messege' => 'Product Inserted!', 'alert-type' => 'success');
       return redirect()->back()->with($notification);

    }
    public function delete($id)
    {

        $product=DB::table('products')->where('id',$id)->first();  //product data get
        if (File::exists('files/product/'.$product->thumbnail)) {
              FIle::delete('files/product/'.$product->thumbnail);
        }

        $images=json_decode($product->images,true);
        if (isset($images)) {
             foreach($images as $key => $image){
                if (File::exists('files/product/'.$image)) {
                    FIle::delete('files/product/'.$image);
                }
             }
        }

        DB::table('products')->where('id',$id)->delete();
       $notification=array('messege' => 'Product Deleted!', 'alert-type' => 'success');
       return redirect()->back()->with($notification);
    }

    public function edit($id)
    {
        $product=DB::table('products')->where('id',$id)->first();
        //$product=Product::findorfail($id);
        $category=Category::all();
        $brand=Brand::all();
        $warehouse=DB::table('warehouses')->get();
        $pickup_point=DB::table('pickups')->get();
         //childcategory get_
        $childcategory=DB::table('childcategories')->where('category_id',$product->category_id)->get();
        // dd($childcategory);
        return view('admin.product.edit',compact('product','category','brand','warehouse','pickup_point','childcategory'));

    }
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required|max:55',
            'subcategory_id' => 'required',
            'brand_id' => 'required',
            'unit' => 'required',
            'selling_price' => 'required',
            'color' => 'required',
            'description' => 'required',
        ]);
 
        //subcategory call for category id
        $subcategory=DB::table('subcategories')->where('id',$request->subcategory_id)->first();
        $slug=Str::slug($request->name, '-');
 
 
        $data=array();
        $data['name']=$request->name;
        $data['slug']=Str::slug($request->name, '-');
        $data['code']=$request->code;
        $data['category_id']=$subcategory->category_id;
        $data['subcategory_id']=$request->subcategory_id;
        $data['childcategory_id']=$request->childcategory_id;
        $data['brand_id']=$request->brand_id;
        $data['pickup_point_id']=$request->pickup_point_id;
        $data['unit']=$request->unit;
        $data['tags']=$request->tags;
        $data['purchase_price']=$request->purchase_price;
        $data['selling_price']=$request->selling_price;
        $data['discount_price']=$request->discount_price;
        $data['warehouse']=$request->warehouse;
        $data['stock_quantity']=$request->stock_quantity;
        $data['color']=$request->color;
        $data['size']=$request->size;
        $data['description']=$request->description;
        $data['video']=$request->video;
        $data['featured']=$request->featured;
        $data['today_deal']=$request->today_deal;
        $data['product_slider']=$request->product_slider;
        $data['status']=$request->status;
        $data['trendy']=$request->trendy;
 
        //__old thumbnail ase kina__ jodi thake new thumbnail insert korte hobe
        $thumbnail = $request->file('thumbnail');
         if($thumbnail) {
            
              $thumbnail=$request->thumbnail;
              $photoname=$slug.'.'.$thumbnail->getClientOriginalExtension();
              Image::make($thumbnail)->resize(600,600)->save('files/product/'.$photoname);
              $data['thumbnail']=$photoname;   // public/files/product/plus-point.jpg   
         }
 
        //__multiple image update__//
 
         $old_images = $request->has('old_images');
         if($old_images){
             $images = $request->old_images;
             $data['images'] = json_encode($images);
         }else{
             $images = array();
             $data['images'] = json_encode($images); 
         }
 
         if($request->hasFile('images')){
             foreach ($request->file('images') as $key => $image) {
                 $imageName= hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
                 Image::make($image)->resize(600,600)->save('files/product/'.$imageName);
                 array_push($images, $imageName);
             }
             $data['images'] = json_encode($images);
         }
 
 
 
        DB::table('products')->where('id',$request->id)->update($data);
        $notification=array('messege' => 'Product Updated!', 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

}
