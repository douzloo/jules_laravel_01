<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\LandingPageVisit; // Import the model

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard with landing page visits.
     */
    public function index(): View
    {
        $visits = LandingPageVisit::orderBy('visited_at', 'desc')->paginate(15); // Paginate for better display

        return view('admin.dashboard', [
            'visits' => $visits,
        ]);
    }

    /**
     * Delete a landing page visit record.
     */
    public function destroy(Request $request, LandingPageVisit $visit): \Illuminate\Http\RedirectResponse
    {
        // Basic authorization: ensure only authenticated users can delete,
        // further checks like admin role could be added here via Gates/Policies.
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        try {
            $visit->delete();
            return redirect()->route('admin.dashboard')->with('success', 'Visit record deleted successfully.');
        } catch (\Exception $e) {
            logger()->error('Failed to delete visit record: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Failed to delete visit record.');
        }
    }
}
