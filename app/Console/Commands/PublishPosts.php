<?php

namespace App\Console\Commands;

use App\Mail\PublishPostMail;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

#[Signature('app:publish-posts')]
#[Description('update Publish at timestamp for unpublished posts')]
class PublishPosts extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $publishedCount = Post::query()
            // if you want to publish only posts that have not been published yet, you can uncomment the following line
//            ->whereNull('published_at')
            ->where('status', 'draft')
            ->update([
                'published_at' => now(),
                'status' => 'active',
            ]);

        if ($publishedCount === 0) {
            $this->info('No unpublished posts found.');

            Log::info('No unpublished posts found.');

            return self::SUCCESS;
        }

        Mail::to('abdallhelzayat194@gmail.com')
            ->send(new PublishPostMail($publishedCount));

        Log::info('Posts published successfully.', [
            'published_count' => $publishedCount,
            'completed_at' => now()->toDateTimeString(),
        ]);

        $this->info(
            "Published {$publishedCount} posts successfully."
        );

        return self::SUCCESS;
    }
}
