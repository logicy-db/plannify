<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Mapping of the profile policies
        $this->authorizeResource(Event::class, 'event');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('events.index', ['events' => Event::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'preview' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'description' => 'required',
            'location' => 'required',
            'starting_time' => 'required|date_format:H:i',
            'starting_date' => 'required|date_format:Y-m-d',
            'attendees_limit' => 'required|numeric',
        ]);

        // TODO: Rework
        $image = $request->file('preview');
        $imageName = sprintf('%s_%s.%s', uniqid(), Auth::id(), time(), $image->getClientOriginalExtension());
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
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return view('events.show', ['event' => $event]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }

    /**
     * Signing user in for the event.
     */
    public function participate(Event $event) {
        $this->authorize('participate', $event);

        $event->users()->attach(
            Auth::id(), ['participation_type_id' => Event::USER_GOING]
        );

        return back()->with('success', 'You have been singed for the event');
    }

    /**
     * Signing user in for the event.
     */
    public function cancelParticipation(Event $event) {
        $this->authorize('cancelParticipation', $event);

        $user = $event->usersGoing()->firstWhere('id', Auth::id());
        $user->pivot->participation_type_id = Event::USER_CANCELED;
        $user->pivot->save();
        if ($queuedUser = $event->usersQueued()->first()) {
            $queuedUser->pivot->participation_type_id = Event::USER_GOING;
            $queuedUser->pivot->save();
        }

        return back()->with('success', 'You have canceled your participation in the event');
    }

    /**
     * Signing user in for the event.
     */
    public function queue(Event $event) {
        $this->authorize('queue', $event);

        $event->users()->attach(
            Auth::id(), ['participation_type_id' => Event::USER_QUEUED]
        );

        return back()->with('success', 'You have been put in the event queue.');
    }

    /**
     * Signing user in for the event.
     */
    public function cancelQueue(Event $event) {
        $this->authorize('cancelQueue', $event);

        $event->users()->detach(Auth::id());

        return back()->with('success', 'You have exited the event queue.');
    }
}
