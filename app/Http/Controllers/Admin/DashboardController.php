<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Period;
use App\Models\Resenia;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $allPeriods = Period::orderBy('start_time', 'desc')->get();

        if ($request->filled('period_id')) {
            $selectedPeriod = Period::find($request->input('period_id'));
        } else {
            $selectedPeriod = Period::where('is_active', true)->first() ?? $allPeriods->first();
        }

        $stats = [
            'total_reviews' => 0,
            'average_rating' => 0,
            'reported_reviews' => 0,
            'hidden_reviews' => 0,
        ];

        if ($selectedPeriod) {
            $reviewsQuery = Resenia::where('period_id', $selectedPeriod->id);
            $stats['total_reviews'] = (clone $reviewsQuery)->count();
            $stats['average_rating'] = $stats['total_reviews'] > 0 ? round((clone $reviewsQuery)->avg('calificacion'), 1) : 0;
            $stats['reported_reviews'] = (clone $reviewsQuery)->where('denunciada', true)->count();
            $stats['hidden_reviews'] = (clone $reviewsQuery)->where('oculto', true)->count();
        }

        return view('admin.dashboard', compact('selectedPeriod', 'allPeriods', 'stats'));
    }
}
