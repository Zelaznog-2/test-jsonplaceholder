<?php

namespace App\Console\Commands;

use App\Models\Comments;
use Illuminate\Console\Command;
use App\Traits\ServiceJsonPlaceholderTrait;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\CommentsController;
use App\Jobs\CommentsPerUserJob;
use Illuminate\Support\Facades\Log;

class FetchCommentsData extends Command
{
    use ServiceJsonPlaceholderTrait;

    /**
     * The comments controller instance.
     *
     * @var mixed
     */
    protected $commentsCtrl = null;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-comments-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch comments data from the API and store it in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting the comments data fetch process...');

        // Fetch data from the API
        $comments = $this->getDataComment();

        if (empty($comments)) {
            $this->error('No comments data found or an error occurred.');
            return;
        }

        $this->commentsCtrl = new CommentsController();

        // Process and store the comments data
        foreach ($comments as $comment) {
            try {
                $this->info("Storing comment to post ID: {$comment['postId']}");

                // Check if the comment already exists
                $existingComment = Comments::where('id', $comment['id'])->first();
                if ($existingComment) {
                    $this->info("Comment with ID {$comment['id']} already exists. Skipping...");
                    continue;
                }

                $requestData = [
                    'post_id' => $comment['postId'],
                    'name' => $comment['name'],
                    'email' => $comment['email'],
                    'body' => $comment['body'],
                ];
                $request = request()->merge($requestData);

                // Store the comment
                $this->commentsCtrl->store($request);
            } catch (\Throwable $th) {
                $this->error('An error occurred while storing comment: ' . $th->getMessage());
                Log::error('Error storing comment: ' . $th->getMessage(), [
                    'comment_id' => $comment['id'],
                    'post_id' => $comment['postId'],
                ]);
            }
        }

        // Dispatch the CommentsPerUserJob for each user
        CommentsPerUserJob::dispatch();

        $this->info('Comments data fetch process completed successfully.');
        Log::info('Comments data fetch process completed successfully.');
    }

    private function getDataComment()
    {
        try {
            $this->info('Fetching comments data from the API...');
            $endpoint = '/comments';
            $response = Cache::remember('comments_data_api', now()->addMinutes(30) , function () use ($endpoint) {

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
