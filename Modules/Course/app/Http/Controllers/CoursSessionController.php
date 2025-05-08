<?php

namespace Modules\Course\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CoursSession;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CoursSessionController extends Controller
{
    use RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CoursSession::query();
        $query->when($request->keyword, fn ($q) => $q->where('title', 'like', '%' . request('keyword') . '%'));
        $query->when($request->date && $request->filled('date'), fn($q) => $q->whereDate('start_date', $request->date));
        $query->withCount('cours');
        $orderBy = $request->order_by == 1 ? 'asc' : 'desc';
        $sessions = $request->par_page == 'all' ?
            $query->orderBy('id', $orderBy)->get() :
            $query->orderBy('id', $orderBy)->paginate($request->par_page ?? null)->withQueryString();
        return view('course::session.index',compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::all();
        return view('course::session.create',compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
        $request->validate([
            'title'=> 'required|string|max:50',
            'cours_id'=>'required|integer|exists:courses,id',
            'start_date'=>'required|date',
            'end_date'=>'required|date',
            'type'=>'required|string|max:10',
            'max_enrollments'=>'required|integer',
        ]);
        CoursSession::create([
            'title'=>$request->title,
            'cours_id'=>$request->cours_id,
            'start_date' => $request->start_date,
            'end_date'=>$request->end_date,
            'type'=>$request->type,
            'max_enrollments'=>$request->max_enrollments,
            'enrolled_students'=>0,
        ]);
        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.course-session.index');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $courses = Course::all();
        $session = CoursSession::findOrFail($id);
        return view('course::session.edit',compact('courses','session'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $courses = Course::all();
        $session = CoursSession::findOrFail($id);
        return view('course::session.edit',compact('courses','session'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'title'=> 'required|string|max:50',
            'cours_id'=>'required|integer|exists:courses,id',
            'start_date'=>'required|date',
            'end_date'=>'required|date',
            'type'=>'required|string|max:10',
            'max_enrollments'=>'required|integer',
        ]);
        $session= CoursSession::findOrFail($id);
        $session->fill($request->all());
        $session->save();
        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.course-session.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        CoursSession::findOrFail($id)->delete();
        return response()->json(['status' => 'success', 'message' => __('Course session deleted successfully')]);
    }
}
