<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use App\Models\LandingPageVisit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail; // Import Mail facade
use App\Mail\LandingPageVisited; // Import Mailable

class LandingPageController extends Controller
{
    /**
     * Show the landing page and log the visit.
     */
    public function index(Request $request): View
    {
        $visitData = [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'visited_at' => Carbon::now(),
        ];

        // Log the visit to DB
        try {
            $landingPageVisit = LandingPageVisit::create($visitData);

            // Send email notification
            try {
                $notificationEmail = config('mail.admin_notification_email', config('mail.from.address'));
                if ($notificationEmail) {
                     Mail::to($notificationEmail)->send(new LandingPageVisited($landingPageVisit->toArray()));
                } else {
                    logger()->warning('Admin notification email not configured. Skipping email notification.');
                }
            } catch (\Exception $e) {
                logger()->error('Failed to send landing page visit notification email: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            // Log error if visit saving fails, but don't break page load
            logger()->error('Failed to save landing page visit: ' . $e->getMessage());
        }

        $pdfDirectory = public_path('pdf');
        $pdfFiles = [];
        $defaultPdf = null;

        if (File::isDirectory($pdfDirectory)) {
            $files = File::files($pdfDirectory);
            foreach ($files as $file) {
                if (strtolower($file->getExtension()) === 'pdf') {
                    $pdfFiles[] = $file->getFilename();
                }
            }
            if (!empty($pdfFiles)) {
                $defaultPdf = $pdfFiles[0]; // Set the first PDF as default
            }
        }

        return view('landing', [
            'pdfFiles' => $pdfFiles,
            'defaultPdf' => $defaultPdf,
            'pdfDirectoryExists' => File::isDirectory($pdfDirectory)
        ]);
    }
}
