<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{route('admin.home')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{asset('img/world_vision.png')}}" alt="" height="25">
            </span>
            <span class="logo-lg">
                <img src="{{asset('img/world_vision.png')}}" alt="" height="50">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{route('admin.home')}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{asset('img/favicons/favicon-32x32.png')}}" alt="" height="20">
            </span>
            <span class="logo-lg">
                <img src="{{asset('img/world_vision.png')}}" alt="" height="45">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('admin.home')}}">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li> <!-- end Dashboard Menu -->

                {{-- Start User Management --}}
                @role('admin')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarAuth">
                        <i class="ri-account-circle-line"></i>
                        <span data-key="t-usermanagement">{{ 'User Management' }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarAuth">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('admin.permissions.index')}}" class="nav-link">{{ 'Permissions'
                                    }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.roles.index')}}" class="nav-link">{{ 'Role' }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.users.index')}}" class="nav-link">{{ 'User' }}</a>
                            </li>

                            <li class="nav-item">
                                <a href="" class="nav-link">
                                    {{'Password Reset'}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endrole
                {{-- End User Management --}}
                
                {{-- Start Category Color Management --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarCategoryColor" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarCategoryColor">
                        <i class=" mdi mdi-invert-colors"></i>
                        <span data-key="t-country">{{ 'Category Color' }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarCategoryColor">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('admin.category-color.index')}}" class="nav-link">{{ 'List' }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.category-color.create')}}" class="nav-link">{{ 'Create' }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- End Category Color Management --}}

                {{-- Start Country Management --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarCountryMain" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarCountryMain">
                        <i class="mdi mdi-earth"></i>
                        <span data-key="t-country">{{ 'Country Management' }}</span>
                    </a>
                    <div class="collapse menu-dropdown" style="margin-left:20px;" id="sidebarCountryMain">
                        <a class="nav-link menu-link" href="#sidebarCountry" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarCountry">
                            <span data-key="t-country">{{ 'Country' }}</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarCountry">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{route('admin.country.index')}}" class="nav-link">{{ 'List' }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.country.create')}}" class="nav-link">{{ 'Create' }}</a>
                                </li>
                            </ul>
                        </div>
                        <a class="nav-link menu-link" href="#sidebarCountryData" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarCountryData">
                            <span data-key="t-country">{{ 'Country Data' }}</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarCountryData">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{route('admin.country-data.index')}}" class="nav-link">{{ 'List' }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.country-data.create')}}" class="nav-link">{{ 'Create' }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.country-data.bulk')}}" class="nav-link">{{ 'Bulk Import' }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                {{-- End Country Management --}}

                {{-- Start Sub Country Management --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarSubCountryMain" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarSubCountryMain">
                        <i class="ri-flag-line"></i>
                        <span data-key="t-country">{{ 'Sub Country Management' }}</span>
                    </a>
                    <div class="collapse menu-dropdown" style="margin-left:20px;" id="sidebarSubCountryMain">
                        <a class="nav-link menu-link" href="#sidebarSubCountry" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarSubCountry">
                            <span data-key="t-country">{{ 'Sub Country' }}</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSubCountry">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{route('admin.sub-country.index')}}" class="nav-link">{{ 'List' }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.sub-country.create')}}" class="nav-link">{{ 'Create' }}</a>
                                </li>
                            </ul>
                        </div>
                        <a class="nav-link menu-link" href="#sidebarSubCountryData" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarSubCountryData">
                            <span data-key="t-country">{{ 'Sub Country Data' }}</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSubCountryData">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{route('admin.sub-country-data.index')}}" class="nav-link">{{ 'List' }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.sub-country-data.create')}}" class="nav-link">{{ 'Create' }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.sub-country-data.bulk')}}" class="nav-link">{{ 'Bulk Import' }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                {{-- End Sub Country Management --}}
                
                {{-- Start Indicator Management --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarIndicators" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarCountry">
                        <i class=" ri-line-chart-line"></i>
                        <span data-key="t-country">{{ 'Indicators' }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarIndicators">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('admin.indicator.index')}}" class="nav-link">{{ 'List' }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.indicator.create')}}" class="nav-link">{{ 'Create' }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- End Indicator Management --}}

                {{-- Start Source Management --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarSource" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarSource">
                        <i class="mdi mdi-source-branch"></i>
                        <span data-key="t-country">{{ 'Source' }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarSource">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('admin.source.index')}}" class="nav-link">{{ 'List' }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.source.create')}}" class="nav-link">{{ 'Create' }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- End Indicator Management --}}

                {{-- <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarProject" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarProject">
                        <i class="ri-pages-line"></i>
                        <span data-key="t-country">{{ 'Project' }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarProject">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="" class="nav-link">{{ 'List' }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="" class="nav-link">{{ 'Create' }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="" class="nav-link">{{ 'Import'
                                    }}</a>
                            </li>
                        </ul>
                    </div>
                </li> --}}

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>