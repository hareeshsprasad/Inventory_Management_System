<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // public function __construct(){
    // 	$this->middleware('auth');
    // }

    public function store(Request $request){
    	
    	$data=new Product;
        $data->product_code=$request->code;
    	$data->name= $request->name;
        $data->category = $request->category;
    	$data->stock = $request->stock;
    	$data->unit_price = $request->unit_price;
    	// $data->total_price = $request->stock * $request->unit_price;
        $data->sales_unit_price = $request->sale_price;
        // $data->sales_stock_price =$request->stock * $request->sale_price;


        $data->save();
        return Redirect()->route('add.product');
    }

    public function allProduct(){
    	$products = Product::all();
    	return view('Admin.all_product',compact('products'));
    }

    public function availableProducts(){
        $products = Product::where('stock','>','0')->get();
        return view('Admin.available_products',compact('products'));
    }

    public function formData($id){
        $product = Product::find($id);
        
        return view('Admin.add_order',compact('product'));
        // return view('Admin.add_order',['product'=>$product]);
    }

    public function purchaseData($id){
        $product = Product::find($id);
        
        return view('Admin.purchase_products',compact('product'));
    }

    public function storePurchase(Request $request){

        Product::where('name',$request->name)->update(['stock' => $request->stock + $request->purchase]);
        
        return Redirect()->route('all.product');
    }
    
    public function productDetails($id) {
        $product =  Product::find($id);
        return view('Admin.edit_product',compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'product_code' => $request->input('code'),
            'name' => $request->input('name'),
            'category' => $request->input('category'),
            'stock' => $request->input('stock'),
            'unit_price' => $request->input('unit_price'),
            'sales_unit_price' => $request->input('sale_price'),
        ]);
    
        return redirect()->route('all.product')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct($id){
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('all.product')->with('success', 'Product deleted successfully!');
    }

}
