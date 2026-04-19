<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PdfHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CredentialController extends Controller
{
    /**
     * Export all user credentials to PDF.
     */
    public function export()
    {
        // Fetch all users
        $users = User::orderBy('role')->orderBy('name')->get();
        
        // Get logo in Base64
        $logoBase64 = PdfHelper::getLogoBase64();

        // Load the view and pass data
        $pdf = Pdf::loadView('pdf.user_credentials', compact('users', 'logoBase64'));

        // Return PDF for download
        return $pdf->download('Dokumentasi_Akses_User_' . now()->format('Y-m-d') . '.pdf');
    }
}
