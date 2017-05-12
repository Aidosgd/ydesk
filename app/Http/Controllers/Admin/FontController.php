<?php namespace App\Http\Controllers\Admin;


use App\Font;
use Illuminate\Http\Request;

class FontController extends \Ibec\Admin\Http\Controllers\Controller{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $this->document->breadcrumbs([
            'Font Fields' => '',
        ]);
		$font = Font::paginate(config('admin.pagination'));
		return view('admin.font.index',
			[
				'font' => $font,
				'batchAction' => admin_route('seo.create'),
			]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		$this->document->breadcrumbs([
			'Font Fields' => admin_route('seo.index'),
			'create Font' => ''
		]);


		return view('admin.font.form',
			[
				'font' => null,
				'target' => 'store',
			]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$font_fields = new Font();

		$font_fields->fill([
//			'font_url' => $request->input('font_url'),
//			'font_size' => $request->input('font_size'),
			'font_family' => $request->input('font_family'),
		]);
		$font_fields->save();

		return redirect(admin_route('font.index'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Font $font)
	{
		$this->document->breadcrumbs([
			'font index' => admin_route('seo.index'),
			'edit font' => ''
		]);

		return view('admin.font.form',
			[
				'font' => $font,
				'target' => 'update',
			]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, Font $font)
	{
		$font->fill([
//			'font_url' => $request->input('font_url'),
//			'font_size' => $request->input('font_size'),
			'font_family' => $request->input('font_family'),
		]);

		$font->update();

		return redirect(admin_route('font.index'));
	}

	function destroy(Font $seo) {
		$seo->delete();
		return redirect()->back();
	}

	public function deleteBatch(Request $request, $action = null) {

		$ids = $request->input('selected', []);
		if ($ids)
		{
			Font::whereIn('id', $ids)->delete();
		}
	}

}
