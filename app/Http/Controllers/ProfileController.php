<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('profiles.index', ['profiles' => Profile::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('profiles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // TODO: remove access to contoller if user already has a profile (middleware?)
        if ($user->profile) {
            return back()->with('error', 'Only one profile can be created per user.');
        }

        // TODO: make all fields required | validation of image does not work
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required|numeric',
            'address' => 'required',
            'job_position' => 'required',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('avatar');
        $imageName = sprintf('%s_%s.%s', Auth::id(), time(), $image->getClientOriginalExtension());
        // TODO: make a const path in Profile model
        // TODO: add a default picture to the public folder
        $destinationPath = public_path('/avatars');
        $image->move($destinationPath, $imageName);

        $profile = new Profile();
        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->phone_number = $request->phone_number;
        $profile->address = $request->address;
        $profile->job_position = $request->job_position;
        $profile->avatar = $imageName;

        $user->profile()->save($profile);

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param Profile $profile
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Profile $profile)
    {
        return view('profiles.show', ['profile' => $profile]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Profile $profile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Profile $profile)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        // TODO: add image
        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->phone_number = $request->phone_number;
        $profile->address = $request->address;
        $profile->save();

        return back()->with('success', sprintf('User %s was updated', $profile->user->getFullname()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }

    /*
     * Search profiles by specified parameters.
     */
    public function search(Request $request) {
        $first_name = $request->first_name;
        $profiles = Profile::where('first_name', 'like', "%$first_name%")
            ->get();
        return view('profiles.search', ['profiles' => $profiles]);
    }
}
