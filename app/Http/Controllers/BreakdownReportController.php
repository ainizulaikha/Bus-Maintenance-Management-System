<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\BreakdownReport;
use App\Models\User;
use Illuminate\Support\Str;

class BreakdownReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => ['required', 'string', 'size:6', 'regex:/^TR[0-9]{4}$/'],
            'report_date' => ['required', 'date'],
            'report_time' => ['required'],
            'place' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'pics' => ['nullable', 'array'],
            'pics.*' => ['image', 'max:5120'],
        ], [
            'bus_id.size' => 'Please enter a valid Bus ID containing exactly 6 characters.',
            'bus_id.regex' => 'The Bus ID must follow this format: TR0000.',
            'pics.*.image' => 'Please upload a valid image file (JPG, JPEG, PNG, etc.).',
            'pics.*.max' => 'The selected picture is too large. Each picture must not exceed 5 MB.',
            'pics.uploaded' => 'The picture could not be uploaded. Please check its file size and try again.',
        ]);

        $picturePaths = [];

        if ($request->hasFile('pics')) {
            foreach ($request->file('pics') as $picture) {
                $picturePaths[] = $picture->store('breakdown-pictures', 'public');
            }
        }

        $report = BreakdownReport::create([
            'report_id' => 'TEMP-' . Str::uuid(),
            'bus_id' => $validated['bus_id'],
            'user_id' => Auth::user()->user_id,
            'report_date' => $validated['report_date'],
            'report_time' => $validated['report_time'],
            'place' => $validated['place'],
            'description' => $validated['description'],
            'pics' => $picturePaths,
            'status' => 'Pending',
        ]);

        $report->update([
            'report_id' => 'RP' . str_pad($report->id, 4, '0', STR_PAD_LEFT),
        ]);

        return redirect('/home')->with([
            'success' => true,
            'report_id' => $report->report_id,
        ]);
    }

    public function showStatus(Request $request)
    {
        $validated = $request->validate([
            'report_id' => [
                'required',
                'string',
                'size:6',
                'regex:/^RP[0-9]{4}$/',
            ],
        ], [
            'report_id.size' => 'The Report ID must contain exactly 6 characters.',
            'report_id.regex' => 'Please enter a valid Report ID, for example RP0001.',
        ]);

        $report = BreakdownReport::where('report_id', strtoupper($validated['report_id']))
            ->where('user_id', Auth::user()->user_id)
            ->first();

        if (!$report) {
            return back()
                ->withInput()
                ->withErrors([
                    'report_id' => 'We could not find this report. Please check the Report ID and try again.',
                ]);
        }

        return view('driver-maintenance', compact('report'));
    }

    public function inbox()
    {
        $reports = BreakdownReport::where('user_id', Auth::user()->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('driver-inbox', compact('reports'));
    }

    public function adminRequests()
    {
        $reports = BreakdownReport::where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingCount = $reports->count();

        $mechanics = User::where('role', 'Mechanic')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin-request', compact(
            'reports',
            'pendingCount',
            'mechanics'
        ));
    }

    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'assigned_mechanic_id' => [
                'required',
                'string',
                'size:6',
                'regex:/^ME[0-9]{4}$/',
            ],
        ]);

        $mechanic = User::where('user_id', $validated['assigned_mechanic_id'])
            ->where('role', 'Mechanic')
            ->where('is_active', true)
            ->first();

        if (!$mechanic) {
            return back()->withErrors([
                'assigned_mechanic_id' => 'Please select a valid active mechanic.',
            ]);
        }

        $report = BreakdownReport::where('id', $id)
            ->where('status', 'Pending')
            ->firstOrFail();

        $report->update([
            'status' => 'Approved',
            'assigned_mechanic_id' => $mechanic->user_id,
            'approved_by' => Auth::user()->user_id,
            'approved_at' => now(),
        ]);

        return redirect('/admin-request')
            ->with('success', 'Maintenance request approved and mechanic assigned successfully.');
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $report = BreakdownReport::where('id', $id)
            ->where('status', 'Pending')
            ->firstOrFail();

        $report->update([
            'status' => 'Rejected',
            'approved_by' => Auth::user()->user_id,
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect('/admin-request')
            ->with('success', 'Maintenance request rejected successfully.');
    }

    public function adminMaintenance()
    {
        $reports = BreakdownReport::whereIn('status', [
            'Approved',
            'In Progress'
        ])->orderBy('created_at', 'desc')->get();

        return view('admin-maintenance', compact('reports'));
    }

    public function adminRecords()
    {
        $reports = BreakdownReport::whereIn('status', [
            'Completed',
            'Rejected'
        ])->orderBy('created_at', 'desc')->get();

        return view('admin-records', compact('reports'));
    }

    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'report_type' => ['required', 'in:maintenance,breakdown,bus_status'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];
        $reportType = $validated['report_type'];

        if ($reportType === 'bus_status') {
            $reports = \App\Models\Bus::all();
        } else {
            $query = BreakdownReport::whereBetween('report_date', [
                $startDate,
                $endDate
            ]);

            if ($reportType === 'maintenance') {
                $query->whereIn('status', [
                    'Approved',
                    'In Progress',
                    'Completed'
                ]);
            }

            $reports = $query->orderBy('report_date', 'desc')->get();
        }

        return view('admin-generate', compact(
            'reports',
            'reportType',
            'startDate',
            'endDate'
        ));
    }

    public function mechanicMaintenance()
    {
        $mechanicID = auth()->user()->user_id;

        $reports = BreakdownReport::where('assigned_mechanic_id', $mechanicID)
            ->whereIn('status', [
                'Approved',
                'In Progress',
                'Completed'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $newTasks = $reports->where('status', 'Approved');

        return view('mechanic-maintenance', compact(
            'reports',
            'newTasks'
        ));
    }

    public function mechanicToStart()
    {
        $mechanicID = auth()->user()->user_id;

        $reports = BreakdownReport::where('assigned_mechanic_id', $mechanicID)
            ->where('status', 'Approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mechanic-tostart', compact('reports'));
    }


    public function mechanicInProgress()
    {
        $mechanicID = auth()->user()->user_id;

        $reports = BreakdownReport::where('assigned_mechanic_id', $mechanicID)
            ->where('status', 'In Progress')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mechanic-inprogress', compact('reports'));
    }


    public function mechanicHistory()
    {
        $mechanicID = auth()->user()->user_id;

        $reports = BreakdownReport::where('assigned_mechanic_id', $mechanicID)
            ->where('status', 'Completed')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mechanic-history', compact('reports'));
    }

    public function updateMaintenanceStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Approved,In Progress,Completed'],
            'repair_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $mechanicID = auth()->user()->user_id;

        $report = BreakdownReport::where('report_id', $id)
            ->where('assigned_mechanic_id', $mechanicID)
            ->firstOrFail();

        $currentStatus = $report->status;
        $newStatus = $validated['status'];

        // Allow only the correct status progression.
        $validTransition =
            ($currentStatus === 'Approved' && $newStatus === 'In Progress') ||
            ($currentStatus === 'In Progress' && $newStatus === 'Completed');

        if (!$validTransition) {
            return back()->withErrors([
                'status' => 'Please select a valid new maintenance status.'
            ]);
        }

        $report->status = $newStatus;
        $report->repair_notes = $validated['repair_notes'] ?? null;
        $report->save();

        return back()->with('success', 'Maintenance task updated successfully!');
    }
}
