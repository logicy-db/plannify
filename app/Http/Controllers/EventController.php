<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\ParticipationType;
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
     * Event listing view.
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
     * Show the form for creating a new event.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created event.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Minimal/maximum value of the event starting time
        $min = date('Y-m-d\TH:i');
        $max = date('Y-m-d\TH:i', strtotime('+100 years'));

        $request->validate([
            'preview' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|max:255',
            'description' => 'required|max:500',
            'location' => 'required|max:255',
            'meeting_point' => 'required|max:255',
            'starting_time' => "required|date_format:\"Y-m-d\TH:i\"|after:{$min}|before:{$max}|before:ending_time",
            'ending_time' => "required|date_format:\"Y-m-d\TH:i\"|after:starting_time",
            'attendees_limit' => 'required|numeric|max:500',
        ]);

        $event = new Event();
        $imagePath = $request->file('preview')->store(Event::IMAGE_FOLDER, 'public');

        $event->name = $request->name;
        $event->description = $request->description;
        $event->meeting_point = $request->meeting_point;
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
     * Display the specified event.
     *
     * @param Event $event
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Event $event)
    {
        return view('events.show', ['event' => $event]);
    }

    /**
     * Show the form for editing the event.
     *
     * @param Event $event
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Event $event)
    {
        return view('events.edit', ['event' => $event]);
    }

    /**
     * Update the specified event.
     *
     * @param Request $request
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Event $event)
    {
        // Minimal/maximum value of the event starting time
        $min = date('Y-m-d\TH:i');
        $max = date('Y-m-d\TH:i', strtotime('+100 years'));

        $request->validate([
            'preview' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|max:255',
            'description' => 'required|max:500',
            'location' => 'required|max:255',
            'meeting_point' => 'required|max:255',
            'starting_time' => "required|date_format:\"Y-m-d\TH:i\"|after:{$min}|before:{$max}",
            'ending_time' => "required|date_format:\"Y-m-d\TH:i\"|after:starting_time",
        ]);

        $event->name = $request->name;
        $event->description = $request->description;
        $event->meeting_point = $request->meeting_point;
        $event->location = $request->location;
        $event->starting_time = date('Y-m-d H:i:s', strtotime($request->starting_time));
        $event->ending_time = date('Y-m-d H:i:s', strtotime($request->ending_time));

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
            ->with('success', 'Event has been updated.');
    }

    /**
     * Remove the specified event.
     *
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $event)
    {
        $event->delete();
        $storage = Storage::disk('public');

        if ($storage->exists($event->preview)) {
            $storage->delete($event->preview);
        }

        return redirect()->route('events.index')->with('success', 'Event was successfully deleted.');
    }

    /**
     * Signing user in for the event.
     *
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function participate(Event $event) {
        $this->authorize('participate', $event);

        $event->users()->attach(
            Auth::id(), ['participation_type_id' => Event::USER_GOING]
        );

        return back()->with('success', 'You have been singed for the event');
    }

    /**
     * Cancels user participation in the event.
     */
    public function cancelParticipation(Event $event, User $user = null) {
        if (is_null($user)) {
            $this->authorize('cancelParticipation', $event);

            $user = $event->usersGoing()->firstWhere('id', Auth::id());
            $msg = 'You have canceled your participation in the event.';
        } else {
            $this->authorize('cancelUserParticipation', [$event, $user]);

            $user = $event->usersGoing()->firstWhere('id', $user->id);
            $msg = "You have canceled user participation in the event.";
        }

        $user->pivot->participation_type_id = Event::USER_CANCELED;
        $user->pivot->save();

        // After user cancels their participation, sign in for the event first user from event queue
        if ($queuedUser = $event->usersQueued()->first()) {
            $queuedUser->pivot->participation_type_id = Event::USER_GOING;
            $queuedUser->pivot->save();
        }

        return back()->with('success', $msg);
    }

    /**
     * Adds user to the event queue.
     *
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function queue(Event $event) {
        $this->authorize('queue', $event);

        $event->users()->attach(
            Auth::id(), ['participation_type_id' => Event::USER_QUEUED]
        );

        return back()->with('success', 'You have been put in the event queue.');
    }

    /**
     * Remove user from the queue.
     *
     * @param Event $event
     * @param User|null $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function cancelQueue(Event $event, User $user = null) {
        if (is_null($user)) {
            $this->authorize('cancelQueue', $event);

            $event->users()->detach(Auth::id());
            $msg = 'You have exited the event queue.';
        } else {
            $this->authorize('cancelUserQueue', [$event, $user]);

            $event->users()->detach($user->id);
            $msg = "You have removed user from the event queue.";
        }

        return back()->with('success', $msg);
    }

    /**
     * Allow user to participate when previous participation was canceled.
     *
     * @param Event $event
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function allowParticipation(Event $event, User $user) {
        $this->authorize('allowParticipation', [$event, $user]);
        $event->users()->detach($user->id);

        return back()->with('success', "You have allowed user to participate in the event.");
    }

    /**
     * Search for planned events by name.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function search(Request $request) {
        $this->authorize('viewAny', Event::class);

        $request->validate([
            'event_name' => 'max:50',
        ]);

        $events = Event::where('name', 'like', "%$request->event_name%")
            ->where('starting_time', '>', now())->get()
            ->sortBy('starting_time');

        return view('events.search', ['events' => $events]);
    }
}
