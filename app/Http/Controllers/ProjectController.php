<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreRequest;
use App\Http\Requests\Project\UpdateRequest;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::where('organization_id', auth()->payload()->get('organization_id'))->paginate(10);
    }

    public function store(StoreRequest $request)
    {
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'organization_id' => auth()->payload()->get('organization_id'),
        ]);

        return $project;
    }

    public function show(Project $project)
    {
        $this->authorizeOrganization($project);

        return $project;
    }

    public function update(UpdateRequest $request, Project $project)
    {
        $this->authorizeOrganization($project);

        $project->update($request->only('name', 'description'));

        return $project;
    }

    public function destroy(Project $project)
    {
        $this->authorizeOrganization($project);
        $project->delete();

        return response(status: Response::HTTP_NO_CONTENT);
    }

    private function authorizeOrganization(Project $project) {
        if ($project->organization_id !== auth()->payload()->get('organization_id')) {
            abort(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }
    }
}
