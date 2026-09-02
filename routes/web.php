<?php
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\TeacherProfileController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\TeacherBookingController;
use App\Http\Controllers\TeacherAvailabilityController;
use App\Http\Controllers\TeacherReviewController;
use App\Http\Controllers\TeacherEarningController;

use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentTeacherController;
use App\Http\Controllers\StudentBookingController;
use App\Http\Controllers\StudentBookingRequestController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\StudentReviewController;
use App\Http\Controllers\StudentPaymentController;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminTeacherController;
use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\AdminPaymentController;

use App\Http\Controllers\HomeController;

use App\Models\Teacher;
use App\Models\DanceStyle;
use App\Http\Controllers\BookingMessageController;
use App\Http\Controllers\AdminPlatformMessageController;
use App\Http\Controllers\PlatformMessageController;
/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

/*
Route::get('/', function () {
    return view('home');
});
*/


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('home');


/*
|--------------------------------------------------------------------------
| Find a Teacher
|--------------------------------------------------------------------------
|
| Public page.
| Uses REAL Teacher and DanceStyle database records.
| Existing student teacher routes below are NOT changed.
|
*/

Route::get(
    '/find-a-teacher',
    function () {

        $danceStyles = DanceStyle::query()
            ->orderBy('name')
            ->get();


        $teachers = Teacher::query()
            ->with([
                'user',
                'danceStyles',
            ])

            ->withAvg(
                [
                    'reviews as average_rating' => function ($query) {
                        $query->where('approved', true);
                    }
                ],
                'rating'
            )

            ->withCount(
                [
                    'reviews as review_count' => function ($query) {
                        $query->where('approved', true);
                    }
                ]
            )


            /*
            |--------------------------------------------------------------------------
            | Location Filter
            |--------------------------------------------------------------------------
            */

            ->when(
                request('location'),
                function ($query, $location) {

                    $query->where(
                        function ($subQuery) use ($location) {

                            $subQuery
                                ->where(
                                    'city',
                                    'like',
                                    '%' . $location . '%'
                                )

                                ->orWhere(
                                    'province',
                                    'like',
                                    '%' . $location . '%'
                                );
                        }
                    );
                }
            )


            /*
            |--------------------------------------------------------------------------
            | Dance Style Filter
            |--------------------------------------------------------------------------
            */

            ->when(
                request('dance_style_id'),
                function ($query, $danceStyleId) {

                    $query->whereHas(
                        'danceStyles',
                        function ($danceStyleQuery) use ($danceStyleId) {

                            $danceStyleQuery->where(
                                'dance_styles.id',
                                $danceStyleId
                            );
                        }
                    );
                }
            )


            /*
            |--------------------------------------------------------------------------
            | Teacher Order
            |--------------------------------------------------------------------------
            */

            ->orderByDesc('average_rating')
            ->orderByDesc('review_count')
            ->orderByDesc('id')

            ->get();


        return view(
            'public.find-teacher',
            compact(
                'teachers',
                'danceStyles'
            )
        );
    }
)->name('public.find-teacher');


/*
|--------------------------------------------------------------------------
| Become a Teacher
|--------------------------------------------------------------------------
*/

Route::view(
    '/become-a-teacher',
    'public.become-teacher'
)->name('public.become-teacher');


/*
|--------------------------------------------------------------------------
| Dance Styles
|--------------------------------------------------------------------------
|
| Uses REAL DanceStyle database records.
|
*/

Route::get(
    '/dance-styles',
    function () {

        $danceStyles = DanceStyle::query()
            ->orderBy('name')
            ->get();


        return view(
            'public.dance-styles',
            compact('danceStyles')
        );
    }
)->name('public.dance-styles');


/*
|--------------------------------------------------------------------------
| How It Works
|--------------------------------------------------------------------------
*/

Route::view(
    '/how-it-works',
    'public.how-it-works'
)->name('public.how-it-works');


/*
|--------------------------------------------------------------------------
| About Us
|--------------------------------------------------------------------------
*/

Route::view(
    '/about',
    'public.about'
)->name('public.about');


/*
|--------------------------------------------------------------------------
| Contact
|--------------------------------------------------------------------------
|
| The Contact VIEW is public.
| Form submission backend will be connected separately.
|
*/

Route::view(
    '/contact',
    'public.contact'
)->name('public.contact');


/*
|--------------------------------------------------------------------------
| REGISTER
|--------------------------------------------------------------------------
*/

Route::get(
    '/register',
    [AuthController::class, 'showRegister']
)->name('register');


Route::post(
    '/register',
    [AuthController::class, 'register']
)->name('register.store');

