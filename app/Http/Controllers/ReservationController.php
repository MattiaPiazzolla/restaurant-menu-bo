<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::orderBy('date')
            ->orderBy('time')
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        return view('reservations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'name' => 'required|string|max:255',
            'party_size' => 'required|integer|min:1',
            'table_number' => 'required|integer|min:0',
            'note' => 'nullable|string',
        ], [
            'date.required' => 'La data è obbligatoria',
            'date.date' => 'La data non è valida',
            'time.required' => "L'ora è obbligatoria",
            'name.required' => 'Il nome è obbligatorio',
            'name.max' => 'Il nome non può superare i 255 caratteri',
            'party_size.required' => 'Il numero di ospiti è obbligatorio',
            'party_size.integer' => 'Il numero di ospiti deve essere un numero intero',
            'party_size.min' => 'Il numero di ospiti deve essere almeno 1',
            'table_number.required' => 'Il numero del tavolo è obbligatorio',
            'table_number.integer' => 'Il numero del tavolo deve essere un numero intero',
            'table_number.min' => 'Il numero del tavolo deve essere almeno 0',
        ]);

        Reservation::create($validated);

        return redirect()->route('reservations.index')
            ->with('success', 'Prenotazione creata con successo.');
    }

    public function edit(Reservation $reservation)
    {
        return view('reservations.edit', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'name' => 'required|string|max:255',
            'party_size' => 'required|integer|min:1',
            'table_number' => 'required|integer|min:0',
            'note' => 'nullable|string',
        ], [
            'date.required' => 'La data è obbligatoria',
            'date.date' => 'La data non è valida',
            'time.required' => "L'ora è obbligatoria",
            'name.required' => 'Il nome è obbligatorio',
            'name.max' => 'Il nome non può superare i 255 caratteri',
            'party_size.required' => 'Il numero di ospiti è obbligatorio',
            'party_size.integer' => 'Il numero di ospiti deve essere un numero intero',
            'party_size.min' => 'Il numero di ospiti deve essere almeno 1',
            'table_number.required' => 'Il numero del tavolo è obbligatorio',
            'table_number.integer' => 'Il numero del tavolo deve essere un numero intero',
            'table_number.min' => 'Il numero del tavolo deve essere almeno 0',
        ]);

        $reservation->update($validated);

        return redirect()->route('reservations.index')
            ->with('success', 'Prenotazione aggiornata con successo.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Prenotazione eliminata con successo.');
    }

    public function destroyPast()
    {
        Reservation::where('date', '<', today())->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Tutte le prenotazioni passate sono state eliminate con successo.');
    }

    public function toggleArrived(Reservation $reservation)
    {
        $reservation->update([
            'arrived' => !$reservation->arrived
        ]);

        return back()->with('success', 
            $reservation->arrived 
                ? 'Il cliente è arrivato!' 
                : 'Status arrivo aggiornato'
        );
    }
} 