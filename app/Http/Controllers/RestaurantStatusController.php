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
        
        return redirect()->route('schedules.index')
            ->with('success', 'Stato del ristorante aggiornato con successo');
    }

    public function getStatus()
    {
        $status = RestaurantStatus::first();
        return response()->json(['is_open' => $status->is_open]);
    }
}