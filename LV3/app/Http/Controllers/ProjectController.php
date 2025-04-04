<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'numeric',
            'completed_tasks' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'tasks' => $validated['tasks'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'leader_id' => Auth::id(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Projekt je uspješno kreiran!');
    }

    public function index()
    {

        $leaderProjects = Project::where('leader_id', auth()->user()->id)->get();

        $memberProjects = auth()->user()->projects;

        return view('projects.index', compact('leaderProjects', 'memberProjects'));
    }

    public function manageMembersForm()
    {
        $user = auth()->user();
        $projects = Project::where('leader_id', $user->id)->get();
        $users = User::where('id', '!=', $user->id)->get();

        return view('projects.manage-members', compact('projects', 'users'));
    }

    public function addMembers(Request $request, Project $project)
    {
        if (auth()->id() !== $project->leader_id) {
            abort(403);
        }

        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $existingMemberIds = $project->users()->pluck('users.id')->toArray();

        $newMembers = array_diff($request->users, $existingMemberIds);

        if (empty($newMembers)) {
            return redirect()->back()->with('error', 'Odabrani korisnici su već članovi projekta.');
        }

        $project->users()->attach($newMembers);

        return redirect()->route('dashboard')->with('success', 'Korisnici su uspješno dodani projektu.');
    }

    public function edit(Project $project)
    {
        $user = auth()->user();


        if ($user->id !== $project->leader_id && !$project->users->contains($user)) {
            abort(403);
        }

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $user = auth()->user();


        if ($user->id !== $project->leader_id && !$project->users->contains($user)) {
            abort(403);
        }

        if ($user->id === $project->leader_id) {

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'completed_tasks' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $project->update($request->all());
        } else {

            $request->validate([
                'completed_tasks' => 'nullable|string',
            ]);

            $project->update([
                'completed_tasks' => $request->completed_tasks,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Projekt uspješno ažuriran.');
    }
}
