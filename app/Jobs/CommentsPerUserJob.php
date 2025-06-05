<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;

class CommentsPerUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting CommentsPerUserJob...');

        $users = User::with('posts.comments')->get();
        $counts = $users->map(function ($user) {
            $uuid = Uuid::uuid4()->toString();            
            $data = [
                'user_id' => $user->id,
                'name' => $user->name,
                'comment_count' => $user->posts->sum(function ($post) {
                    return $post->comments->count();
                }),
            ];


            Log::info("CommentsPerUserJob[$uuid] Procesados comentarios para user_id={$user->id}: total={$data['comment_count']}");
            return $data;
        });
        
        Storage::disk('public')->put('exports/user_comment_counts.json', $counts->toJson());

        
    }
}
