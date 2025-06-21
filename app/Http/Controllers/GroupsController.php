<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Models\Notes;
use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupsController extends Controller
{
    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'entry_code' => 'required|string|max:255|unique:groups,entry_code',
        ]);

        $group = Groups::create([
            'name' => $request->name,
            'entry_code' => $request->entry_code,
            'leader' => Auth::id(),
        ]);

        $group->members()->attach(Auth::id());

        return response()->json(['message' => 'Group created', 'group' => $group]);
    }

    public function joinGroup(Request $request)
    {
        $request->validate([
            'entry_code' => 'required|string',
        ]);

        $group = null;
        if ($request->has('group_id')) {
            $group = Groups::where('id', $request->group_id)
                ->where('entry_code', $request->entry_code)
                ->first();
        } elseif ($request->has('name')) {
            $group = Groups::where('name', $request->name)
                ->where('entry_code', $request->entry_code)
                ->first();
        }

        if (!$group) {
            return response()->json(['message' => 'Group not found or entry code incorrect'], 404);
        }

        $group->members()->syncWithoutDetaching([Auth::id()]);

        return response()->json(['message' => 'Joined group', 'group' => $group]);
    }

    public function editGroup(Request $request, $id)
    {
        $group = Groups::findOrFail($id);

        if ($group->leader !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'entry_code' => 'sometimes|string|max:255|unique:groups,entry_code,' . $group->id,
            'kick_member_id' => 'sometimes|integer|exists:users,id',
        ]);

        if ($request->has('name')) {
            $group->name = $request->name;
        }
        if ($request->has('entry_code')) {
            $group->entry_code = $request->entry_code;
        }
        $group->save();

        if ($request->has('kick_member_id')) {
            $group->members()->detach($request->kick_member_id);
        }

        return response()->json(['message' => 'Group updated', 'group' => $group]);
    }

    public function deleteGroup($id)
    {
        $group = Groups::findOrFail($id);

        if ($group->leader !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Notes::where('group_id', $group->id)->delete();
        Tasks::where('group_id', $group->id)->delete();

        $group->members()->detach();

        $group->delete();

        return response()->json(['message' => 'Group and related notes/tasks deleted']);
    }

    public function leaveGroup($id)
    {
        $group = Groups::findOrFail($id);

        if ($group->leader === Auth::id()) {
            return response()->json(['message' => 'Leader cannot leave the group. Please delete the group or transfer leadership.'], 403);
        }

        $group->members()->detach(Auth::id());

        return response()->json(['message' => 'Left the group']);
    }
}
