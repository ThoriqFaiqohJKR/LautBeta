<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('index');
    }

    public function analysis()
    {
        return view('page-analysis');
    }

    public function detailanalysis($id)
    {
        return view('page-analysis-detail', compact('id'));
    }
    public function detailngopini($id)
    {
        return view('page-ngopini-detail', compact('id'));
    }

    public function detailinsight($id)
    {
        return view('page-insight-detail', compact('id'));
    }

    public function feature()
    {
        return view('page-feature');
    }
    public function detailfeature($id)
    {
        return view('page-feature-detail', compact('id'));
    }

    public function about()
    {
        return view('about');
    }

    public function grafik()
    {
        return view('page-grafik');
    }

    public function journal()
    {
        return view('page-journal');
    }

    public function report()
    {
        return view('page-report');
    }
    public function detailreport($id)
    {
        return view('page-report-detail', compact('id'));
    }

    public function event()
    {
        return view('page-event');
    }

    public function detailevent($id)
    {
        return view('page-event-detail', compact('id'));
    }


    public function activity()
    {
        return view('page-activity');
    }

    public function detailactivity($id)
    {
        return view('page-activity-detail', compact('id'));
    }

    public function reportresource()
    {
        return view('page-report-resource');
    }
    public function detailreportresource($id)
    {
        return view('page-report-resource-detail', compact('id'));
    }

    public function detailagenda($id)
    {
        return view('page-agenda-detail', compact('id'));
    }

    public function database()
    {
        return view('page-database');
    }

    public function gallery()
    {
        return view('page-gallery');
    }

    public function previewjurnal($id)
    {
        return view('journal-preview', compact('id'));
    }


    public function news()
    {
        return view('news');
    }
}
