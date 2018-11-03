<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" class="img-circle" src="/images/{{ auth()->id() }}.jpg" 
                        onerror="if (this.src != '/images/error.jpg') this.src = '/images/error.jpg';">  
                             
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear">
                            <span class="block m-t-xs">
                                <strong class="font-bold">{{ auth()->user()->name }}</strong>
                            </span> <span class="text-muted text-xs block">{{ __('Admin') }}<b class="caret"></b></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="{{ route('members.show', auth()->id()) }}">Profile</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Mailbox</a></li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ route('logout') }}" 
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                {{ __('Log out') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="logo-element">
                    FL+
                </div>
            </li>
            <li class="{{ isActiveRoute('home')}}">
                <a href="{{ route('home') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Home</span></a>
            </li>

            <li class="{{ isActiveRoute('members.index') }} {{ isActiveRoute('members.create') }} {{ isActiveRoute('members.show') }}">
                <a href=""><i class="fa fa-flask"></i> <span class="nav-label">Team</span>
                    <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ isActiveRoute('members.index') }} {{ isActiveRoute('members.show') }}"><a href="{{ route('members.index') }}">Members</a></li>
                    <li class="{{ isActiveRoute('members.create') }}"><a href="{{ route('members.create')}}">Add new</a></li>
                </ul>
            </li>             
            
            <li class="{{ isActiveRoute('practice.index') }} {{ isActiveRoute('practice.create') }} {{ isActiveRoute('practice.show') }} {{ isActiveRoute('vets.show') }}">
                <a href="index.html"><i class="fa fa-ambulance"></i> <span class="nav-label">Vets</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ isActiveRoute('practice.index') }} {{ isActiveRoute('practice.show') }}"><a href="{{ route('practice.index') }}">Practices</a></li>
                    <li class="{{ isActiveRoute('practice.create') }}"><a href="{{ route('practice.create') }}">Add new</a></li>
                </ul>
            </li>            

            <li class="{{ isActiveRoute('files.index') }} {{ isActiveRoute('files.create') }}">
                <a href="{{ route('files.index') }}"><i class="fa fa-file"></i> <span class="nav-label">Files</span></a>
            </li>
        </ul>

    </div>
</nav>