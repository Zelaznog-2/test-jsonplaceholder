<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\ServiceJsonPlaceholderTrait;
use App\Models\Posts;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Log;


class FetchPostsData extends Command
{
    use ServiceJsonPlaceholderTrait;

    /**
     * The posts controller instance.
     *
     * @var mixed
     */
    protected $postsCtrl = null;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-posts-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch posts data from the API and store it in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting the posts data fetch process...');

        // Fetch data from the API
        $posts = $this->getDataPost();

        if (empty($posts)) {
            $this->error('No posts data found or an error occurred.');
            return;
        }

        $this->postsCtrl = new PostsController();

        // Process and store the posts data
        foreach ($posts as $post) {
            try {
                $this->info("Storing post: {$post['title']}");

                // Check if the post already exists
                $existingPost = Posts::where('id', $post['id'])->first();

                $requestData = [
                    'user_id' => $post['userId'],
                    'title' => $post['title'],
                    'body' => $post['body'],
                ];
                // Prepare the request
                $request = request()->merge($requestData);

                if ($existingPost){
                    $response = $this->postsCtrl->update($request, $existingPost->id);
                } else {
                    $response = $this->postsCtrl->store($request);
                }

                if ($response->getStatusCode() === 201 || $response->getStatusCode() === 200) {
                    $type = 'created successfully.';
                    if ($existingPost) {
                        $type = 'update successfully.';
                    }

                    $this->info("Post title {$post['title']} {$type}");
                } else {
                    $this->error("Failed to store/update post title {$post['title']}: " . $response->getContent());
                    Log::error("Failed to store/update post title {$post['title']}: " . $response->getContent());
                }

            } catch (\Throwable $th) {
                $this->error('An error occurred while storing post: ' . $th->getMessage());
                Log::error('An error occurred while storing post: ' . $th->getMessage());
            }
        }

        $this->info('Posts data fetch process completed.');
        Log::info('Posts data fetch process completed.');
    }

    private function getDataPost(): array
    {
        try {
            $this->info('Fetching posts data from the API...');
            $endpoint = '/posts';
            $response = Cache::remember('posts_data_api', now()->addMinutes(30) , function () use ($endpoint) {

                $apiResponse = $this->fetchDataFromApi($endpoint);
                if (isset($apiResponse['error'])) {
                    throw new \Exception("API Error: " . $apiResponse['message']);
                }
                
                
                return $apiResponse['data'];
            });

            $this->info('Data fetched successfully.');
            return $response;
        } catch (\Throwable $th) {
            $this->error('An error occurred while fetching data: ' . $th->getMessage());
            return [];
        }
    }
}
