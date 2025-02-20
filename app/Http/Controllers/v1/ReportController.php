<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
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

        $report->isReviewed = true;
        $report->reviewer_id = $request->user()->id;

        $report->update(
            [
            "isReviewed" => true,
            "reviewer_id" => $request->user()->id
            ]
        );

        return Response()->json('report is reviewd now');
    }
}
