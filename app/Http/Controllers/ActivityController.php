<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function __invoke()
    {
        $activities = Activity::with('causer')->latest()->paginate();

        return view('activities.index', compact('activities'));
    }
}