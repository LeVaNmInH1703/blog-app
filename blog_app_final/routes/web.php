<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmojiController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\messageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ToastController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\LastActivityUser;
use App\Models\Notification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ______________________ test
Route::get('/test', [HomeController::class, 'test']);

//login with gg
Route::get('/login-with-google', [AuthController::class, 'loginWithGoogle'])->name('loginWithGoogle');
Route::get('/login-by-google/callback', [AuthController::class, 'loginWithGoogleCallback']);
//login
Route::get('/login', [AuthController::class, 'getLogin'])->name('login');
Route::post('/login', [AuthController::class, 'postLogin'])->middleware('throttle:login');
//register
Route::get('/register', [AuthController::class, 'getRegister'])->name('register');
Route::post('/register', [AuthController::class, 'postRegister']);
//forgot password
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/forgot-password', [AuthController::class, 'postForgotPassword']);
//reset password
Route::get('reset-password', [AuthController::class, 'resetPassword']);
Route::post('reset-password', [AuthController::class, 'postResetPassword']);
//verify mail
Route::get('/verify-email', [AuthController::class, 'verifyEmail']);
Route::get('/waiting-for-email-verification', [AuthController::class, 'waitingForEmailVerification'])->name('waitingForEmailVerification');
Route::get('/resend-verification-email', [AuthController::class, 'resendVerificationEmail'])->name('resendVerificationEmail');
//middleware auth
Route::middleware(['auth', 'auth.session', LastActivityUser::class])->group(function () {
    // ______________________ lang
    Route::get('/lang/{locale}', function ($locale) {
        App::setLocale($locale);
        session(['locale' => $locale]);
        return redirect()->back();
    })->name('lang');

    // ______________________ home

    // Route::get('/',[function (){
    //     return 'a';
    // }] )->name('home');
    // ______________________ session
    Route::get('/set-session/{key}/{value}', [function ($key, $value) {

        if ($key != 'chatCurrent') abort(404);
        session([$key => $value]);
    }]);
    // ______________________ users
    Route::get('/search-users', [UserController::class, 'search'])->name('searchUser');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    // ______________________ friendship
    Route::middleware(["throttle:1,0.01"])->group(
        function () {
            Route::get('/add-friend/{user}', [FriendShipController::class, 'addFriend'])->name('addFriend');
            Route::get('/accept-request/{user}', [FriendShipController::class, 'acceptRequest'])->name('acceptRequest');
            Route::get('/cancel-request/{user}', [FriendShipController::class, 'cancelRequest'])->name('cancelRequest');
        }
    );
    Route::get('/unfriend/{user}', [FriendShipController::class, 'unfriend'])->name('unfriend');

    // ______________________ toast
    Route::get('/get-toast-for-message/{message}', [ToastController::class, 'getForMessage'])->name('getForMessage');
    Route::get('/see-all-notification', [function () {
        Notification::where([
            ['user_id_receive', Auth::id()],
            ['isSaw', false]
        ])->update(['isSaw' => true]);
    }]);

    // ______________________ message
    Route::get('/message', [messageController::class, 'message'])->name('message');
    Route::post('/message', [messageController::class, 'sendMessage']);
    Route::get('/chat-history/{chat}', [messageController::class, 'chatHistory'])->name('chatHistory');
    Route::get('/get-chat-item-patial-view/{message}', [messageController::class, 'getChatItemPatialView'])->name('getChatItemPatialView');
    Route::get('/update-seen-message-in-group/{group}', [messageController::class, 'updateSeenMessageInGroup'])->name('updateSeenMessageInGroup');
    Route::get('/count-group-has-new-message', [messageController::class, 'countGroupHasNewMessage'])->name('countGroupHasNewMessage')
        // ->middleware('throttle:countGroupHasNewMessage')
    ;
    //______________________ group
    Route::get('/chat-with/{user}', [ChatController::class, 'chatWith'])->name('chatWith');
    Route::post('/add-new-group-chat', [ChatController::class, 'addNewGroupChat']);
    Route::get('/about-group/{group}', [ChatController::class, 'aboutGroup'])->name('aboutGroup');
    Route::get('/kick-member/{group}/{user}', [ChatController::class, 'kickMember'])->name('kickMember');
    Route::get('/add-member/{group}/{user}', [ChatController::class, 'addMember'])->name('addMember');
    Route::post('/update-group/{group}', [ChatController::class, 'updateGroup'])->can('update', 'group');
    Route::get('/leave-group/{group}', [ChatController::class, 'leaveGroup'])->name('leaveGroup');
    Route::get('/disovle-group/{group}', [ChatController::class, 'disovleGroup'])->name('disovleGroup');
    
    // ______________________ blog
    Route::get('/', [BlogController::class, 'index'])->name('home');
    Route::get('/load-more-blog', [BlogController::class, 'loadMoreBlog'])->name('loadMoreBlog');// Tạo emoji
    Route::post('/emoji', [EmojiController::class, 'store'])->name('emojis.store');
    Route::get('/blog-detail/{blog}', [BlogController::class, 'blogDetail'])->name('blogDetail');
    Route::get('/create-post', [BlogController::class, 'createBlogGetView'])->name('createBlog');
    Route::post('/create-blog', [BlogController::class, 'createBlog']);
    //return view partial
    Route::get('/count-emoji/{name}/{obj}', [BlogController::class, 'countEmoji'])->name('countEmoji');
    Route::get('/get-emoji/{emoji?}/{isShowName?}', [BlogController::class, 'getEmoji'])->name('getEmoji');
    // ______________________ comment 
    Route::post('/create-comment/{blog}/{comment?}', [CommentController::class, 'createComment']);
    Route::get('/get-replies/{comment?}', [CommentController::class, 'getReplies']);

    // ______________________ file
    Route::get('/download/{fileName}/{oldName?}', [FileController::class, 'download'])->name('download.file');

    // ______________________ profile
    Route::get('/profile/{user?}', [UserController::class, 'profile'])->name('profile');

    // ______________________ auth
    Route::post('/update/avatar', [UserController::class, 'updateAvatar']);
    Route::post('/update/name', [UserController::class, 'updateName']);
    Route::post('/update/introduce', [UserController::class, 'updateIntroduce']);


    // ______________________ about
    Route::get('/about', [HomeController::class, 'about'])->name('about');

    // ______________________ logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    // ______________________ logout
    Route::get('/get-notification/{notification}', [NotificationController::class, 'getNotification'])->name('getNotification');
});

// note:
/*
    laravel toast https://www.youtube.com/watch?v=iHXxiUP7QmQ&list=PLieV0Y7g-FFxLo_abo51cHZcxXUzVLc_E&index=2 1:05:00

*/