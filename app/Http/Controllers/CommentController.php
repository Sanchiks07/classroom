<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Assignment;
use App\Models\Comment;
use App\Models\ActionLog;

class CommentController extends Controller
{
    public function store(Request $request, Assignment $assignment)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $comment = $assignment->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        // Action log
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'target_type' => 'Comment',
            'target_id' => $comment->id,
            'description' => 'Created a comment on assignment "' . $assignment->title . '": "' . $comment->body . '"',
        ]);

        return back();
    }

    public function update(Request $request, Comment $comment)
    {
        abort_if($comment->user_id !== auth()->id(), 403); // Checks if the authenticated user is the owner of the comment

        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        // Action log
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'target_type' => 'Comment',
            'target_id' => $comment->id,
            'description' => 'Updated a comment on assignment "' . $comment->assignment->title . '": "' . $comment->body . '"',
        ]);

        return back();
    }

    public function destroy(Comment $comment)
    {
        abort_if($comment->user_id !== auth()->id(), 403);

        $comment->delete();

        // Action log
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'target_type' => 'Comment',
            'target_id' => $comment->id,
            'description' => 'Deleted a comment on assignment "' . $comment->assignment->title . '"',
        ]);

        return back();
    }
}
