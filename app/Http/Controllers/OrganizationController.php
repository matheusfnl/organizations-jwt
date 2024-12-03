<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\{Request, Response};

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        return Organization::where('owner_id', $request->user()->id)->paginate(10);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $organization = Organization::create([
            ...$request->validate(['name' => 'required|string|max:255']),
            'owner_id' => $user->id,
        ]);

        $organization->users()->attach($user->id, ['role' => 'owner']);

        return $organization;
    }

    public function show(Request $request, Organization $organization)
    {
        $this->authorizeUser($organization, $request->user());

        return $organization;
    }

    public function update(Request $request, Organization $organization)
    {
        $this->authorizeUser($organization, $request->user());

        $organization->update([...$request->validate([
            'name' => 'required|string|max:255'
        ])]);

        return $organization;
    }

    public function destroy(Request $request, Organization $organization)
    {
        $this->authorizeUser($organization, $request->user());

        $organization->delete();

        return response(status: Response::HTTP_NO_CONTENT);
    }

    private function authorizeUser(Organization $organization, User $user): void
    {
        if ($organization->owner_id !== $user->id) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized');
        }
    }
}
