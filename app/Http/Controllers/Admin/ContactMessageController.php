<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactMessageController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/ContactMessages/Index', [
            'messages' => ContactMessage::latest()->get(),
        ]);
    }

    public function update(Request $request, ContactMessage $contact_message)
    {
        $request->validate([
            'status' => 'required|in:pending,responded,archived',
        ]);

        $contact_message->update($request->only('status'));

        return back()->with('success', 'Message status updated.');
    }

    public function destroy(ContactMessage $contact_message)
    {
        $contact_message->delete();

        return back()->with('success', 'Message deleted.');
    }
}
