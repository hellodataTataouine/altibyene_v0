<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\FrontendController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\AuthenticatedController;

Route::middleware(['guest:sanctum'])->group(function () {
    Route::post('register', [AuthenticatedController::class, 'register'])->name('api.register');
    Route::post('login', [AuthenticatedController::class, 'login'])->name('api.patient-login');
    Route::post('forget-password', [AuthenticatedController::class, 'forgetPassword'])->name('api.forget-password');
    Route::post('reset-password', [AuthenticatedController::class, 'resetPassword'])->name('api.reset-password');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthenticatedController::class, 'logout'])->name('api.logout');
    Route::post('logout/all-app', [AuthenticatedController::class, 'logoutAllApp'])->name('api.logoutAllApp');

    Route::controller(DashboardController::class)->group(function () {
        Route::get('enrolled-courses', 'enrolled_courses');
        Route::get('wishlist-courses', 'wishlist_courses');
        Route::get('add-remove-wishlist/{course:slug}', 'add_remove_wishlist')->where('slug', '[a-zA-Z0-9-_]+');

        Route::get('learning/{slug}', 'course_learning')->where('slug', '[a-zA-Z0-9-_]+')->name('api.learning');
        Route::get('learning/{slug}/get-file-info/{type}/{lesson_id}', 'get_lesson_info')->where('slug', '[a-zA-Z0-9-_]+')->where('type', 'lesson|document|live|quiz')->where('lesson_id', '[0-9]+')->name('api.get-file-info');
        Route::get('learning/make-lesson-complete/{lesson_id}', 'make_lesson_complete')->where('lesson_id', '[0-9]+');

        Route::get('learning/{slug}/quiz/{id}', 'quiz_index')->where('slug', '[a-zA-Z0-9-_]+')->where('id', '[0-9]+')->name('api.quiz-index');
        Route::post('learning/{slug}/quiz/{id}', 'quiz_store')->where('slug', '[a-zA-Z0-9-_]+')->where('id', '[0-9]+');
        Route::get('learning/{slug}/quiz-results/{id}', 'quiz_results')->where('slug', '[a-zA-Z0-9-_]+')->where('id', '[0-9]+');

        Route::get('questions/{course_slug}/{lesson_id}', 'fetch_lesson_questions')->where('course_slug', '[a-zA-Z0-9-_]+')->where('lesson_id', '[0-9]+');
        Route::post('questions-create/{course_slug}/{lesson_id}', 'create_lesson_questions')->where('course_slug', '[a-zA-Z0-9-_]+')->where('lesson_id', '[0-9]+')->name('api.questions-create');
        Route::delete('questions-destroy/{question_id}', 'destroyQuestion')->where('question_id', '[0-9]+');

        Route::post('questions/replay/{lesson_id}/{question_id}', 'create_replay_questions')->where('lesson_id', '[0-9]+')->where('question_id', '[0-9]+');
        Route::delete('questions/replay/{reply_id}', 'destroyReply')->where('reply_id', '[0-9]+');

        Route::get('learning/{slug}/announcements', 'course_announcements')->where('slug', '[a-zA-Z0-9-_]+');

        Route::get('orders', 'orders');
        Route::get('orders/{invoice_id}', 'show_order')->where('invoice_id', '[a-zA-Z0-9-_]+');
        
        Route::get('reviews', 'reviews');
        Route::get('reviews/{id}', 'show_review')->where('id', '[a-zA-Z0-9-_]+');
        Route::delete('reviews/{id}', 'destroy_review')->where('id', '[a-zA-Z0-9-_]+');
        Route::get('quiz-attempts', 'quiz_attempts');
        Route::get('quiz-attempts/{id}', 'show_quiz_attempt')->where('id', '[a-zA-Z0-9-_]+');
        Route::get('profile', 'profile');
        Route::post('update-profile-picture', 'update_profile_picture')->withoutMiddleware('json.only');
        Route::put('update-profile', 'update_profile');
        Route::put('update-bio', 'update_bio');
        Route::put('update-password', 'update_password');
        Route::put('update-address', 'update_address');
        Route::put('update-social-links', 'update_socials');
        
    });
    Route::controller(CartController::class)->group(function () {
        Route::get('cart-list', 'index');
        Route::post('add-to-cart/{slug}', 'add_to_cart')->where('slug', '[a-zA-Z0-9-_]+');
        Route::delete('remove-from-cart/{slug}', 'remove_from_cart')->where('slug', '[a-zA-Z0-9-_]+');
    });
});

Route::controller(FrontendController::class)->group(function () {
    Route::get('settings', 'settings');
    Route::get('countries', 'country_list');
    Route::get('social-links', 'socialLinks');
    Route::get('language-list', 'allLanguages');
    Route::get('currency-list', 'allCurrency');
    Route::get('static-language/{code?}', 'getLanguageFile');
    Route::post('contact-us', 'contactUs')->middleware('throttle:3,60');
    Route::post('subscribe-us', 'newsletter_request')->middleware('throttle:3,60');
    Route::get('course-main-categories', 'main_categories');
    Route::get('course-sub-categories/{slug}', 'sub_categories');
    Route::get('course-languages', 'course_languages');
    Route::get('course-levels', 'course_levels');
    Route::get('popular-courses', 'popular_courses');
    Route::get('fresh-courses', 'fresh_courses');
    Route::get('search-courses', 'search_courses');
    Route::get('course/{slug}', 'course_details')->where('slug', '[a-zA-Z0-9-_]+');
    Route::get('course/free-lesson-info/{lesson_id}', 'get_lesson_info')->where('lesson_id', '[0-9]+')->name('api.free-lesson');
    Route::get('course/reviews/{slug}', 'course_reviews')->where('slug', '[a-zA-Z0-9-_]+');
    Route::get('privacy-policy', 'privacy_policy');
    Route::get('terms-and-conditions', 'terms_and_conditions');
    Route::get('faqs', 'faqs');
    Route::get('on-boarding-screen', 'on_boarding_screen');
});

Route::middleware('payment.api')->group(function () {
    Route::get('download-invoice/{invoice_id}', [DashboardController::class,'downloadInvoice'])->where('invoice_id', '[a-zA-Z0-9-_]+')->withoutMiddleware('json.only');
    Route::get('download-certificate/{course_slug}', [DashboardController::class,'downloadCertificate'])->where('course_slug', '[a-zA-Z0-9-_]+')->withoutMiddleware('json.only');
});

Route::fallback(function () {
    return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
});