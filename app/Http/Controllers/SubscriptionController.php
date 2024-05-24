<?php

namespace App\Http\Controllers;

use App\Models\AppUser;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function getSubscriptionList(): Collection
    {
        return Subscription::all();
    }

    public function boundSubscription(Request $request)
    {
        $body = $request->all();
        $user = AppUser::query()->find($body['user_id']);

        $user->subscription_id = $request['subscription_id'];
        $user->club_id = $request['club_id'];

        $user->save();
    }

    public function getSubscription()
    {
        $subscriptionId = AppUser::query()->find($_GET['user_id'], 'subscription_id');

        return Subscription::query()->find($subscriptionId);
    }
}
