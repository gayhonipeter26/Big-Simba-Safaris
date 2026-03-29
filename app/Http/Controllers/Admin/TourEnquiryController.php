<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ExpeditionApproved;
use App\Models\TourEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class TourEnquiryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Enquiries/Index', [
            'enquiries' => TourEnquiry::with(['tour', 'fleet', 'user'])->latest()->paginate(15),
        ]);
    }

    public function update(Request $request, TourEnquiry $tourEnquiry)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,approved,rejected,completed',
        ]);

        $originalStatus = $tourEnquiry->status;
        $tourEnquiry->update($validated);

        if ($validated['status'] === 'approved' && $originalStatus !== 'approved') {
            Mail::to($tourEnquiry->email)->send(new ExpeditionApproved($tourEnquiry));
        }

        return redirect()->back()->with('success', 'Expedition status updated.');
    }

    public function destroy(TourEnquiry $tourEnquiry)
    {
        $tourEnquiry->delete();

        return redirect()->back()->with('success', 'Expedition completely purged.');
    }
}
