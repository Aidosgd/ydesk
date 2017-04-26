<?php namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\SeoFields;
use App\Models\SeoFieldsNode;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Ibec\Media\Image;
use Illuminate\Support\Facades\Input;


class SeoController extends \Ibec\Admin\Http\Controllers\Controller{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $this->document->breadcrumbs([
            'Seo Fields' => '',
        ]);
		$seo = SeoFields::paginate(config('admin.pagination'));
		return view('admin.seo.index',
			[
				'seo' => $seo,
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
			'SeoFieldss' => admin_route('seo.index'),
			'create SeoFields' => ''
		]);


		return view('admin.seo.form',
			[
				'seo' => null,
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
//		dd($request->all());
		$seo_fields = new SeoFields();
		$seo_fields->save();

		foreach (config('app.locales') as $locale)
		{
			$localeFields = $request->input($locale, []);

			$data = array_filter($localeFields, 'strlen');
			if ($data)
			{
				$node = new SeoFieldsNode();
				$node->seo_fields_id = $seo_fields->id;
				$node->fill($data);
				$node->language_id = $locale;
				$node->save();
			}
		}

		return redirect(admin_route('seo.index'));
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
	public function edit(SeoFields $seo)
	{
		$this->document->breadcrumbs([
			'SeoFieldss' => admin_route('seo.index'),
			'edit SeoFields' => ''
		]);

		return view('admin.seo.form',
			[
				'seo' => $seo,
				'target' => 'update',
			]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, SeoFields $seo_fields)
	{
		$seo_fields->update();

		$nodes = $seo_fields->nodes->all();

		foreach (config('app.locales') as $locale)
		{
			$localeFields = $request->input($locale, []);
			$filtered = array_filter($localeFields, 'strlen');


			$node = array_get($nodes, $locale, null);

			if ($node)
			{
				if (!empty($filtered))
				{
					$node->fill($localeFields);
					$node->language_id = $locale;
					$node->seo_fields_id = $seo_fields->id;
					$node->save();
				}
				else
				{
					$node->delete();
				}
			}
			elseif ($filtered)
			{
				$node = new SeoFieldsNode();
				$node->seo_fields_id = $seo_fields->id;
				$node->fill($filtered);
				$node->language_id = $locale;
				$node->save();
			}
		}

		return redirect(admin_route('seo.index'));
	}

	function destroy(SeoFields $seo) {
		$seo->delete();
		return redirect()->back();
	}

	public function deleteBatch(Request $request, $action = null) {

		$ids = $request->input('selected', []);
		if ($ids)
		{
			SeoFields::whereIn('id', $ids)->delete();
		}
	}

}
