<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('projects.index', [
            'projects' => $user->projects,
        ]);
    }

    public function create(): View
    {
        return view('projects.create');
    }

    public function show(Project $project): View
    {
        $this->authorize('view', $project);

        return view('projects.show', [
            'project' => $project,
        ]);
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $values = $request->validated();

        $request->user()->projects()->create(
            array_merge($values, [
                'book_id' => Str::uuid()->getUrn(),
                'book_webtoon_style' => $request->boolean('book_webtoon_style'),
            ]),
        );

        return redirect()->route('projects.index')
            ->with('status', 'project-created');
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        return view('projects.edit', [
            'project' => $project,
        ]);
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $values = $request->validated();

        $project->update(array_merge(
            $values,
            ['book_webtoon_style' => $request->boolean('book_webtoon_style')],
        ));

        return redirect()->route('projects.index')
            ->with('status', 'project-updated');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('destroy', $project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('status', 'project-deleted');
    }
}
