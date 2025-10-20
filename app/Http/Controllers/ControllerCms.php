<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ControllerCms extends Controller
{
    //
    public function login()
    {
        return view('cms.page-login');
    }
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }

    public function pageabout()
    {
        return view('cms.page-about');
    }
    public function pageinsight()
    {
        return view('cms.page-insight');
    }
    public function indexinsight()
    {
        return view('cms.index-insight');
    }
    public function addinsight()
    {
        return view('cms.add-insight');
    }
    public function editinsight($locale, $id)
    {
        return view('cms.edit-insight', compact('id'));
    }
    public function previewinsight($locale, $id)
    {
        return view('cms.preview-insight', compact('id'));
    }

    public function pageliteracy()
    {
        return view('cms.page-literacy');
    }

    public function indexliteracy()
    {
        return view('cms.index-literacy');
    }
    public function addliteracy()
    {
        return view('cms.add-literacy');
    }
    public function editliteracy($locale, $id)
    {
        return view('cms.edit-literacy', compact('id'));
    }
    public function previewliteracy($locale, $id)
    {
        return view('cms.preview-literacy', compact('id'));
    }

    public function indexagenda()
    {
        return view('cms.index-agenda');
    }
    public function addagenda()
    {
        return view('cms.add-agenda');
    }
    public function editagenda($locale, $id)
    {
        return view('cms.edit-agenda', compact('id'));
    }
    public function previewagenda($locale, $id)
    {
        return view('cms.preview-agenda', compact('id'));
    }
    public function pageagenda()
    {
        return view('cms.page-agenda');
    }
    public function pageresource()
    {
        return view('cms.page-resource');
    }

    public function indexresource()
    {
        return view('cms.index-resource');
    }
    public function addresource()
    {
        return view('cms.add-resource');
    }
    public function editresource($locale, $id)
    {
        return view('cms.edit-resource', compact('id'));
    }
    public function previewresource($locale, $id)
    {
        return view('cms.preview-resource', compact('id'));
    }
}
