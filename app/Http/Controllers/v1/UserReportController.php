<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\UserReport;
use Illuminate\Http\Response;

class UserReportController extends Controller
{
    public function index()
    {
        $reports = Report::query()->get()->all();

        return Response()->json($reports);
    }


    public function show(Request $request, string $reportID)
    {
        $report = Report::query()->find($reportID);

        return Response()->json($report);
    }

    public function review(Request $request, string $reportID)
    {
        $report = Report::query()->find($reportID);

        if (!$report) {
            abort(404, 'report not found');
        }

        if ($report->isReviewed) {
            abort(400, 'Report has been reviewd');
        }

        $report->update(
            [
                "isReviewed" => true,
                "reviewer_id" => $request->user()->id
            ]
        );

        return Response()->json('report is reviewd now');
    }
}
