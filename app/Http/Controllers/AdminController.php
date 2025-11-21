<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Simple password protection
    public function login(Request $request)
    {
        // if ($request->method() === 'POST') {
        //     if ($request->input('password') === env('ADMIN_PASSWORD', 'letters2santa2024')) {
        //         session(['admin_logged_in' => true]);
        //         return redirect()->route('admin.dashboard');
        //     }
        //     return back()->with('error', 'Incorrect password');
        // }

        return view('admin.login');
    }

    public function logout()
    {

        Auth::logout();

        // Invalidate & regenerate session for security
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login')->with('status', 'Logged out successfully!');
    }

    public function dashboard(Request $request)
    {
        // if (!session('admin_logged_in')) {
        //     return redirect()->route('admin.login');
        // }

        $query = Letter::query()->orderBy('created_at', 'desc');

        // Filter by payment status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('payment_status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('child_name', 'like', "%{$search}%")
                  ->orWhere('parent_email', 'like', "%{$search}%")
                  ->orWhere('parent_mobile', 'like', "%{$search}%");
            });
        }

        $letters = $query->paginate(20);

        // Stats
        $stats = [
            'total' => Letter::count(),
            'completed' => Letter::where('payment_status', 'completed')->count(),
            'pending' => Letter::where('payment_status', 'pending')->count(),
            'failed' => Letter::where('payment_status', 'failed')->count(),
            'total_revenue' => Letter::where('payment_status', 'completed')->sum('amount'),
            'with_photos' => Letter::whereNotNull('child_photo')->count(),
        ];

        return view('admin.dashboard', compact('letters', 'stats'));
    }

    public function view($id)
    {

        $letter = Letter::findOrFail($id);

        // Get S3 URL for photo if exists
        $photoUrl = null;
        if ($letter->child_photo) {
            $photoUrl = Storage::disk('s3')->url($letter->child_photo);
        }

        return view('admin.view', compact('letter', 'photoUrl'));
    }

    public function deletePhoto($id)
    {


        $letter = Letter::findOrFail($id);

        if ($letter->child_photo) {
            // Delete from S3
            Storage::disk('s3')->delete($letter->child_photo);

            // Update database
            $letter->update(['child_photo' => null]);
        }

        return back()->with('success', 'Photo deleted successfully');
    }
}
