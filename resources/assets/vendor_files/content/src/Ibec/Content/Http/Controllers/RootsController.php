<?php namespace Ibec\Content\Http\Controllers;

use Ibec\Content\CategoryNode;
use Ibec\Content\Post;
use Ibec\Content\Root;
use Ibec\Content\Http\Requests;
use Ibec\Admin\Http\Controllers\Controller;
use Illuminate\Database\DatabaseManager;
use Ibec\Content\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class RootsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		return view('content::roots.index', ['roots' => Root::all()]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if  (!Auth::guard('admin')->user()->super_user)
			return '<div style="font-size:20px">Обратитесь к разработчику , у вас недостаточно прав</div>';


		$this->document->breadcrumbs([
			'Create root' => ''
		]);


		return view('content::roots.form',
			[
				'root' => null,
				'configPosts' => [],
				'target' => 'store',
			]
		);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Ibec\Content\Http\Controllers\Response
	 */
	public function store(Request $request)
	{
		if  (!Auth::guard('admin')->user()->super_user)
			return '<div style="font-size:20px">Обратитесь к разработчику , у вас недостаточно прав</div>';
		$validator = app('validator')->make(
			[
				'slug' => ($slug = $request->input('slug')),
				'title' => ($title = $request->input('title', [])),
				'config' => ($config = $request->input('config', [])),
			],
			[
				'slug' => ['required', 'max:255', 'unique:roots', 'alpha_dash'],
				'title' => ['array'],
				'config' => ['array'],
				'config.posts' => ['array'],
				'config.posts.fields' => ['array'],
				'config.posts.options' => ['array'],
			]
		);

		$root = new Root();

		if ($validator->passes())
		{
			$root->getConnection()->beginTransaction();
			try
			{
				$category = new Category();
				$category->save();
				$category->makeRoot();

				$locales = config('app.locales', []);
				$nodes = [];
				foreach ($title as $key => $value)
				{
					if (in_array($key, $locales))
					{
						$nodes[$key] = new CategoryNode();
						$nodes[$key]->title = $value;
						$nodes[$key]->slug = $slug;
						$nodes[$key]->language_id = $key;
					}
				}

				if ($nodes)
				{
					$category->nodes()->saveMany($nodes);
				}

				$root->slug = $slug;
				$root->title = $title;
				$root->id = $category->id;
				$root->config = $config;
				$root->save();
			}
			catch (\Exception $e)
			{
				$root->getConnection()->rollback();
				return redirect()->back()->withErrors(
					new MessageBag([$e->getMessage()])
				);
			}

			$root->getConnection()->commit();
		}
		else
		{
			return redirect(admin_route('content.roots.create'))->withErrors($validator->errors());
		}

		return redirect(admin_route('content.roots.index'))->with('message', 'created');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  Root  $root
	 * @return Response
	 */
	public function show(Root $root)
	{
		$this->document->breadcrumbs([
			ucfirst($root->slug) => ''
		]);

		$childCategoriesCount = $root->category->children->count();
		$childCategories = $root->category->children->take(5);
		$categoriesList = $root->category->descendantsAndSelf()->pluck('id');
		$childPosts = Post::query()
			->whereIn('category_id', $categoriesList)
			->orderBy('created_at', 'DESC')
			->take(5)->get();
		$childPostsCount = Post::query()
			->whereIn('category_id', $categoriesList)
			->count();

		$this->document->page->title(ucfirst($root->slug));

		return view('content::roots.show', [
			'root' => $root,
			'childCategories' => $childCategories,
			'childCategoriesCount' => $childCategoriesCount,
			'childPostsCount' => $childPostsCount,
			'childPosts' => $childPosts]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  Root  $root
	 * @return Response
	 */
	public function edit(Root $root)
	{
		if  (!Auth::guard('admin')->user()->super_user)
			return '<div style="font-size:20px">Обратитесь к разработчику , у вас недостаточно прав</div>';
		$this->document->breadcrumbs([
			ucfirst($root->slug) => admin_route('content.roots.show', [$root->slug]),
			ucfirst($root->slug).' edit' => ''
		]);

		$i = 0;
		$configPosts = array_build($root->config('posts.fields', []), function($key, $value) use (&$i)
		{
			return [$i++, ['name' => $key, 'type' => $value]];
		});

		return view('content::roots.form',
			[
				'root' => $root,
				'configPosts' => $configPosts,
				'target' => 'update',
			]
		);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Root  $root
	 * @return Response
	 */
	public function update(Request $request, Root $root)
	{
		if  (!Auth::guard('admin')->user()->super_user)
			return '<div style="font-size:20px">Обратитесь к разработчику , у вас недостаточно прав</div>';
		$validator = app('validator')->make(
			[
				'slug' => ($slug = $request->input('slug')),
				'title' => ($title = $request->input('title', [])),
				'config' => ($config = $request->input('config', [])),
			],
			[
				'slug' => ['required', 'max:255', 'unique:roots,slug,'.$root->slug.',slug', 'alpha_dash'],
				'title' => ['array'],
				'config' => ['array'],
				'config.posts' => ['array'],
				'config.posts.fields' => ['array'],
				'config.posts.options' => ['array'],
			]
		);
		if ($validator->passes())
		{
			$root->slug = $slug;
			$root->title = $title;
			$root->config = $config;
			$root->save();
		}
		else
		{
			return redirect(admin_route('content.roots.edit', [$root->slug]))->withErrors($validator->errors());
		}

		return redirect(admin_route('content.roots.index'))->with('message', 'updated');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Root  $root
	 * @return Response
	 */
	public function confirmDestroy(Root $root)
	{


		$this->document->breadcrumbs([
			ucfirst($root->slug) => admin_route('content.roots.show', [$root->slug]),
			ucfirst($root->slug).' confirm destroy' => '',
		]);

		return view('content::roots.confirm-destroy', ['root' => $root,]);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Root  $root
	 * @return Response
	 */
	public function destroy(Root $root)
	{

		if  (!Auth::guard('admin')->user()->super_user)
			return '<div style="font-size:20px">Обратитесь к разработчику , у вас недостаточно прав</div>';

		$root->category->descendantsAndSelf()->delete();
		Post::query()->where('category_id','=',$root->category->id)->delete();
		$root->fields()->delete();
		$root->terms()->delete();
		$root->delete();

		return redirect(admin_route('content.roots.index'));
	}

}
