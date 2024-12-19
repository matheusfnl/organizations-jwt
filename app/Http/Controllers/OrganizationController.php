<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organization\{OrganizationRequest, ChangeOrganizationRequest};
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\{Request, Response};

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        return Organization::where('owner_id', auth()->user()->id)->paginate(10);
    }

    public function store(OrganizationRequest $request)
    {
        $user = auth()->user();
        $organization = Organization::create([
            'name' => $request->name,
            'owner_id' => $user->id,
        ]);

        $organization->users()->attach($user->id, ['role' => 'owner']);

        return $organization;
    }

    public function change(ChangeOrganizationRequest $request)
    {
        $user = auth()->user();

        if ($request->organization_id === $user->organization_id) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Cannot change to the same organization');
        }

        $organization = Organization::where('owner_id', $user->id)
            ->where('id', $request->organization_id)
            ->first();

        if (!$organization) {
            abort(Response::HTTP_NOT_FOUND, 'Organization not found');
        }

        $token = auth()->claims(['organization_id' => $organization->id])->fromUser($user);

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function show(Request $request, Organization $organization)
    {
        $this->authorizeUser($organization, auth()->user());

        return $organization;
    }

    public function update(OrganizationRequest $request, Organization $organization)
    {
        $this->authorizeUser($organization, auth()->user());

        $organization->update(['name' => $request->name]);

        return $organization;
    }

    public function destroy(Request $request, Organization $organization)
    {
        $this->authorizeUser($organization, auth()->user());

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
