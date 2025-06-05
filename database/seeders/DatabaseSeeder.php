<?php

namespace Database\Seeders;

use App\Models\Comments;
use App\Models\Posts;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // Uncomment the following lines to seed the database with data from the API
        $this->fetchDataFromApiUser();
        $this->fetchDataFromApiPost();
        $this->fetchDataFromApiComment();

    }

    private function fetchDataFromApiUser(): void
    {
        $url = env('APi_JSON_PLACEHOLDER');
        $response = Http::get("$url/users");
        $users = $response->json();

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'address' => json_encode($user['address']),
                'phone' => $user['phone'],
                'website' => $user['website'],
                'company' => json_encode($user['company']),
                'password' => bcrypt('password'), // Default password, change as needed
            ]);
        }
    }

    private function fetchDataFromApiPost(): void
    {
        $url = env('APi_JSON_PLACEHOLDER');
        $response = Http::get("$url/posts");
        $users = $response->json();

        foreach ($users as $user) {
            Posts::create([
                'user_id' => $user['userId'],
                'title' => $user['title'],
                'body' => $user['body'],
            ]);
        }
    }

    private function fetchDataFromApiComment(): void
    {
        $url = env('APi_JSON_PLACEHOLDER');
        $response = Http::get("$url/comments");
        $users = $response->json();

        foreach ($users as $user) {
            Comments::create([
                'post_id' => $user['postId'],
                'name' => $user['name'],
                'email' => $user['email'],
                'body' => $user['body'],
            ]);
        }
    }
}
