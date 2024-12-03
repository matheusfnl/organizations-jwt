<?php

namespace App\Http\Controllers;

use App\Models\{Organization, OrganizationUser};
use App\Models\User;
use Illuminate\Http\{Request, Response};

class OrganizationUserController extends Controller
{
    public function index(Request $request, Organization $organization)
    {
        $this->authorizeUser($organization, $request->user());

        return OrganizationUser::where('organization_id', $organization->id)->paginate(10);
    }

    public function store(Request $request, Organization $organization)
    {
        $this->authorizeUser($organization, $request->user());

        if ($request->role === 'owner') {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Organization can\'t have more than one owner');
        }

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|in:' . implode(',', OrganizationUser::TYPES),
        ]);

        $existingRole = OrganizationUser::where('organization_id', $organization->id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existingRole) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'User already has a role in this organization');
        }

        $organizationUser = OrganizationUser::create([
            ...$validatedData,
            'organization_id' => $organization->id,
        ]);

        return $organizationUser;
    }

    public function show(Request $request, Organization $organization, string $memberId)
    {
        $this->authorizeUser($organization, $request->user());

        $organizationUser = $this->findOrganizationMember($memberId, $organization);

        return $organizationUser;
    }

    public function update(Request $request, Organization $organization, string $memberId)
    {
        $this->authorizeUser($organization, $request->user());

        if ($request->role === 'owner') {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Organization can\'t have more than one owner');
        }

        $organizationUser = $this->findOrganizationMember($memberId, $organization);
        $organizationUser->update([
            ...$request->validate([
                'role' => 'required|string|in:' . implode(',', OrganizationUser::TYPES),
            ]),
        ]);

        return $organizationUser;
    }

    public function destroy(Request $request, Organization $organization, string $memberId)
    {
        $this->authorizeUser($organization, $request->user());

        $organizationUser = $this->findOrganizationMember($memberId, $organization);
        $organizationUser->delete();

        return response(status: Response::HTTP_NO_CONTENT);
    }

    private function findOrganizationMember(string $memberId, Organization $organization) {
        $organizationUser = OrganizationUser::where('organization_id', $organization->id)
            ->where('user_id', $memberId)
            ->first();

        if (! $organizationUser) {
            abort(Response::HTTP_NOT_FOUND, 'Member was not found');
        }

        return $organizationUser;
    }

    private function authorizeUser(Organization $organization, User $user): void
    {
        if ($organization->owner_id !== $user->id) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized');
        }
    }
}
