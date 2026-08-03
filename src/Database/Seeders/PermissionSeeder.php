<?php


namespace Apachish\Blog\Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    static public function run()
    {
        $permission_panel = Permission::where("name", "panel")->first();
        if (empty($permission_panel)) return;

        $guard_names = ['web', 'project'];
        foreach ($guard_names as $guard_name) {
            $permission_blogs = Permission::updateOrCreate(
                [
                    "name" => "blogs",
                ],
                [
                    "title" => "blog::messages.Blogs",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_panel->id
                ]);



            $permission_categories = Permission::updateOrCreate(
                [
                    "name" => "categories",
                ],
                [
                    "title" => "blog::messages.Categories",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_blogs->id
                ]);

            $permission_posts = Permission::updateOrCreate(
                [
                    "name" => "posts",
                ],
                [
                    "title" => "blog::messages.Posts",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_blogs->id
                ]);

            $permission_tags = Permission::updateOrCreate(
                [
                    "name" => "tags",
                ],
                [
                    "title" => "blog::messages.Tags",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_blogs->id
                ]);

            $permission_comments = Permission::updateOrCreate(
                [
                    "name" => "comments",
                ],
                [
                    "title" => "blog::messages.Comments",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_blogs->id
                ]);

            $permissions = [
                [
                    "name" => 'create_post',
                    "title" => "blog::messages.Create Post",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_posts->id
                ],
                [
                    "name" => 'edit_post',
                    "title" => "blog::messages.Edit Post",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_posts->id
                ],
                [
                    "name" => 'delete_post',
                    "title" => "blog::messages.Delete Post",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_posts->id
                ],
                [
                    "name" => 'create_category',
                    "title" => "blog::messages.Create Category",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_categories->id
                ],
                [
                    "name" => 'edit_category',
                    "title" => "blog::messages.Edit Category",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_categories->id
                ],
                [
                    "name" => 'delete_category',
                    "title" => "blog::messages.Delete Category",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_categories->id
                ],
                [
                    "name" => 'create_tag',
                    "title" => "blog::messages.Create Tag",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_tags->id
                ],
                [
                    "name" => 'edit_tag',
                    "title" => "blog::messages.Edit Tag",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_tags->id
                ],
                [
                    "name" => 'delete_tag',
                    "title" => "blog::messages.Delete Tag",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_tags->id
                ],
                [
                    "name" => 'edit_comment',
                    "title" => "blog::messages.Edit Coment",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_comments->id
                ],
                [
                    "name" => 'delete_comment',
                    "title" => "blog::messages.Delete Comment",
                    "guard_name" => $guard_name,
                    "parent_id" => $permission_comments->id
                ],
            ];
            foreach ($permissions as $set) {
                $permission = Permission::updateOrCreate(['name' => $set['name']], [
                    'title' => $set['title'],
                    'guard_name' => $set['guard_name'],
                    'parent_id' => $set['parent_id'],
                ]);

                if (!empty($set['children'])) {
                    foreach ($set['children'] as $child) {
                        $child['parent_id'] = $permission->id;
                        $child_permission = Permission::updateOrCreate(['name' => $child['name']], $child);
                    }
                }
            }
        }
    }
}
