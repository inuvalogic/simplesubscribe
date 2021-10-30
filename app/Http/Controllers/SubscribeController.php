<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Subscriber;

class SubscribeController extends Controller
{
    public function doSubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:subscribers',
            'website_id' => 'required',
        ],[
            'email.unique' => 'Email already subscribed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages(),
            ], 422);
        }

        $subsciber = new Subscriber;
        $subsciber->name = $request->name;
        $subsciber->email = $request->email;
        $subsciber->website_id = $request->website_id;
        $subsciber->state = 1;
        $subsciber->save();

        return response()->json([
            'message' => 'Successfully subscribed!',
        ], 201);
    }
}
