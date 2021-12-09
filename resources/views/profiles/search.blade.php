<div class="profile-cards">
    @if(sizeof($profiles))
        @foreach($profiles as $profile)
            <div class="profile-card">
                <img class="avatar" src="{{ $profile->getAvatarUrl() }}" alt="Profile picture">
                <div class="full-name">{{ $profile->user->getFullname() }}</div>
                <div class="job-position">{{ $profile->getJobName() }}</div>
                <button class="see-more"><a href="{{ route('profiles.show', $profile) }}">More</a></button>
            </div>
        @endforeach
    @else
        No matching profiles where found.
    @endif
</div>
