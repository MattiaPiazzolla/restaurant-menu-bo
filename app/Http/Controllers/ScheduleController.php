<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\RestaurantStatus;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")->get();
        $status = RestaurantStatus::firstOrCreate([], ['is_open' => false]);
        return view('schedules.index', compact('schedules', 'status'));
    }

    public function update(Request $request)
    {
        foreach ($request->schedules as $id => $data) {
            $schedule = Schedule::findOrFail($id);
            
            // Ensure times are in HH:mm format
            $schedule->update([
                'is_open' => isset($data['is_open']),
                'lunch_opening' => $data['lunch_opening'] ? $data['lunch_opening'] . ':00' : null,
                'lunch_closing' => $data['lunch_closing'] ? $data['lunch_closing'] . ':00' : null,
                'dinner_opening' => $data['dinner_opening'] ? $data['dinner_opening'] . ':00' : null,
                'dinner_closing' => $data['dinner_closing'] ? $data['dinner_closing'] . ':00' : null,
            ]);
        }

        return redirect()->route('schedules.index')
            ->with('success', 'Orari aggiornati con successo!');
    }

    public function apiIndex()
    {
        $schedules = Schedule::orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")->get();
        $status = RestaurantStatus::first();
        return response()->json([
            'schedules' => $schedules,
            'status' => $status
        ]);
    }

    public function apiUpdate(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'is_open' => 'required|boolean',
            'lunch_opening' => 'nullable|date_format:H:i',
            'lunch_closing' => 'nullable|date_format:H:i',
            'dinner_opening' => 'nullable|date_format:H:i',
            'dinner_closing' => 'nullable|date_format:H:i',
        ]);

        $schedule->update($validated);

        return response()->json($schedule);
    }
}