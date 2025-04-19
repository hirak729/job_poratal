<div class="card border-0 shadow mb-4 p-3">
    <div class="s-body text-center mt-3">
        @if (Auth::user()->image != null)
            <img src="{{ asset('storage/profile_pic/' . $user->image) }}" alt="avatar"  class="rounded-circle img-fluid" style="width: 100px;">
        @endif
        <h5 class="mt-3 pb-0">{{ Auth::user()->name }}</h5>
    </div>
</div>
<div class="card account-nav border-0 shadow mb-4 mb-lg-0">
    <div class="card-body p-0">
        <ul class="list-group list-group-flush ">
            <li class="list-group-item d-flex justify-content-between p-3">
                <a href="#">Dashboard</a>
            </li>
            <li class="list-group-item d-flex justify-content-between p-3">
                <a href="{{ route('admin.users') }}">Users</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{ route('admin.jobs') }}">Jobs</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{ route('admin.applications') }}">Jobs Applications</a>
            </li>                                                     
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="#">Logout</a>
            </li>                                                        
        </ul>
    </div>
</div>