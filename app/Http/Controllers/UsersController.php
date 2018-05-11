<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct ()
    {
        $this->middleware ('auth');
    }

    public function show(User $user) {
//        return view('users.show', compact('user'));
        return view ('users.show', compact ('user'));
    }

    public function edit(User $user) {
        return view ('users.edit', compact ('user'));
    }

    public function update(UserRequest $request, ImageUploadHandler $uploadHandler,User $user) {

        $data = $request->all ();

        /**
         *  测试uploadedFile方法
        if($request->hasFile ('avatar')) {

            ddd( "guessExtension: " . $request->avatar->guessExtension());
            ddd("guessClientExtension: " . $request->avatar->guessClientExtension());
            ddd('getClientOriginalExtension: ' . $request->avatar->getClientOriginalExtension());
            ddd('getMimeType: ' . $request->avatar->getMimeType());
            ddd('getClientMimeType: ' . $request->avatar->getClientMimeType());
            ddd('getClientSize: ' . $request->avatar->getClientSize());
            ddd('getClientOriginalName: ' . $request->avatar->getClientOriginalName());
            ddd('getError: ' . $request->avatar->getError());
            ddd("getMaxFilesize: " . $request->avatar->getMaxFilesize());
            ddd('path: ' . $request->avatar->path());
        }
         * */


        if($request->avatar) {
            $result = $uploadHandler->save ($request->avatar, 'avatar', $user->id, 362);

            if($result) $data['avatar'] = $result['path'];
        }

//        ddd ($request->avatar);die;
        $user->update ($data);

        return redirect ()->route ('users.show', $user->id)->with ('success','个人资料更新成功');
    }
}
