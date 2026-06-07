<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\bahanbaku\storebahanbakurequest;
use App\Http\Requests\bahanbaku\updatebahanbakurequest;
use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\Category;
use App\Models\Unit;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Illuminate\Support\Facades\Storage;


class BahanBakuController extends Controller
{
    public function index()
    {
        $bahan= BahanBaku::where("user_id", auth()->id())->count();

        return view('bahanbakus.index', [
            'bahanbakus' => $bahan,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(storebahanbakurequest $request)
    {
        /**
         * Handle upload image
         */
        $image = "";
        if ($request->hasFile('fotobahan')) {
            $image = $request->file('fotobahan')->store('bahanbakus', 'public');
        }

        BahanBaku::create([
            "kodebahan" => IdGenerator::generate([
                'table' => 'bahan_bakus',
                'field' => 'kodebahan',
                'length' => 4,
                'prefix' => 'BB'
            ]),

            'fotobahan'     => $image,
            'namabahan'     => $request->namabahan,
            'category_id'   => $request->category_id,
            'unit_id'       => $request->unit_id,
            'stokbahan'     => $request->stokbahan,
            'hargabeli'     => $request->hargabeli,
            'detailbahan'   => $request->detailbahan,
            'tanggalmasuk'  =>$request->tanggalmasuk,
            'jenisbahan'    =>$request->jenisbahan,
            "user_id" => auth()->id(),
            
        ]);


        return to_route('bahanbakus.index')->with('Berhasil..!', 'Bahan baku telah ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(BahanBaku $bahanbaku)
    {
        abort_unless($bahanbaku->user_id === auth()->id(), 404);

        // Generate a barcode
        $generator = new BarcodeGeneratorHTML();

        $barcode = $generator->getBarcode($bahanbaku->kodebahan, $generator::TYPE_CODE_128);

        return view('bahanbakus.show', [
            'bahan' => $bahanbaku,
            'barcode' => $barcode,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BahanBaku $bahanbaku)
    {
        abort_unless($bahanbaku->user_id === auth()->id(), 404);

        return view('bahanbakus.edit', [
            'bahan' => $bahanbaku,
            'categories' => Category::where("user_id", auth()->id())->get(),
            'units' => Unit::where("user_id", auth()->id())->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updatebahanbakurequest $request, BahanBaku $bahanbaku)
    {
        abort_unless($bahanbaku->user_id === auth()->id(), 404);

        $image = $bahanbaku->fotobahan;
        if ($request->hasFile('fotobahan')) {
            if ($bahanbaku->fotobahan) {
                Storage::disk('public')->delete($bahanbaku->fotobahan);
            }

            $image = $request->file('fotobahan')->store('bahanbakus', 'public');
        }

        $bahanbaku->update([
            'fotobahan' => $image,
            'namabahan' => $request->namabahan,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'stokbahan' => $request->stokbahan,
            'hargabeli' => $request->hargabeli,
            'detailbahan' => $request->detailbahan,
            'tanggalmasuk' => $request->tanggalmasuk,
            'jenisbahan' => $request->jenisbahan,
        ]);

        return redirect()
            ->route('bahanbakus.index')
            ->with('success', 'Bahan baku has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BahanBaku $bahanbaku)
    {
        abort_unless($bahanbaku->user_id === auth()->id(), 404);

        if ($bahanbaku->fotobahan) {
            Storage::disk('public')->delete($bahanbaku->fotobahan);
        }

        $bahanbaku->delete();

        return redirect()
            ->route('bahanbakus.index')
            ->with('success', 'Bahan baku has been deleted!');
    }
}
