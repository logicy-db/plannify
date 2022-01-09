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
     * Profile listing view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('profiles.index', ['profiles' => Profile::all()]);
    }

    /**
     * Show the form for creating a new profile.
     */
    public function create()
    {
        return view('profiles.create');
    }

    /**
     * Store a newly created profile.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone_number' => 'required|max:255',
            'address' => 'required|max:255',
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

        return redirect()->route('events.index')->with('success', 'You have configured your profile!');
    }

    /**
     * Display the specified profile.
     *
     * @param Profile $profile
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Profile $profile)
    {
        return view('profiles.show', ['profile' => $profile]);
    }

    /**
     * Update the specified profile.
     *
     * @param Request $request
     * @param Profile $profile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Profile $profile)
    {
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone_number' => 'required|max:255',
            'address' => 'required|max:255',
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

        return back()->with('success', 'Profile has been updated.');
    }

    /*
     * Search profiles by firstname and lastname.
     */
    public function search(Request $request) {
        $this->authorize('viewAny', Profile::class);

        $request->validate([
            'first_name' => 'max:50',
            'last_name' => 'max:50',
        ]);

        $firstName = $request->first_name;
        $lastName = $request->last_name;

        $profiles = Profile::where('first_name', 'like', "%$firstName%")
            ->where('last_name', 'like', "%$lastName%")
            ->get();

        return view('profiles.search', ['profiles' => $profiles]);
    }
}
