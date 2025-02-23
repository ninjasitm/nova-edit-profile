<?php

namespace SaintSystems\Nova\EditProfile\Http\Controllers;

use Storage;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class ToolController extends Controller
{
    public function index()
    {
        return response()->json([
            [
                "component" => "file-field",
                "prefixComponent" => true,
                "indexName" => __("Avatar"),
                "name" => __("Avatar"),
                "attribute" => "avatar",
                "value" => auth()->user()->avatar,
                "previewUrl" => Storage::url(auth()->user()->avatar),
                "panel" => null,
                "sortable" => false,
                "textAlign" => "left",
                "deletable" => true,
                "downloadable" => true,
                "acceptedType" => "image/*"
            ],
            [
                "component" => "text-field",
                "prefixComponent" => true,
                "indexName" => __("Name"),
                "name" => __("Name"),
                "attribute" => "name",
                "value" => auth()->user()->name,
                "panel" => null,
                "sortable" => false,
                "textAlign" => "left"
            ],
            [
                "component" => "text-field",
                "prefixComponent" => true,
                "indexName" => __("E-mail address"),
                "name" => __("E-mail address"),
                "attribute" => "email",
                "value" => auth()->user()->email,
                "panel" => null,
                "sortable" => false,
                "textAlign" => "left"
            ],
            [
                "component" => "password-field",
                "prefixComponent" => true,
                "indexName" => __("Password"),
                "name" => __("Password"),
                "attribute" => "password",
                "value" => null,
                "panel" => null,
                "sortable" => false,
                "textAlign" => "left"
            ],
            [
                "component" => "password-field",
                "prefixComponent" => true,
                "indexName" => __("Password Confirmation"),
                "name" => __("Password Confirmation"),
                "attribute" => "password_confirmation",
                "value" => null,
                "panel" => null,
                "sortable" => false,
                "textAlign" => "left"
            ]
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        request()->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'nullable|string|confirmed'
        ]);

        if(request()->filled('password')) {
            auth()->user()->update([
                'name' => request('name'),
                'email' => request('email'),
                'password' => Hash::make(request('password')),
            ]);
            if($file = $request->file('avatar')) {
                $avatar = Storage::url($file->store('avatars'));
                $user->update([
                    'avatar' => $avatar
                ]);
            }
        } else {
            auth()->user()->update(request()->only('name', 'email'));
        }

        return response()->json(__("Your profile has been updated!"));
    }
}
