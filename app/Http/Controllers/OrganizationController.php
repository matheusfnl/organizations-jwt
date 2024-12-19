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
        return Organization::where('owner_id', auth()->id())->paginate(10);
    }

    public function store(OrganizationRequest $request)
    {
        $userId = auth()->id();
        $organization = Organization::create([
            'name' => $request->name,
            'owner_id' => $userId,
        ]);

        $organization->users()->attach($userId, ['role' => 'owner']);

        return $organization;
    }

    public function change(ChangeOrganizationRequest $request)
    {
        $user = auth()->user();

        if ($request->organization_id === auth()->payload()->get('organization_id')) {
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
        return $organization;
    }

    public function update(OrganizationRequest $request, Organization $organization)
    {
        $organization->update(['name' => $request->name]);

        return $organization;
    }

    public function destroy(Request $request, Organization $organization)
    {
        $organization->delete();

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
