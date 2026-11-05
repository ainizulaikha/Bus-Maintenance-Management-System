<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\BreakdownReport;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $busStats = null;
        $pendingRequests = 0;
        $routes = collect();

        // Admin & Operation homepage
        if ($user->role === 'Admin & Operation') {
            $busStats = [
                'total' => Bus::count(),
                'available' => Bus::where('status', 'Available')->count(),
                'standby' => Bus::where('status', 'Standby')->count(),
                'underMaintenance' => Bus::where('status', 'Under Maintenance')->count(),
            ];

            $pendingRequests = BreakdownReport::where('status', 'Pending')->count();
        }

        // Bus Driver homepage
        elseif ($user->role === 'Bus Driver') {
            $routes = Bus::select('route')
                ->selectRaw('COUNT(*) as bus_count')
                ->groupBy('route')
                ->orderBy('route')
                ->get();
        }

        return view('home', compact(
            'user',
            'busStats',
            'pendingRequests',
            'routes'
        ));
    }
}
