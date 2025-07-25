<?php

namespace Modules\Course\app\Http\Controllers;

use App\Models\Quiz;
use App\Models\Course;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use App\Models\CourseChapter;
use App\Models\CourseChapterItem;
use App\Models\CourseChapterLesson;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Session\Session;
use App\Http\Requests\Frontend\QuizLessonCreateRequest;
use App\Models\CoursSession;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Modules\Course\app\Http\Requests\ChapterLessonRequest;
use Modules\Order\app\Models\Enrollment;
use App\Traits\MailSenderTrait;

class CourseContentController extends Controller
{
    function chapterStore(Request $request, string $courseId): RedirectResponse
    {

        $request->validate([
            'title' => ['required', 'max:255'],
        ], [
            'title.required' => __('Title is required'),
            'title.max' => __('Title is too long'),
        ]);

        $chapter = new CourseChapter();
        $chapter->title = $request->title;
        $chapter->course_id = $courseId;
        $chapter->session_id = $request->session_id ?? null; // Optional session ID
        $chapter->instructor_id = Course::find($courseId)->instructor_id;
        $chapter->status = 'active';
        $chapter->order = CourseChapter::where('course_id', $courseId)->max('order') + 1;
        $chapter->save();

        return redirect()->back()->with(['messege' => __('Chapter created successfully'), 'alert-type' => 'success']);
    }

    function chapterEdit(string $chapterId)
    {
        $chapter = CourseChapter::find($chapterId);
        return view('course::course.partials.edit-section-modal', compact('chapter'))->render();
    }

    function chapterUpdate(Request $request, string $chapterId)
    {
        checkAdminHasPermissionAndThrowException('course.management');
        $chapter = CourseChapter::findOrFail($chapterId);
        $chapter->title = $request->title;
        $chapter->save();
        return redirect()->back()->with(['messege' => __('Updated successfully'), 'alert-type' => 'success']);
    }

    function chapterDestroy(string $chapterId)
    {
        checkAdminHasPermissionAndThrowException('course.management');
        $chapter = CourseChapter::findOrFail($chapterId);
        $chapterItems = CourseChapterItem::where('chapter_id', $chapterId)->get();
        $lessonFiles = CourseChapterLesson::whereIn('chapter_item_id', $chapterItems->pluck('id'))->get();
        $quizIds = Quiz::whereIn('chapter_item_id', $chapterItems->pluck('id'))->pluck('id');
        $questionIds = QuizQuestion::whereIn('quiz_id', $quizIds)->pluck('id');

        // delete quizzes, questions, answers and lesson files
        QuizQuestion::whereIn('id', $questionIds)->delete();
        Quiz::whereIn('id', $quizIds)->delete();
        CourseChapterLesson::whereIn('id', $lessonFiles->pluck('id'))->delete();
        foreach ($lessonFiles as $lesson) {
            if (File::exists(asset($lesson->file_path))) File::delete(asset($lesson->file_path));
        }

        // delete chapter items and chapter
        CourseChapterItem::whereIn('id', $chapterItems->pluck('id'))->delete();
        $chapter->delete();

        return response()->json(['status' => 'success', 'message' => __('Question deleted successfully')]);
    }

    function chapterSorting(string $courseId, Request $request)
    {

        if($request->has('session_id')) {
            $chapters = CourseChapter::where('course_id', $courseId)->where('session_id',$request->session_id)->orderBy('order', 'ASC')->get();
        } else {
            $chapters = CourseChapter::where('course_id', $courseId)->orderBy('order', 'ASC')->get();
        }

        return view('course::course.partials.chapter-sorting-index', compact('chapters', 'courseId'))->render();
    }

    function chapterSortingStore(Request $request, string $courseId)
    {
        $newOrder = $request->chapter_ids;

        foreach ($newOrder as $key => $value) {
            $chapter = CourseChapter::where('course_id', $courseId)->find($value);
            $chapter->order = $key + 1;
            $chapter->save();
        }

        return redirect()->back()->with(['messege' => __('Updated successfully'), 'alert-type' => 'success']);
    }

