<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;

class BusController extends Controller
{
    public function driverBusStatus()
    {
        $buses = Bus::orderBy('bus_id')->get();

        $availableCount = Bus::where('status', 'Available')->count();

        $standbyCount = Bus::where('status', 'Standby')->count();

        $maintenanceCount = Bus::where('status', 'Under Maintenance')->count();

        $totalBuses = Bus::count();

        return view('driver-busstatus', compact(
            'buses',
            'totalBuses',
            'availableCount',
            'standbyCount',
            'maintenanceCount'
        ));
    }

    public function adminBusStatus(Request $request)
    {
        $busQuery = Bus::query();

        // Search by Bus ID or Route
        if ($request->filled('search')) {
            $search = $request->search;

            $busQuery->where(function ($query) use ($search) {
                $query->where('bus_id', 'like', '%' . $search . '%')
                    ->orWhere('route', 'like', '%' . $search . '%');
            });
        }

        // Filter by Availability Status
        if ($request->filled('status')) {
            $busQuery->where('status', $request->status);
        }

        $buses = $busQuery->orderBy('bus_id')->get();

        // Summary counts always show all registered buses
        $availableCount = Bus::where('status', 'Available')->count();
        $standbyCount = Bus::where('status', 'Standby')->count();
        $maintenanceCount = Bus::where('status', 'Under Maintenance')->count();
        $totalBuses = Bus::count();

        return view('admin-busstatus', compact(
            'buses',
            'totalBuses',
            'availableCount',
            'standbyCount',
            'maintenanceCount'
        ));
    }

    public function updateBusStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Available,Standby,Under Maintenance'],
        ]);

        $bus = Bus::findOrFail($id);

        $bus->update([
            'status' => $validated['status'],
        ]);

        return redirect('/admin-busstatus')
            ->with('success', 'Bus availability updated successfully.');
    }

    public function storeBus(Request $request)
    {
        $validated = $request->validate([
            'route' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:Available,Standby,Under Maintenance'],
        ]);

        // Find the highest existing Bus ID number
        $lastBusNumber = Bus::where('bus_id', 'like', 'TR%')
            ->get()
            ->map(fn($bus) => (int) substr($bus->bus_id, 2))
            ->max() ?? 0;

        // Generate the next Bus ID
        $newBusId = 'TR' . str_pad($lastBusNumber + 1, 4, '0', STR_PAD_LEFT);

        // Save the new bus
        Bus::create([
            'bus_id' => $newBusId,
            'route' => $validated['route'],
            'status' => $validated['status'],
        ]);

        return redirect('/admin-busstatus')
            ->with('success', 'New bus ' . $newBusId . ' added successfully!');
    }

    public function updateBusRoute(Request $request, $id)
    {
        $validated = $request->validate([
            'route' => ['required', 'string', 'max:255'],
        ]);

        $bus = Bus::findOrFail($id);

        $bus->update([
            'route' => $validated['route'],
        ]);

        return redirect('/admin-busstatus')
            ->with('success', 'Bus route updated successfully!');
    }

    public function deleteBus($id)
    {
        $bus = Bus::findOrFail($id);

        $bus->delete();

        return redirect('/admin-busstatus')
            ->with('success', 'Bus ' . $bus->bus_id . ' deleted successfully!');
    }
}
