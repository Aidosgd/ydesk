<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ContentPackageSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker = \Faker\Factory::create();

		$users = \Ibec\Acl\User::lists('id');

		$allCategories = [];
		$rootCategories = [];

		foreach (['Articles', 'News', 'Blog', 'Pages'] as $rootName)
		{
			$root = new \Ibec\Content\Root();
			$root->getConnection()->beginTransaction();
			try
			{
				$category = new \Ibec\Content\Category();
				$category->save();
				$category->makeRoot();

				$root->slug = str_slug($rootName);
				$root->title = ['en' => $rootName];
				$root->id = $category->id;
				$root->save();

				$node = $category->en;

				$ok = $node->fill([
					'category_id' => $category->id,
					'language_id' => 'en',
					'title' => $rootName,
					'description' => '',
					'slug' => str_slug($rootName)
				]);
			}
			catch (\Exception $e)
			{
				$root->getConnection()->rollback();
				$this->command->error($e->getMessage());
				$ok = false;
			}

			if ($ok)
			{
				$rootCategories[$category->id] = $category;
				$allCategories[] = $category->id;
				$root->getConnection()->commit();
			}
		}

		foreach($rootCategories as $id => $rootCategory)
		{
			$children = [];
			for ($i = 0; $i < 20; $i ++)
			{
				$child = new \Ibec\Content\Category();
				$child->getConnection()->beginTransaction();
				try
				{
					$child->save();

					if (empty($children) || rand(0, 3) < 1)
					{
						$child->makeChildOf($rootCategory);
					}
					else
					{
						$child->makeChildOf($children[array_rand($children)]);
					}

					$name = $faker->city;

					$node = $child->en;
					$ok = $node->fill([
						'category_id' => $child->id,
						'language_id' => 'en',
						'title' => $name,
						'description' => $faker->text,
						'slug' => str_slug($name)
					])->save();

				}
				catch (Exception $e)
				{
					$child->getConnection()->rollback();
					$this->command->error($e->getMessage());
					$ok = false;
				}

				if ($ok)
				{
					$allCategories[] = $child->id;
					$child->getConnection()->commit();
				}
			}
		}

		for ($i = 0; $i < 0; $i++)
		{
			$post = new \Ibec\Content\Post();
			$post->getConnection()->beginTransaction();
			try {

				$post->fill([
					'user_id' => $users[array_rand($users)],
					'category_id' => $allCategories[array_rand($allCategories)],
				])->save();

				$node = $post->en;

				$name = $faker->sentence;

				$ok = $node->fill([
					'post_id' => $post->id,
					'language_id' => 'en',
					'slug' => str_slug($name),
					'title' => $name,
					'teaser' => $faker->text(150),
					'content' => $faker->text(500),
				])->save();
			}
			catch (Exception $e)
			{
				$post->getConnection()->rollBack();
				$this->command->error($e->getMessage());
				$ok = false;
			}

			if ($ok)
			{
				$post->getConnection()->commit();
			}
		}
	}

}
