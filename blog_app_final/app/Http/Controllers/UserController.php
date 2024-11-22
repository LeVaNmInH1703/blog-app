<?php

namespace App\Http\Controllers;

use App\Events\requestReloadPage;
use App\Models\Emoji;
use App\Models\FriendShips;
use App\Models\Message;
use App\Models\User;
use App\Services\BlogService;
use App\Services\UserService;
use App\View\Components\CardInfoUserComponent;
use App\View\Components\UserListComponent;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class UserController extends FriendshipController
{
    public $userService,$blogService;
    public function __construct(UserService $userService,BlogService $blogService)
    {
        $this->userService = $userService;
        $this->blogService = $blogService;
    }
    // public function createUser($request)
    // {
    //     return $this->useTransaction(function () use ($request) {
    //         $newUser = User::create([
    //             "email" => $request->email,
    //             "password" => $request->password,
    //             'name' => $request->name,
    //         ]);
    //         $image = $request->file('fileAvatar');
    //         $imageAvatarName = '';
    //         if ($image) {
    //             $imageAvatarName = 'profile_' . $newUser->id . Str::random(20) . '.' . $image->extension();
    //             $this->resizeAndSaveImage($imageAvatarName, $image);
    //         } else {
    //             if ($request->avatar > 8 || $request->avatar < 1)
    //                 $request->avatar = 1;
    //             $imageAvatarName = 'avatar' . $request->avatar . '.png';
    //         }
    //         User::find($newUser->id)->update([
    //             'avatar_filename' => asset('images/') . '/' . $imageAvatarName,
    //         ]);
    //     });
    // }
    // public function search(Request $request)
    // {
    //     if (!$request->search) return response('');
    //     $users = User::where('name', 'LIKE', '%' . strtolower($request->search) . '%')
    //         ->where('name', '!=', Auth::user()->name)
    //         ->get();
    //     return response((string)(new UserListComponent($users,"Search result"))->render());
    // }

    // public function resizeAndSaveImage($name, $image, $width = 200)
    // {
    //     $ratioWidthHeight = getimagesize($image)[0] / getimagesize($image)[1];
    //     // resize
    //     $manager = new ImageManager(new Driver());
    //     $manager->read($image)->resize($width, $width / $ratioWidthHeight)
    //         ->save(public_path('images_resize') . '/' . $name);

    //     //move
    //     $image->move(public_path('images'), $name);
    // }
    public function index()
    {
        $usersMayKnow = $this->userService->getUsersMayKnow();
        return view("pages.users", compact('usersMayKnow'));
    }
    public function profile(User $user = null)
    {
        if (!$user || Auth::id() == $user->id) {
            $user = Auth::user();
        } else {
            //show profile 
        }
        $temp = User::whereIn('id', $user->friends->pluck('id'))
            ->whereIn('id', Auth::user()->friends->pluck('id'));
        $user->commonFriends = $temp->take(7)->get();
        $user->commonFriendsCount = $temp->count();
        $userFiles = collect(); // Khởi tạo một Collection để lưu trữ file
        $user->blogs = $user->blogs->sortByDesc('created_at');
        // foreach ($user->blogs as &$blog) {
        //     $blog=$this->blogService->getBlogDetail($blog->id);
        //     // dd($blog);
        //     $userFiles = $userFiles->merge($blog->medias); // Thêm các file của blog vào collection
        // }
        $user->blogs = $user->blogs->map(function ($blog) {
            return $this->blogService->getBlogDetail($blog->id);
        });
        $user->medias = $userFiles;
        // dd($user);
        return view("pages.profile", compact('user'));
    }
    // // ______________edit profile
    public function updateAvatar(Request $request)
    {
        // $request->validate([
        //     'fileAvatar' => ['required', 'image', 'max:10000']
        // ]);
        $image = $request->file('fileAvatar');
        if (!$image) return redirect()->back()->withErrors(['fileAvatar' => __('public.Please select an image for your avatar')]);

        $this->userService->updateAuthAvatar($image);
       
        return redirect()->back()->with('message', __('public.Update success'));
    }
    public function updateName(Request $request)
    {
        // $request->validate([
        //     'authName' => ['required', 'max:255']
        // ]);
        $this->userService->updateAuthName($request->authName);
        return redirect()->back()->with('message', __('public.Update success'));
    }
    // public function updateIntroduce(Request $request)
    // {
    //     // dd($request->all());
    //     $request->validate([
    //         'gender' => ['in:male,female,other'], // Xác thực giá trị giới tính
    //         'education' => ['nullable'],
    //         'hometown' => ['nullable'],
    //         'birthDay' => ['date', 'before:today', 'nullable']
    //     ]);
    //     User::find(Auth::id())->update([
    //         'birth_day' => $request->birthDay,
    //         'gender' => $request->gender,
    //         'country' => $request->country,
    //         'education' => $request->education,
    //     ]);
    //     return redirect()->back()->with('message', __('public.Update success'));
    // }
}
