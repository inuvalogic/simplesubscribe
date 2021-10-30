<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use App\Models\Website;
use App\Models\Post;
use App\Models\PostSubscriber;
use App\Mail\PostMail;

class PostController extends Controller
{
    public function createPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'website_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages(),
            ], 422);
        }

        $post = new Post;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->website_id = $request->website_id;
        $post->state = $request->state ?? 1; // set default auto published if not set
        $post->save();

        if ($post->state == 1){
            $website = Website::find($post->website_id);

            $all_subs = $website->subscribers()->get();

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
        }

        return response()->json([
            'message' => 'Post created',
        ], 201);
    }
}