Route::get('/language/{locale}', function ($locale) {

    if (!in_array($locale, ['en', 'fr'])) {
        abort(404);
    }

    session(['locale' => $locale]);

    App::setLocale($locale);

    return redirect()->back();

})->name('language.switch');
/*
|--------------------------------------------------------------------------
| DancePair Client Messages
|--------------------------------------------------------------------------
*/

Route::get(
    '/platform-messages/inbox',
    [PlatformMessageController::class, 'inbox']
)->name('platform-messages.inbox');


Route::post(
    '/platform-messages/{recipient}/shown',
    [PlatformMessageController::class, 'shown']
)->name('platform-messages.shown');


Route::post(
    '/platform-messages/{recipient}/dismiss',
    [PlatformMessageController::class, 'dismiss']
)->name('platform-messages.dismiss');
/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get(
    '/login',
    [AuthController::class, 'showLogin']
)->name('login');


Route::post(
    '/login',
    [AuthController::class, 'login']
)->name('login.store');

Route::post(
    '/bookings/{booking}/messages',
    [BookingMessageController::class, 'store']
)->name('bookings.messages.store');
/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [AuthController::class, 'logout']
)
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {


    /*
    |--------------------------------------------------------------------------
    | TEACHER
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/teacher/dashboard',
        [TeacherDashboardController::class, 'index']
    )->name('teacher.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/teacher/profile',
        [TeacherProfileController::class, 'edit']
    )->name('teacher.profile.edit');


    Route::put(
        '/teacher/profile',
        [TeacherProfileController::class, 'update']
    )->name('teacher.profile.update');


    /*
    |--------------------------------------------------------------------------
    | Cities
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/teacher/cities/{province}',
        [TeacherProfileController::class, 'cities']
    )->name('teacher.cities');


    /*
    |--------------------------------------------------------------------------
    | Availability
    |--------------------------------------------------------------------------
    */

   /*
|--------------------------------------------------------------------------
| Availability
|--------------------------------------------------------------------------
*/

Route::get(
    '/teacher/availability',
    [TeacherAvailabilityController::class, 'index']
)->name('teacher.availability');


Route::post(
    '/teacher/availability',
    [TeacherAvailabilityController::class, 'store']
)->name('teacher.availability.store');


Route::put(
    '/teacher/availability/{availability}',
    [TeacherAvailabilityController::class, 'update']
)->name('teacher.availability.update');


Route::delete(
    '/teacher/availability/{availability}',
    [TeacherAvailabilityController::class, 'destroy']
)->name('teacher.availability.destroy');

    /*
    |--------------------------------------------------------------------------
    | Bookings
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/teacher/bookings',
        [TeacherBookingController::class, 'index']
    )->name('teacher.bookings');


    Route::post(
        '/teacher/bookings/{booking}/accept',
        [TeacherBookingController::class, 'accept']
    )->name('teacher.bookings.accept');


    Route::post(
        '/teacher/bookings/{booking}/reject',
        [TeacherBookingController::class, 'reject']
    )->name('teacher.bookings.reject');


    Route::get(
        '/teacher/bookings/{booking}/student',
        [TeacherBookingController::class, 'studentProfile']
    )->name('teacher.bookings.student');


    /*
    |--------------------------------------------------------------------------
    | Teacher Reviews
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/teacher/reviews',
        [TeacherReviewController::class, 'index']
    )->name('teacher.reviews');


    Route::post(
        '/teacher/bookings/{booking}/review',
        [TeacherReviewController::class, 'store']
    )->name('teacher.reviews.store');


    /*
    |--------------------------------------------------------------------------
    | Earnings
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/teacher/earnings',
        [TeacherEarningController::class, 'index']
    )->name('teacher.earnings');



    /*
    |--------------------------------------------------------------------------
    | STUDENT
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/student/dashboard',
        [StudentDashboardController::class, 'index']
    )->name('student.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/student/profile',
        [StudentProfileController::class, 'edit']
    )->name('student.profile.edit');


    Route::put(
        '/student/profile',
        [StudentProfileController::class, 'update']
    )->name('student.profile.update');


    /*
    |--------------------------------------------------------------------------
    | Find Teachers
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/student/teachers',
        [StudentTeacherController::class, 'index']
    )->name('student.teachers');


    Route::get(
        '/student/teachers/{teacher}',
        [StudentTeacherController::class, 'show']
    )->name('student.teachers.show');


    /*
    |--------------------------------------------------------------------------
    | Booking Request
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/student/availability/{availability}/request',
        [StudentBookingRequestController::class, 'store']
    )->name('student.booking.request');


    /*
    |--------------------------------------------------------------------------
    | Student Bookings
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/student/bookings',
        [StudentBookingController::class, 'index']
    )->name('student.bookings');


    Route::put(
        '/student/bookings/{booking}',
        [StudentBookingController::class, 'update']
    )->name('student.bookings.update');


    Route::delete(
        '/student/bookings/{booking}',
        [StudentBookingController::class, 'destroy']
    )->name('student.bookings.destroy');


    /*
    |--------------------------------------------------------------------------
    | Student Reviews
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/student/reviews',
        [StudentReviewController::class, 'index']
    )->name('student.reviews');


    Route::post(
        '/student/bookings/{booking}/review',
        [StudentReviewController::class, 'store']
    )->name('student.reviews.store');


    /*
    |--------------------------------------------------------------------------
    | Student Payments
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/student/payments',
        [StudentPaymentController::class, 'index']
    )->name('student.payments.index');


    Route::get(
        '/student/bookings/{booking}/payment',
        [StudentPaymentController::class, 'show']
    )->name('student.payments.show');


    Route::post(
        '/student/bookings/{booking}/payment/checkout',
        [StudentPaymentController::class, 'checkout']
    )->name('student.payments.checkout');


    Route::get(
        '/student/bookings/{booking}/payment/success',
        [StudentPaymentController::class, 'success']
    )->name('student.payments.success');


    Route::get(
        '/student/payments/{payment}/receipt',
        [StudentPaymentController::class, 'receipt']
    )->name('student.payments.receipt');



    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Admin Bookings
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/bookings',
        [AdminBookingController::class, 'index']
    )->name('admin.bookings');


    Route::get(
        '/admin/bookings/{booking}',
        [AdminBookingController::class, 'show']
    )->name('admin.bookings.show');


    /*
    |--------------------------------------------------------------------------
    | Admin Reviews
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/reviews',
        [AdminReviewController::class, 'index']
    )->name('admin.reviews');


    Route::get(
        '/admin/reviews/{review}',
        [AdminReviewController::class, 'show']
    )->name('admin.reviews.show');


    /*
    |--------------------------------------------------------------------------
    | Admin Payments
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/payments',
        [AdminPaymentController::class, 'index']
    )->name('admin.payments');


    Route::get(
        '/admin/payments/{payment}',
        [AdminPaymentController::class, 'show']
    )->name('admin.payments.show');

/*
|--------------------------------------------------------------------------
| Admin DancePair Messages
|--------------------------------------------------------------------------
*/

Route::get(
    '/admin/platform-messages',
    [AdminPlatformMessageController::class, 'index']
)->name('admin.platform-messages');


Route::post(
    '/admin/platform-messages',
    [AdminPlatformMessageController::class, 'store']
)->name('admin.platform-messages.store');


Route::patch(
    '/admin/platform-messages/{platformMessage}/toggle',
    [AdminPlatformMessageController::class, 'toggle']
)->name('admin.platform-messages.toggle');


Route::delete(
    '/admin/platform-messages/{platformMessage}',
    [AdminPlatformMessageController::class, 'destroy']
)->name('admin.platform-messages.destroy');
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/dashboard',
        [AdminDashboardController::class, 'index']
    )->name('admin.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Teachers
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/teachers',
        [AdminTeacherController::class, 'index']
    )->name('admin.teachers');


    Route::get(
        '/admin/teachers/{teacher}/edit',
        [AdminTeacherController::class, 'edit']
    )->name('admin.teachers.edit');


    Route::put(
        '/admin/teachers/{teacher}',
        [AdminTeacherController::class, 'update']
    )->name('admin.teachers.update');


    /*
    |--------------------------------------------------------------------------
    | DELETE TEACHER
    |--------------------------------------------------------------------------
    */

    Route::delete(
        '/admin/teachers/{teacher}',
        [AdminTeacherController::class, 'destroy']
    )->name('admin.teachers.destroy');


    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/students',
        [AdminStudentController::class, 'index']
    )->name('admin.students');


    Route::get(
        '/admin/students/{student}/edit',
        [AdminStudentController::class, 'edit']
    )->name('admin.students.edit');


    Route::put(
        '/admin/students/{student}',
        [AdminStudentController::class, 'update']
    )->name('admin.students.update');


    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/settings',
        [AdminSettingsController::class, 'edit']
    )->name('admin.settings');


    Route::put(
        '/admin/settings',
        [AdminSettingsController::class, 'update']
    )->name('admin.settings.update');

});