    function lessonCreate(Request $request)
    {
        $courseId = $request->courseId;
        $chapterId = $request->chapterId;
        $chapters = CourseChapter::where('course_id', $courseId)->get();
        $type = $request->type;
        if ($request->type == 'lesson') {
            return view('course::course.partials.lesson-create-modal', [
                'courseId' => $courseId,
                'chapterId' => $chapterId,
                'chapters' => $chapters,
                'type' => $type
            ])->render();
        }elseif ($request->type == 'document') {
            return view('course::course.partials.document-create-modal', [
                'courseId' => $courseId,
                'chapterId' => $chapterId,
                'chapters' => $chapters,
                'type' => $type
            ])->render();
        } elseif ($request->type == 'quiz') {
            return view('course::course.partials.quiz-create-modal', [
                'courseId' => $courseId,
                'chapterId' => $chapterId,
                'chapters' => $chapters,
                'type' => $type
            ])->render();
        } elseif ($request->type == 'live') {
            return view('frontend.instructor-dashboard.course.partials.live-create-modal', [
                'courseId'  => $courseId,
                'chapterId' => $chapterId,
                'chapters'  => $chapters,
                'type'      => $type,
            ])->render();
        }
    }

    function lessonStore(ChapterLessonRequest $request)
    {
        $chapterItem = CourseChapterItem::create([
            'instructor_id' => Course::find(session()->get('course_create'))->instructor_id,
            'chapter_id' => $request->chapter_id,
            'type' => $request->type,
            'order' => CourseChapterItem::whereChapterId($request->chapter_id)->count() + 1,
        ]);

        $course = Course::findOrFail($request->course_id);
        $title = $request->title;
        if ($request->type == 'lesson') {
            CourseChapterLesson::create([
                'title' => $request->title,
                'description' => $request->description,
                'instructor_id' =>  $chapterItem->instructor_id,
                'course_id' => $request->course_id,
                'chapter_id' => $request->chapter_id,
                'chapter_item_id' => $chapterItem->id,
                'file_path' => $request->source == 'upload' ? $request->upload_path : $request->link_path,
                'storage' => $request->source,
                'file_type' => $request->file_type,
                'volume' => $request->volume,
                'duration' => $request->duration,
                'is_free' => $request->is_free,
            ]);
            $contentType = 'une nouvelle leçon';
        }elseif ($request->type == 'document') {
            CourseChapterLesson::create([
                'title' => $request->title,
                'description' => $request->description,
                'instructor_id' =>  $chapterItem->instructor_id,
                'course_id' => $request->course_id,
                'chapter_id' => $request->chapter_id,
                'chapter_item_id' => $chapterItem->id,
                'file_path' => $request->upload_path,
                'file_type' => $request->file_type,
            ]);
            $contentType = 'un nouveau document';
        } elseif ($request->type == 'quiz') {
            Quiz::create([
                'chapter_item_id' => $chapterItem->id,
                'instructor_id' => $chapterItem->instructor_id,
                'chapter_id' => $request->chapter,
                'course_id' => $request->course_id,
                'title' => $request->title,
                'time' => $request->time_limit,
                'attempt' => $request->attempts,
                'pass_mark' => $request->pass_mark,
                'total_mark' => $request->total_mark,
            ]);
            $contentType = 'un nouveau quiz';
        }
        $enrolled = Enrollment::where('course_id', $course->id)->get()->pluck('user_id')->toArray();
        $enrolledUsers = User::whereIn('id', $enrolled)->get();
        foreach ($enrolledUsers as $user) {
            //$user = $enroll->user;

            $htmlContent = "
                <html>
                <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
                    <div style='background-color: #fff; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                        <h2 style='color: #333;'>Bonjour {$user->name} {$user->last_name},</h2>
                        <p>Nous venons d'ajouter <strong>{$contentType}</strong> intitulé <strong>{$title}</strong> au cours <strong>{$course->title}</strong>.</p>
                        <p>Vous pouvez y accéder dès maintenant depuis votre tableau de bord.</p>
                        <p style='margin-top: 30px;'>
                            <a href='" . url('/student/learning/' . $course->slug) . "' style='background-color: #007BFF; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px;'>Voir le cours</a>
                        </p>
                        <p style='margin-top: 40px;'>Cordialement,<br>L’équipe " . config('app.name') . "</p>
                    </div>
                </body>
                </html>
            ";
            MailSenderTrait::setMailConfig();
            Mail::send([], [], function ($message) use ($user, $htmlContent) {
                $message->to($user->email)
                    ->subject('Nouveau contenu ajouté à votre cours')
                    ->html($htmlContent);
            });
        }
        return response()->json(['status' => 'success', 'message' => __('Lesson created successfully')]);
    }

