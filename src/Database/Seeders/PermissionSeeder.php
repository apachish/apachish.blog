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
        $permission_panel = Permission::where("name","panel")->first();
        if(empty($permission_panel))  return;

        $permission_categories = Permission::updateOrCreate(
            [
                "name" => "categories",
            ],
            [
                "title" => "blog::messages.Categories",
                "guard_name" => "web",
                "parent_id"=> $permission_panel->id
            ]);
        $permission_categories->assignRole(["developer","admin"]);

        $permission_posts = Permission::updateOrCreate(
            [
                "name" => "posts",
            ],
            [
                "title" => "blog::messages.Posts",
                "guard_name" => "web",
                "parent_id"=> $permission_panel->id
            ]);
        $permission_posts->assignRole(["developer","admin"]);
        $permission_tags = Permission::updateOrCreate(
            [
                "name" => "tags",
            ],
            [
                "title" => "blog::messages.Tags",
                "guard_name" => "web",
                "parent_id"=> $permission_panel->id
            ]);
        $permission_tags->assignRole(["developer","admin"]);
        $permission_comments = Permission::updateOrCreate(
            [
                "name" => "comments",
            ],
            [
                "title" => "blog::messages.Comments",
                "guard_name" => "web",
                "parent_id"=> $permission_panel->id
            ]);
        $permission_comments->assignRole(["developer","admin"]);

        $permissions = [
            [
                "name" => 'edit_post',
                "title" => "blog::messages.Edit Post",
                "guard_name" => "web",
                "parent_id"=> $permission_posts->id
            ],
            [
                "name" => 'delete_post',
                "title" => "blog::messages.Delete User",
                "guard_name" => "web",
                "parent_id"=> $permission_posts->id
            ],
        ];
        foreach ($permissions as $set) {
            $permission = Permission::updateOrCreate(['name' => $set['name']], [
                'title' => $set['title'],
                'guard_name' => $set['guard_name'],
                'parent_id' => $set['parent_id'],
            ]);
            $permission->assignRole(["developer", "admin"]);
            if(!empty($set['children'])) {
                foreach ($set['children'] as $child) {
                    $child['parent_id'] = $permission->id;
                    $child_permission = Permission::updateOrCreate(['name' => $child['name']], $child);
                    $child_permission->assignRole(["developer", "admin"]);

                }
            }
        }
    }
}
