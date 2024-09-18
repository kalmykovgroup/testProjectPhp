<?php

 declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Update the authenticated user's API token.
     *
     * @param  Request  $request
     * @return array
     */
    public function test(Request $request)
    {
        return response()->json(Post::all());
    }
}
