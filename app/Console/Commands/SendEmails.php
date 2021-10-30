<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use App\Models\Website;
use App\Models\Post;
use App\Models\PostSubscriber;
use App\Mail\PostMail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send {postid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to subscriber';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $postid = $this->argument('postid');
        
        $post = Post::find($postid);
        $website = Website::find($post->website_id);

        $all_subs = $website->subscribers()->where('state', '=', 1)->get();

        foreach ($all_subs as $subs) {
            $check = PostSubscriber::where('post_id', '=', $post->id)
                ->where('subscriber_id', '=', $subs->id);

            if (!$check->exists()) {
                Mail::to($subs->email)->queue(new PostMail($post));

                $postsub = new PostSubscriber;
                $postsub->subscriber_id = $subs->id;
                $postsub->post_id = $post->id;
                $postsub->save();
            }
        }

        $this->info("Sending email successfully");
    }
}
