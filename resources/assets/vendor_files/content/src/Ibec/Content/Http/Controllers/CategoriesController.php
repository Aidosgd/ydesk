<?php namespace Ibec\Content\Http\Controllers;

use Ibec\Content\Category;
use Ibec\Content\Root;
use Ibec\Content\Post;
use Ibec\Content\CategoryNode;
use Ibec\Content\Http\Requests;
use Ibec\Admin\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Intervention\Image\Image;
use Symfony\Component\Console\Input\Input;

class CategoriesController extends Controller {



	/**
	 * Display a listing of the resource.
	 *
	 * @param Root $root
	 * @return \Illuminate\Http\Response
	 */
	public function index(Root $root, Request $request)
	{
		$this->document->breadcrumbs([
			ucfirst($root->slug) => admin_route('content.roots.show', [$root->slug]),
			'Categories' => ''
		]);


		$this->document->page->title(ucfirst($root->slug) . " > Categories");

		$categories = Category::roots()->find($root->id)->descendantsAndSelf();


		if ($title = $request->input('title'))
		{
			$categories = $categories->whereHas('nodes', function($q) use ($title)
			{
				$q->where('title', 'like', '%'.$title.'%');
			});


		}



		if ($request->wantsJson())
		{
			$fields = $request->input('fields', null);
			$data = $categories->get();
			if ($fields)
			{
				$data = $data->map(function($item) use ($fields){
					return array_only($item->toArray(), $fields);
				});
			}

			return $data;
		}

		$categories = $categories->get();



		return view('content::categories.index',
			[
				'categories' => $categories,
				'root' => $root,
				'batchAction' => admin_route('content.roots.categories.deleteBatch', [$root->slug])
			]
		);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @param Root $root
	 * @return \Illuminate\Http\Response
	 */
	public function create(Root $root)
	{
		$this->document->breadcrumbs([
			ucfirst($root->slug) => admin_route('content.roots.show', [$root->slug]),
			'Categories' => admin_route('content.roots.categories.index', [$root->slug]),
			'Categoriy create' => ''
		]);


		$this->document->page->title(ucfirst($root->slug) . " > Category create");

		$image = null;
		$cropped_coords = null;

		if(\Illuminate\Support\Facades\Input::old()){

			$image = \Ibec\Media\Image::where('id', \Illuminate\Support\Facades\Input::old('image_id'))->first();

			$cropped_coords = \Illuminate\Support\Facades\Input::old('cropped_coords');

		}

		return view('content::categories.form',
			[
				'image' => $image,
				'category' => null,
				'target' => 'store',
				'cropped_coords' => $cropped_coords,
				'root' => $root,
				'categories' => Category::getIdentedList('-', $root->id),
			]
		);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Requests\CategoryFormRequest $request
	 * @param Root $root
	 * @return \Illuminate\Http\Response
	 */
	public function store(Requests\CategoryFormRequest $request, Root $root)
	{

		$parent = Category::find($request->input('parent_id'));

		$data = [];

		$category = $parent->children()->create($data);

		if($request->input('image_id'))
		{
			$category->images()->sync([ $request->input('image_id')]);
			$category->images()->update(
				[
					'title' => $request->input('image_title'),
					'alt' => $request->input('image_alt'),
					'cropped_coords' => $request->input('cropped_coords')?$request->input('cropped_coords'):null
				]
			);
		}

		$nodes = [];

		foreach (config('app.locales') as $locale)
		{

			$data = array_filter($request->input($locale, []), 'strlen');

			if ($data)
			{
				$node = new CategoryNode();
				$data['published'] = array_get($data, 'published', false);
				$node->fill($data);
				$node->language_id = $locale;
				$node->category_id=$category->id;
				$nodes[$locale] = $node;
			}
		}

		$category->nodes()->saveMany($nodes);
		return redirect(admin_route('content.roots.categories.index', [$root->slug]));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param Root $root
	 * @param Category $category
	 * @return \Illuminate\Http\Response
	 */
	public function show(Root $root, Category $category)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Root $root
	 * @param Category $category
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Root $root, Category $category)
	{

		$lockedCategories = config('lockedCategories')?:[];
		if  (($category->isRoot() && !Auth::guard('admin')->user()->super_user) || (in_array($category->node->slug, $lockedCategories)&& !Auth::guard('admin')->user()->super_user))
			return '<div style="font-size:20px">Обратитесь к разработчику , у вас недостаточно прав</div>';


		$this->document->breadcrumbs([
			ucfirst($root->slug) => admin_route('content.roots.show', [$root->slug]),
			'Categories' => admin_route('content.roots.categories.index', [$root->slug]),
			'Category edit' => ''
		]);


		$this->document->page->title(ucfirst($root->slug) . " > Category edit");

		$image = $category->images()->withPivot('title', 'alt', 'cropped_coords')->first();

		$cropped_coords = $image
			?$image->pivot->cropped_coords
			:null;

		if(\Illuminate\Support\Facades\Input::old()){

			$image = \Ibec\Media\Image::where('id', \Illuminate\Support\Facades\Input::old('image_id'))->first();
			$cropped_coords = \Illuminate\Support\Facades\Input::old('cropped_coords');
		}

		return view('content::categories.form',
			[
				'category' => $category,
				'target' => 'update',
				'image'=> $image,
				'root' => $root,
				'cropped_coords' => $cropped_coords,
				'categories' => Category::getIdentedList('-', $root->id, [$category]),
			]
		);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Requests\CategoryFormRequest    $request
	 * @param \Ibec\Content\Root $root
	 * @param \Ibec\Content\Category      $category
	 *
	 * @return \Ibec\Content\Http\Controllers\\Illuminate\Http\Response
	 */
	public function update(Requests\CategoryFormRequest $request, Root $root, Category $category)
	{
		$lockedCategories = config('lockedCategories')?:[];
		if  (($category->isRoot() && !Auth::guard('admin')->user()->super_user) || (in_array($category->node->slug, $lockedCategories)&& !Auth::guard('admin')->user()->super_user))
			return '<div style="font-size:20px">Обратитесь к разработчику , у вас недостаточно прав</div>';
		$parent = $request->input('parent_id', $category->getParentId());

		if ($parent != $category->getParentId())
		{
			$available = Category::roots()->find($root->id)->descendantsAndSelf()->where('id', '!=', $category->id)->findOrFail($parent);
			$category->makeChildOf($available);
		}

		$category->save();

		if($request->input('image_id'))
		{
			$category->images()->sync([ $request->input('image_id')]);
			$category->images()->update(
				[
					'title' => $request->input('image_title'),
					'alt' => $request->input('image_alt'),
					'cropped_coords' => $request->input('cropped_coords')?$request->input('cropped_coords'):null
				]
			);
		} else {
			$category->images()->detach();
		}



		$nodes = $category->nodes->all();

		foreach (config('app.locales') as $locale)
		{
			$data = $request->input($locale, []);
			$filtered = array_filter($data, 'strlen');

			$node = array_get($nodes, $locale, null);

			if ($node)
			{
				if (!empty($filtered))
				{
					$data['published'] = array_get($data, 'published', false);
					$node->fill($data);
					$node->language_id = $locale;
					$nodes[$locale] = $node;
				}
				else
				{
					$node->delete();
					unset($nodes[$locale]);
				}
			}
			elseif ($filtered)
			{
				$node = new CategoryNode();
				$data['published'] = array_get($data, 'published', false);
				$node->fill($data);
				$node->language_id = $locale;
				$node->category_id=$category->id;
				$nodes[$locale] = $node;
			}
		}

		$category->nodes()->saveMany($nodes);

		return redirect(admin_route('content.roots.categories.index', [$root->slug]));

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Root $root
	 * @param Category $category
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Root $root, Category $category)
	{
		$lockedCategories = config('lockedCategories')?:[];
		if  (($category->isRoot() && !Auth::guard('admin')->user()->super_user) || (in_array($category->node->slug, $lockedCategories)&& !Auth::guard('admin')->user()->super_user))
			return '<div style="font-size:20px">Обратитесь к разработчику , у вас недостаточно прав</div>';

		Post::where('category_id','=',$category->id)->delete();
		$category->images()->detach();
		$category->delete();

		return redirect(admin_route('content.roots.categories.index', [$root->slug]));
	}

	public function deleteBatch(Request $request, $action = null) {


		$ids = $request->input('selected', []);
		if ($ids)
		{

			$categories = Category::whereIn('id', $ids)->get();

			foreach($categories as $category)
			{
				$lockedCategories = config('lockedCategories')?:[];
				if  (($category->isRoot() && !Auth::guard('admin')->user()->super_user) || (in_array($category->node->slug, $lockedCategories)&& !Auth::guard('admin')->user()->super_user))
					return;

				Post::where('category_id', '=', $category->id)->delete();
				$category->images()->detach();
				$category->delete();
			}
		}
	}

}
