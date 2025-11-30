<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::all();
        return view('notices.index', compact('notices'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        Notice::create($request->only('name'));
        return back()->with('success', 'Notice added!');
    }

    public function update(Request $request, Notice $notice)
    {
        $request->validate(['name' => 'required|string']);
        $notice->update($request->only('name'));
        return back()->with('success', 'Notice updated!');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        return back()->with('success', 'Notice deleted!');
    }
}
