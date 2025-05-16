<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function report_post(Request $request, string $post_id)
    {
        $request->validate([
            'body' => 'required|string|max:255',
        ]);

        $report = new Report();
        $report->post_id = $request['post_id'];
        $report->report_text = $request['body'];
        $report->user_id = $request->user()->id;
        $report->isReviewed = false;

        $report->save();

        return Response()->json('Report has been submitted');
    }

    public function index(Request $request)
    {
        $isReviewed = $request['isReviewed'];

        if ($isReviewed === 'false') {
            $reports = Report::query()->get()->where('isReviewed', 0);
            return Response()->json($reports);
        } elseif ($isReviewed === 'true') {
            $reports = Report::query()->get()->where('isReviewed', 1);
            return Response()->json($reports);
        }

        $reports = Report::query()->get()->all();

        return Response()->json($reports);
    }

    public function show(Request $request, string $report_id)
    {
        $report = Report::query()->find($report_id);

        return Response()->json($report);
    }

    public function review(Request $request, string $report_id)
    {
        $request->validate([
            'isValid' => 'nullable|boolean',
        ]);

        $isValid = $request['isValid'];
        $report = Report::query()->find($report_id);

        if (!$report) {
            abort(404, 'report not found');
        }
        if ($report->isReviewed) {
            abort(400, 'Report has been reviewed');
        }

        $report->isReviewed = true;
        if ($isValid) $report->isValid = $isValid;
        $report->reviewer_id = $request->user()->id;

        $report->save();

        return Response()->json('report is reviewed now');
    }

    // TODO: look if it will be ever used again
    public function takeAction(Request $request, string $report_id)
    {
        $action_id = $request['action'];

        $report = Report::query()->find($report_id);

        if (!$report) {
            abort(404, 'report not found');
        }
        if ($report->isReviewed) {
            abort(400, 'Report has been reviewed');
        }

        $report->update(
            [
                "action" => $action_id,
            ]
        );

        return Response()->json('action has been took on reports');
    }
}
