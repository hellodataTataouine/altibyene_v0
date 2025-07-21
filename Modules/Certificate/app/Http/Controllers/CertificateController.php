<?php

namespace Modules\Certificate\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Course;
use App\Models\User;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Modules\Order\app\Models\Enrollment;
use App\Traits\MailSenderTrait;

class CertificateController extends Controller
{
    use RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certifications = Certification::with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('certificate::index', [
            'certifications' => $certifications
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $enrolledCourses = Enrollment::all()->pluck('user_id')->toArray();
        $users = User::whereIn('id', $enrolledCourses)->get();
        $enrolledCourses_ids = Enrollment::all()->pluck('course_id')->toArray();
        $courses = Course::whereIn('id', $enrolledCourses_ids)->get();
        return view('certificate::create', [
            'users' => $users,
            'courses' => $courses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
        //dd($request->all());
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'upload_path' => 'required|string|max:255',
        ]);
        $certification = new Certification();
        $certification->user_id = $request->user_id;
        $certification->course_id = $request->course_id;
        $certification->certificat = $request->upload_path;
        $certification->save();
        $user = User::find($request->user_id);
        $course = Course::find($request->course_id);
        $downloadUrl = asset($certification->certificat);
        $htmlContent = "
            <html>
            <body style='font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px;'>
                <div style='max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
                    <h2>Bonjour {$user->name} {$user->last_name},</h2>
                    <p>Votre certificat pour le cours <strong>{$course->title}</strong> est maintenant disponible.</p>
                    <p>Vous pouvez le télécharger en cliquant sur le bouton ci-dessous ou en vous connectant à votre tableau de bord.</p>
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='{$downloadUrl}' style='background-color: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;'>Télécharger mon certificat</a>
                    </p>
                    <p>Merci pour votre confiance,<br>L'équipe ". config('app.name') ."</p>
                </div>
            </body>
            </html>
        ";
        MailSenderTrait::setMailConfig();
        Mail::send([], [], function ($message) use ($user, $htmlContent) {
            $message->to($user->email)
                ->subject('Votre certificat est prêt')
                ->html($htmlContent);
        });
        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.certificate.store', ['certificats' => $certification->id]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('certificate::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('certificate::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
    public function getCoursesByUser(User $user)
    {
        $courseIds = Enrollment::where('user_id', $user->id)->pluck('course_id');
        $courses = Course::whereIn('id', $courseIds)->get();

        return response()->json($courses);
    }
}
