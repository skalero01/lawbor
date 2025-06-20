<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Document;
use App\Models\User;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $currentDate = now();

        $totalDocuments = Document::count();
        $completedDocuments = Document::whereNotNull('analysis_completed_at')->count();
        $processingDocuments = Document::whereNull('analysis_completed_at')
            ->whereNotNull('ocr_completed_at')
            ->count();
        $errorDocuments = Document::whereNotNull('error')->count();

        $recentActivity = Document::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $totalUsers = User::count();

        return view('livewire.admin.dashboard', compact(
            'user',
            'currentDate',
            'totalDocuments',
            'completedDocuments',
            'processingDocuments',
            'errorDocuments',
            'recentActivity',
            'totalUsers'
        ));
    }
}
