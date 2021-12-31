<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

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
        return view('events.index', [
            'plannedEvents' => Event::where('starting_time', '>', now())->get()
                ->sortBy('starting_time'),
            'pastEvents' => Event::where('starting_time', '<=', now())->get()
                ->sortByDesc('starting_time'),
        ]);
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
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Minimal/maximum value of the event starting time
        $min = date('Y-m-d\TH:i');
        $max = date('Y-m-d\TH:i', strtotime('+100 years'));

        //
        $request->validate([
            'preview' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'description' => 'required',
            'location' => 'required',
            'meeting_point' => 'required',
            'starting_time' => "required|date_format:\"Y-m-d\TH:i\"|after:{$min}|before:{$max}",
            'ending_time' => "required|date_format:\"Y-m-d\TH:i\"|after:starting_time",
            'attendees_limit' => 'required|numeric',
        ]);

        $event = new Event();
        $imagePath = $request->file('preview')->store(Event::IMAGE_FOLDER, 'public');

        $event->name = $request->name;
        $event->description = $request->description;
        $event->meeting_point = $request->meeting_point;
        $event->status_id = EventStatus::STATUS_ACTIVE;
        $event->location = $request->location;
        $event->starting_time = date('Y-m-d H:i:s', strtotime($request->starting_time));
        $event->ending_time = date('Y-m-d H:i:s', strtotime($request->ending_time));
        $event->attendees_limit = $request->attendees_limit;
        $event->preview = $imagePath;
        $event->save();

        return redirect()->route('events.show', $event)
            ->with('success', 'New event has been created!');
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
        return view('events.edit', ['event' => $event]);
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
        // Minimal/maximum value of the event starting time
        $min = date('Y-m-d\TH:i');
        $max = date('Y-m-d\TH:i', strtotime('+100 years'));

        $request->validate([
            'preview' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'description' => 'required',
            'location' => 'required',
            'meeting_point' => 'required',
            'starting_time' => "required|date_format:\"Y-m-d\TH:i\"|after:{$min}|before:{$max}",
            'ending_time' => "required|date_format:\"Y-m-d\TH:i\"|after:starting_time",
            'attendees_limit' => 'required|numeric',
        ]);

        $event->name = $request->name;
        $event->description = $request->description;
        $event->meeting_point = $request->meeting_point;
        $event->location = $request->location;
        $event->starting_time = date('Y-m-d H:i:s', strtotime($request->starting_time));
        $event->ending_time = date('Y-m-d H:i:s', strtotime($request->ending_time));
        $event->attendees_limit = $request->attendees_limit;

        if ($request->file('preview')) {
            $storage = Storage::disk('public');

            if ($storage->exists($event->preview)) {
                $storage->delete($event->preview);
            }

            $event->preview = $request->file('preview')->store(Event::IMAGE_FOLDER, 'public');
        }

        $event->save();

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Event has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event was deleted');
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
    public function cancelParticipation(Event $event, User $user = null) {
        if (is_null($user)) {
            $this->authorize('cancelParticipation', $event);

            $user = $event->usersGoing()->firstWhere('id', Auth::id());
            $msg = 'You have canceled your participation in the event';
        } else {
            $this->authorize('cancelUserParticipation', [$event, $user]);

            $user = $event->usersGoing()->firstWhere('id', $user->id);
            $msg = "You have canceled {$user->getFullname()} participation in the event";
        }

        $user->pivot->participation_type_id = Event::USER_CANCELED;
        $user->pivot->save();

        // TODO: inform user by email
        // After user cancels their participation, sign in for the event first user from event queue
        if ($queuedUser = $event->usersQueued()->first()) {
            $queuedUser->pivot->participation_type_id = Event::USER_GOING;
            $queuedUser->pivot->save();
        }

        return back()->with('success', $msg);
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
    public function cancelQueue(Event $event, User $user = null) {
        if (is_null($user)) {
            $this->authorize('cancelQueue', $event);

            $event->users()->detach(Auth::id());
            $msg = 'You have exited the event queue.';
        } else {
            $this->authorize('cancelUserQueue', [$event, $user]);

            $event->users()->detach($user->id());
            $msg = "You have removed {$user->getFullname()} from the event queue.";
        }

        return back()->with('success', $msg);
    }

    /**
     * Allow user to participate when previous participation was canceled.
     */
    public function allowParticipation(Event $event, User $user) {
        $this->authorize('allowParticipation', [$event, $user]);
        $event->users()->detach($user->id);

        return back()->with('success', "You have allowed {$user->getFullname()} to participate in the event");
    }

    /*
     * Search profiles by specified parameters.
     */
    public function search(Request $request) {
        $this->authorize('restore');
//        $first_name = $request->first_name;
//        $profiles = Profile::where('first_name', 'like', "%$first_name%")
//            ->get();
        // TODO: these are not profiles!
        return view('profiles.search', ['profiles' =>[]]);
    }
}
