<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('nastavnik_id', Auth::id())->get();
        return view('tasks.index', compact('tasks'));
    }

    public function studentIndex()
    {
        // Dobijamo sve radove i provjeravamo je li student već prijavljen
        $tasks = Task::with(['applications' => function ($query) {
            $query->where('user_id', Auth::id());
        }])->get();

        return view('tasks.studentIndex', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'naziv_hr' => 'required|string',
            'naziv_en' => 'required|string',
            'zadatak' => 'required|string',
            'tip_studija' => 'required|in:strucni,preddiplomski,diplomski',
        ]);

        Task::create([
            'naziv' => $request->naziv_hr,
            'naziv_en' => $request->naziv_en,
            'zadatak' => $request->zadatak,
            'tip_studija' => $request->tip_studija,
            'nastavnik_id' => Auth::id(),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Rad je uspješno dodan!');
    }
    public function apply($taskId)
    {
        $task = Task::findOrFail($taskId);

        // Provjerava da li je student već prijavljen
        if ($task->applications()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('tasks.studentIndex')->with('error', 'Već ste prijavljeni na ovaj rad.');
        }

        // Ako nije, stvaramo novu prijavu
        $task->applications()->create([
            'user_id' => Auth::id(),
            'prihvaceno' => 0,  // inicijalno postavljamo na 0
        ]);

        return redirect()->route('tasks.studentIndex')->with('success', 'Uspješno ste se prijavili.');
    }

    public function showApplications($taskId)
    {
        // Dohvati rad s pripadajućim prijavama
        $task = Task::with('applications.user')->findOrFail($taskId);

        // Prikaz prijava za određeni rad
        return view('tasks.applications', compact('task'));
    }
}
