<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Mapping of the profile policies
        $this->authorizeResource(Profile::class, 'profile');
    }

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
        $image->move(
            public_path(Profile::IMAGE_FOLDER),
            $imageName
        );

        $profile = new Profile();
        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->phone_number = $request->phone_number;
        $profile->address = $request->address;
        $profile->job_position = $request->job_position;
        $profile->avatar = $imageName;

        Auth::user()->profile()->save($profile);

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
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->phone_number = $request->phone_number;
        $profile->address = $request->address;

        if ($image = $request->file('avatar')) {
            $imageName = sprintf('%s_%s.%s', Auth::id(), time(), $image->getClientOriginalExtension());
            $image->move(
                public_path(Profile::IMAGE_FOLDER),
                $imageName
            );

            $profile->avatar = $imageName;
        }

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
        // TODO: refactor
        $first_name = $request->first_name;
        $profiles = Profile::where('first_name', 'like', "%$first_name%")
            ->get();
        return view('profiles.search', ['profiles' => $profiles]);
    }
}