    function lessonEdit(Request $request)
    {
        $courseId = $request->courseId;
        $chapterItemId = $request->chapterItemId;
        $chapterItem = CourseChapterItem::with(['lesson', 'quiz'])->find($chapterItemId);
        $chapters = CourseChapter::where('course_id', $courseId)->get();
        if ($request->type == 'lesson') {
            return view('course::course.partials.lesson-edit-modal', [
                'chapters' => $chapters,
                'courseId' => $courseId,
                'chapterItem' => $chapterItem
            ])->render();
        }elseif ($request->type == 'document') {
            return view('course::course.partials.document-edit-modal', [
                'chapters' => $chapters,
                'courseId' => $courseId,
                'chapterItem' => $chapterItem
            ])->render();
        } else {
            return view('course::course.partials.quiz-edit-modal', [
                'chapters' => $chapters,
                'courseId' => $courseId,
                'chapterItem' => $chapterItem
            ])->render();
        }
    }

    function lessonUpdate(ChapterLessonRequest $request)
    {
        checkAdminHasPermissionAndThrowException('course.management');

        $chapterItem = CourseChapterItem::findOrFail($request->chapter_item_id);

        $chapterItem->update([
            'chapter_id' => $request->chapter
        ]);

        if ($request->type == 'lesson') {
            $courseChapterLesson = CourseChapterLesson::where('chapter_item_id', $chapterItem->id)->first();

            $old_file_path = $courseChapterLesson->file_path;
            if (in_array($courseChapterLesson->storage, ['wasabi', 'aws']) && $old_file_path != $request->link_path) {
                $disk = Storage::disk($courseChapterLesson->storage);
                $disk->exists($old_file_path) && $disk->delete($old_file_path);
            }

            $courseChapterLesson->update([
                'title' => $request->title,
                'description' => $request->description,
                'course_id' => $chapterItem->course_id,
                'chapter_id' => $chapterItem->chapter_id,
                'chapter_item_id' => $chapterItem->id,
                'file_path' => $request->source == 'upload' ? $request->upload_path : $request->link_path,
                'storage' => $request->source,
                'file_type' => $request->file_type,
                'volume' => $request->volume,
                'duration' => $request->duration,
            ]);
        }elseif($request->type == 'document') {
            $courseChapterLesson = CourseChapterLesson::where('chapter_item_id', $chapterItem->id)->first();
            $courseChapterLesson->update([
                'title' => $request->title,
                'description' => $request->description,
                'course_id' => $chapterItem->course_id,
                'chapter_id' => $chapterItem->chapter_id,
                'chapter_item_id' => $chapterItem->id,
                'file_path' => $request->upload_path,
                'file_type' => $request->file_type,
            ]);
        } else {
            $quiz = Quiz::where('chapter_item_id', $chapterItem->id)->first();
            $quiz->update([
                'chapter_item_id' => $chapterItem->id,
                'title' => $request->title,
                'time' => $request->time_limit,
                'attempt' => $request->attempts,
                'pass_mark' => $request->pass_mark,
                'total_mark' => $request->total_mark,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => __('Lesson updated successfully')]);
    }

    function sortLessons(Request $request, string $chapterId)
    {
        $newOrder = $request->orderIds;
        foreach ($newOrder as $key => $itemId) {
            $chapterItem = CourseChapterItem::where(['chapter_id' => $chapterId, 'id' => $itemId])->first();
            $chapterItem->order = $key + 1;
            $chapterItem->save();
        }

        return response()->json(['status' => 'success', 'message' => __('Lesson sorted successfully')]);
    }

    function chapterLessonDestroy(string $chapterItemId)
    {
        checkAdminHasPermissionAndThrowException('course.management');
        $chapterItem = CourseChapterItem::findOrFail($chapterItemId);

        if ($chapterItem->type == 'quiz') {
            $quiz = $chapterItem->quiz;
            $question = $quiz->questions;
            foreach ($question as $key => $question) {
                $question->answers()->delete();
                $question->delete();
            }
            $quiz->delete();
            $chapterItem->delete();
        } else {
            if (in_array($chapterItem->lesson->storage, ['wasabi', 'aws'])) {
                $disk = Storage::disk($chapterItem->lesson->storage);
                $filePath = $chapterItem->lesson->file_path;
                $disk->exists($filePath) && $disk->delete($filePath);
            }
            // delete chapter item lesson if file exists
            if (File::exists(asset($chapterItem->lesson->file_path))) File::delete(asset($chapterItem->lesson->file_path));
            // delete lesson row
            $chapterItem->lesson()->delete();
            $chapterItem->delete();
        }

        return response()->json(['status' => 'success', 'message' => __('Lesson deleted successfully')]);
    }

    function createQuizQuestion(string $quizId)
    {
        return view('course::course.partials.quiz-question-create-modal', ['quizId' => $quizId])->render();
    }

    function storeQuizQuestion(Request $request, string $quizId)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
            'answers.*' => ['required', 'max:255'],
            'grade' => ['required', 'numeric', 'min:0']
        ], [
            'title.required' => __('Question title is required'),
            'title.max' => __('Question title should not be more than 255 characters'),
            'answers.*.required' => __('At least one answer is required'),
            'answers.*.max' => __('Answer should not be more than 255 characters'),
            'grade.required' => __('Grade is required'),
            'grade.numeric' => __('Grade should be a number'),
            'grade.min' => __('Grade should be greater than or equal to 0'),
        ]);

        $question = QuizQuestion::create([
            'quiz_id' => $quizId,
            'title' => $request->title,
            'grade' => $request->grade
        ]);

        foreach ($request->answers as $key => $answer) {
            $question->answers()->create([
                'title' => $answer,
                'correct' => isset($request->correct[$key]) ? 1 : 0,
                'question_id' => $question->id
            ]);
        }

        return response()->json(['status' => 'success', 'message' => __('Question created successfully')]);
    }

    function editQuizQuestion(string $questionId)
    {
        $question = QuizQuestion::findOrFail($questionId);
        return view('course::course.partials.quiz-question-edit-modal', ['question' => $question])->render();
    }

    function updateQuizQuestion(Request $request, string $questionId)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
            'answers.*' => ['required', 'max:255'],
            'grade' => ['required', 'numeric', 'min:0']
        ], [
            'title.required' => __('Question title is required'),
            'title.max' => __('Question title should not be more than 255 characters'),
            'answers.*.required' => __('At least one answer is required'),
            'answers.*.max' => __('Answer should not be more than 255 characters'),
            'grade.required' => __('Grade is required'),
            'grade.numeric' => __('Grade should be a number'),
            'grade.min' => __('Grade should be greater than or equal to 0'),
        ]);

        $question = QuizQuestion::findOrFail($questionId);
        $question->update([
            'title' => $request->title,
            'grade' => $request->grade
        ]);
        // update or delete answers
        $question->answers()->delete();
        foreach ($request->answers as $key => $answer) {
            $question->answers()->create([
                'title' => $answer,
                'correct' => isset($request->correct[$key]) ? 1 : 0,
                'question_id' => $question->id
            ]);
        }

        return response()->json(['status' => 'success', 'message' => __('Question updated successfully')]);
    }

    function destroyQuizQuestion(string $questionId)
    {
        $question = QuizQuestion::findOrFail($questionId);
        $question->answers()->delete();
        $question->delete();
        return response()->json(['status' => 'success', 'message' => __('Question deleted successfully')]);
    }
    public function copyChapterItem($chapterItemId)
    {
        $chapterItem = CourseChapterItem::with('quiz.questions')->findOrFail($chapterItemId);
        $chapter = CourseChapter::findOrFail($chapterItem->chapter_id);
        $selectedCourse = Course::findorFail($chapter->course_id);
        $courses = Course::with('chapters')->get();
        return view('course::course.partials.quiz-duplicate-modal', ['courses' => $courses,'selectedCourse'=>$selectedCourse,'chapter'=>$chapter,'chapter_item_id'=>$chapterItemId])->render();
    }
    public function copyChapterItemStore(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'chapter_item_id' => 'required|exists:course_chapter_items,id',
            'chapter_id' => 'required|exists:course_chapters,id',
        ]);

        $chapterItem = CourseChapterItem::with('quiz.questions')->findOrFail($request->chapter_item_id);
        $newChapterItem = $chapterItem->replicate();
        $newChapterItem->chapter_id = $request->chapter_id;
        $newChapterItem->save();

        if ($chapterItem->quiz) {
            $newQuiz = $chapterItem->quiz->replicate();
            $newQuiz->chapter_item_id = $newChapterItem->id;
            $newQuiz->save();

            foreach ($chapterItem->quiz->questions as $question) {
                $newQuestion = $question->replicate();
                $newQuestion->quiz_id = $newQuiz->id;
                $newQuestion->save();

                foreach ($question->answers as $answer) {
                    $newAnswer = $answer->replicate();
                    $newAnswer->question_id = $newQuestion->id;
                    $newAnswer->save();
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => __('Chapter item copied successfully')]);
    }
}
