<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    // Prikaz svih radova i prijava
    public function index()
    {
        $user = Auth::user();

        // Dohvati sve radove
        $tasks = Task::with('teacher')->get();

        // Dohvati radove na koje je student već prijavljen
        $appliedTaskIds = $user->applications->pluck('task_id')->toArray();

        return view('applications.index', compact('tasks', 'appliedTaskIds'));
    }

    public function apply(Task $task)
    {
        $user = Auth::user();

        // Provjera postoji li već prijava
        if (!$user->applications()->where('task_id', $task->id)->exists()) {
            $user->applications()->create([
                'task_id' => $task->id,
                'prihvaceno' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Prijava uspješna!');
    }

    public function acceptApplication($applicationId)
    {
        $application = Application::findOrFail($applicationId);

        // Provjeri da li je korisnik nastavnik koji ima pristup tom zadatku
        if (Auth::id() != $application->task->nastavnik_id) {
            abort(403, 'Nemate prava za ovu akciju.');
        }

        $application->update(['prihvaceno' => 1]);

        return redirect()->route('tasks.applications', $application->task_id)
            ->with('success', 'Prijava je prihvaćena!');
    }
}
