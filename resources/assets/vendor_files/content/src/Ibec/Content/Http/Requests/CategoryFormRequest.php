<?php namespace Ibec\Content\Http\Requests;

use Ibec\Admin\Http\Requests\LocaleRequest;

class CategoryFormRequest extends LocaleRequest {

	/**
	 * Array of rules applied to locale-specific fields
	 *
	 * @var array
	 */
	protected $localeRules = [
		'title' => 'required_with:{{lang}}.slug,{{lang}}.description|max:255',
		'slug'  => 'required_with:{{lang}}.title,alpha_dash|max:255|unique:category_nodes,slug,{{current}},slug,language_id,{{lang}}',
	];

	/**
	 * Array of fields required at least for one locale
	 *
	 * @var array
	 */
	protected $localeRequired = [
		'title',
	];

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		/** @var \Ibec\Content\Root $root */
		$root = $this->route('roots');

		/** @var \Ibec\Content\Category $category */
		$category = $this->route('categories');

		$rules = [];

		$rules['image_id'] = 'numeric|exists:images,id';

		if ($category && $category->isRoot())
		{
			// Parent id should not be provided for root categories
			$rules['parent_id'] = 'size:0';
		}
		else
		{
			// Fetch all descendants of root
			$available = $root->category->descendantsAndSelf()->pluck('id');

			if ($category)
			{
				// If category exists, it cannot become a child of self
				// or any of its descendants
				$unavailable = $category->descendantsAndSelf()->pluck('id');
				$available = array_diff($available, $unavailable);
			}

			$rules['parent_id'] = 'in:' . implode(',', $available->toArray());
		}

		if (!$category)
		{
			$this->localeRules['slug'] = str_replace('{{current}}', 'NULL', $this->localeRules['slug']);
		}

		$rules = array_merge(parent::rules(), $rules);

		if ($category)
		{
			// Ensure that slug is not validated against existing one
			foreach ($this->getLocaleList() as $locale)
			{
				$slug = ($node = $category->$locale) ? $node->slug : 'NULL';
				$rules[$locale.'.slug'] = str_replace('{{current}}', $slug, $rules[$locale.'.slug']);
			}
		}

		return $rules;
	}

}
