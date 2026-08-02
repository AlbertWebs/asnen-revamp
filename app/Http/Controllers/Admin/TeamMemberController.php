<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Admin\Concerns\NormalizesNullableIds;
use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    use NormalizesNullableIds;

    public function index(): View
    {
        abort_unless(auth()->user()?->can('team_members.view'), 403);

        $members = TeamMember::query()->orderBy('sort_order')->paginate(20);

        return view('admin.team-members.index', compact('members'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('team_members.create'), 403);

        return view('admin.team-members.edit', ['member' => new TeamMember(['status' => PublishStatus::Draft])]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('team_members.create'), 403);

        $member = TeamMember::create($this->validateMember($request));

        return redirect()->route('admin.team-members.edit', $member)->with('success', 'Team member created.');
    }

    public function edit(TeamMember $teamMember): View
    {
        abort_unless(auth()->user()?->can('team_members.update'), 403);

        return view('admin.team-members.edit', ['member' => $teamMember]);
    }

    public function update(Request $request, TeamMember $teamMember): RedirectResponse
    {
        abort_unless(auth()->user()?->can('team_members.update'), 403);

        $teamMember->update($this->validateMember($request, $teamMember));

        return back()->with('success', 'Team member updated.');
    }

    public function destroy(TeamMember $teamMember): RedirectResponse
    {
        abort_unless(auth()->user()?->can('team_members.delete'), 403);

        $teamMember->delete();

        return redirect()->route('admin.team-members.index')->with('success', 'Team member deleted.');
    }

    public function publish(TeamMember $teamMember): RedirectResponse
    {
        abort_unless(auth()->user()?->can('team_members.publish'), 403);

        $teamMember->publish();

        return back()->with('success', 'Team member published.');
    }

    public function unpublish(TeamMember $teamMember): RedirectResponse
    {
        abort_unless(auth()->user()?->can('team_members.publish'), 403);

        $teamMember->unpublish();

        return back()->with('success', 'Team member unpublished.');
    }

    private function validateMember(Request $request, ?TeamMember $member = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:team_members,slug,'.($member?->id ?? 'NULL')],
            'title_role' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'email' => ['nullable', 'email', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:500'],
            'sort_order' => ['nullable', 'integer'],
            'is_board' => ['nullable', 'boolean'],
            'verification_status' => ['nullable', 'string'],
        ]);
    }
}
