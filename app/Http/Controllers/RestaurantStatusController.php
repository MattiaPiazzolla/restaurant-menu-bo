<?php

namespace App\Http\Controllers;

use App\Models\RestaurantStatus;

class RestaurantStatusController extends Controller
{
    public function index()
    {
        $status = RestaurantStatus::firstOrCreate([], ['is_open' => false]);
        return view('restaurant-status.index', compact('status'));
    }

    public function toggle()
    {
        $status = RestaurantStatus::first();
        $status->update(['is_open' => !$status->is_open]);
        return back()->with('success', 'Status updated successfully');
    }

    public function getStatus()
    {
        $status = RestaurantStatus::first();
        return response()->json(['is_open' => $status->is_open]);
    }
}