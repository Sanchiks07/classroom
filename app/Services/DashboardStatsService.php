<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Comment;
use App\Models\Submission;
use App\Models\User;

class DashboardStatsService
{
    public static function forUser(User $user)
    {
        if ($user->role === 'admin') {
            return self::forAdmin();
        }

        if ($user->role === 'teacher') {
            return self::forTeacher($user);
        }

        if ($user->role === 'student' || !$user->role) {
            return self::forStudent($user);
        }

        abort(403, 'Unauthorized role');
    }

    private static function forStudent(User $user)
    {
        $totalAssignments = Assignment::count();
        $submitted = Submission::where('student_id', $user->id)->count();
        $pending = $totalAssignments - $submitted;
        $averageGrade = Submission::where('student_id', $user->id)->avg('grade');

        return [
            'Total assignments' => $totalAssignments,
            'Submitted' => $submitted,
            'Pending' => $pending,
            'Average grade' => $averageGrade ? round($averageGrade, 2) : 0,
        ];
    }

    private static function forTeacher(User $user)
    {
        $classes = Classroom::where('teacher_id', $user->id)->count();
        $assignments = Assignment::whereIn('classroom_id', Classroom::where('teacher_id', $user->id)->pluck('id'))->count();
        $submissionsToGrade = Submission::whereIn(
            'assignment_id',
            Assignment::whereIn(
                'classroom_id',
                Classroom::where('teacher_id', $user->id)->pluck('id')
            )->pluck('id')
        )->whereNull('grade')->count();
        $commentsWritten = Comment::where('user_id', $user->id)->count();

        return [
            'Classes' => $classes,
            'Assignments' => $assignments,
            'Submissions to grade' => $submissionsToGrade,
            'Comments written' => $commentsWritten,
        ];
    }

    private static function forAdmin()
    {
        $totalUsers = User::count();
        $students = User::where('role', 'student')->count();
        $teachers = User::where('role', 'teacher')->count();
        $classrooms = Classroom::count();

        return [
            'Total users' => $totalUsers,
            'Students' => $students,
            'Teachers' => $teachers,
            'Classrooms' => $classrooms,
        ];
    }
}
