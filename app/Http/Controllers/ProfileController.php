<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'phone_number' => 'required',
            'address' => 'required',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $profile = new Profile();
        $imagePath = $request->file('avatar')->store(Profile::IMAGE_FOLDER, 'public');

        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->phone_number = $request->phone_number;
        $profile->address = $request->address;
        $profile->avatar = $imagePath;

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

        if ($request->file('avatar')) {
            $storage = Storage::disk('public');

            if ($storage->exists($profile->avatar)) {
                $storage->delete($profile->avatar);
            }

            $profile->avatar = $request->file('avatar')->store(Profile::IMAGE_FOLDER, 'public');
        }

        $profile->save();

        return back()->with('success', sprintf('User %s was updated', $profile->user->getFullname()));
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
