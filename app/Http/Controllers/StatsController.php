<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function stats()
    {
        $stats = Cache::remember('stats', 60, function () {
            $userCount = User::count();
            $postCount = Post::count();
            $usersWithZeroPosts = User::doesntHave('posts')->count();

            return [
                'total_users' => $userCount,
                'total_posts' => $postCount,
                'users_with_zero_posts' => $usersWithZeroPosts,
            ];
        });
        return response()->json($stats);
    }
}

