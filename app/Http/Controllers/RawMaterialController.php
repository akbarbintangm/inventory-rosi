<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\bahanbaku;
use App\Models\Unit;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Str;


class RawMaterialController extends Controller
{
    public function index()
    {
        $bahan = bahanbaku::where("user_id", auth()->id())->count();

        return view('bahanbakus.index', [
            'bahanbakus' => $bahan,
        ]);
    }

    public function create(Request $request)
    {
        $categories = Category::where("user_id", auth()->id())->get(['id', 'name']);
        $units = Unit::where("user_id", auth()->id())->get(['id', 'name']);

        if ($request->has('category')) {
            $categories = Category::where("user_id", auth()->id())->whereSlug($request->get('category'))->get();
        }

        if ($request->has('unit')) {
            $units = Unit::where("user_id", auth()->id())->whereSlug($request->get('unit'))->get();
        }

        return view('bahanbakus.create', [
            'categories' => $categories,
            'units' => $units,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        /**
         * Handle upload image
         */
        $image = "";
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image')->store('bahanbakus', 'public');
        }

        Product::create([
            "code" => IdGenerator::generate([
                'table' => 'bahanbakus',
                'field' => 'code',
                'length' => 4,
                'prefix' => 'PC'
            ]),

            'product_image'     => $image,
            'name'              => $request->name,
            'category_id'       => $request->category_id,
            'unit_id'           => $request->unit_id,
            'quantity'          => $request->quantity,
            'buying_price'      => $request->buying_price,
            'selling_price'     => $request->selling_price,
            'quantity_alert'    => $request->quantity_alert,
            'tax'               => $request->tax,
            'tax_type'          => $request->tax_type,
            'notes'             => $request->notes,
            "user_id" => auth()->id(),
            "slug" => Str::slug($request->name, '-'),
            "uuid" => Str::uuid()
        ]);


        return to_route('bahanbakus.index')->with('success', 'Product has been created!');
    }

    public function show($uuid)
    {
        $bahan = Product::where("uuid", $uuid)->firstOrFail();
        // Generate a barcode
        $generator = new BarcodeGeneratorHTML();

        $barcode = $generator->getBarcode($bahan->code, $generator::TYPE_CODE_128);

        return view('bahanbakus.show', [
            'product' => $bahan,
            'barcode' => $barcode,
        ]);
    }

    public function edit($uuid)
    {
        $bahan = Product::where("uuid", $uuid)->firstOrFail();
        return view('bahanbakus.edit', [
            'categories' => Category::where("user_id", auth()->id())->get(),
            'units' => Unit::where("user_id", auth()->id())->get(),
            'product' => $bahan
        ]);
    }

    public function update(UpdateProductRequest $request, $uuid)
    {
        $bahan = Product::where("uuid", $uuid)->firstOrFail();
        $bahan->update($request->except('product_image'));

        $image = $bahan->product_image;
        if ($request->hasFile('product_image')) {

            // Delete Old Photo
            if ($bahan->product_image) {
                unlink(public_path('storage/') . $bahan->product_image);
            }
            $image = $request->file('product_image')->store('bahanbakus', 'public');
        }

        $bahan->name = $request->name;
        $bahan->slug = Str::slug($request->name, '-');
        $bahan->category_id = $request->category_id;
        $bahan->unit_id = $request->unit_id;
        $bahan->quantity = $request->quantity;
        $bahan->buying_price = $request->buying_price;
        $bahan->selling_price = $request->selling_price;
        $bahan->quantity_alert = $request->quantity_alert;
        $bahan->tax = $request->tax;
        $bahan->tax_type = $request->tax_type;
        $bahan->notes = $request->notes;
        $bahan->product_image = $image;
        $bahan->save();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product has been updated!');
    }

    public function destroy($uuid)
    {
        $product = Product::where("uuid", $uuid)->firstOrFail();
        /**
         * Delete photo if exists.
         */
        if ($product->product_image) {
            // check if image exists in our file system
            if (file_exists(public_path('storage/') . $product->product_image)) {
                unlink(public_path('storage/') . $product->product_image);
            }
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product has been deleted!');
    }
}
