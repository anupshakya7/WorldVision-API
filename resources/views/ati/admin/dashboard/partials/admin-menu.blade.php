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
                    <a class="nav-link menu-link" href="{{route('admin.ati.home')}}">
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
                                <a href="{{route('admin.ati.permissions.index')}}" class="nav-link">{{ 'Permissions'
                                    }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.ati.roles.index')}}" class="nav-link">{{ 'Role' }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.ati.users.index')}}" class="nav-link">{{ 'User' }}</a>
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
                                    <a href="{{route('admin.ati.country.index')}}" class="nav-link">{{ 'List' }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.ati.country.create')}}" class="nav-link">{{ 'Create' }}</a>
                                </li>
                            </ul>
                        </div>
                        <a class="nav-link menu-link" href="#sidebarCountryData" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarCountryData">
                            <span data-key="t-country">{{ 'Country Data' }}</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarCountryData" style="margin-left: 15px;">
                            {{-- Upcomming Elections --}}
                            <a class="nav-link menu-link" href="#sidebarCountryDataElection" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarCountryDataElection">
                                <span data-key="t-country">{{ 'Upcoming Elections' }}</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarCountryDataElection">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{route('admin.ati.elections.index')}}" class="nav-link">{{ 'List' }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('admin.ati.elections.create')}}" class="nav-link">{{ 'Create' }}</a>
                                    </li>
                                </ul>
                            </div>

                            {{-- Historical Democratic Disruptions --}}
                            <a class="nav-link menu-link" href="#sidebarCountryDataDisruptions" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarCountryDataDisruptions">
                            <span data-key="t-country">{{ 'Historical Disruptions' }}</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarCountryDataDisruptions">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{route('admin.ati.disruptions.index')}}" class="nav-link">{{ 'List' }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('admin.ati.disruptions.create')}}" class="nav-link">{{ 'Create' }}</a>
                                    </li>
                                </ul>
                            </div>

                            {{-- Domain Score --}}
                            <a class="nav-link menu-link" href="#sidebarCountryDataDomain" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarCountryDataDomain">
                            <span data-key="t-country">{{ 'Domain Score' }}</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarCountryDataDomain">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{route('admin.ati.domain-score.index')}}" class="nav-link">{{ 'List' }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('admin.ati.domain-score.create')}}" class="nav-link">{{ 'Create' }}</a>
                                    </li>
                                </ul>
                            </div>

                            {{-- Indicator Score --}}
                            <a class="nav-link menu-link" href="#sidebarCountryDataIndicator" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarCountryDataIndicator">
                            <span data-key="t-country">{{ 'Indicator Score' }}</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarCountryDataIndicator">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{route('admin.ati.indicator-score.index')}}" class="nav-link">{{ 'List' }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('admin.ati.indicator-score.create')}}" class="nav-link">{{ 'Create' }}</a>
                                    </li>
                                </ul>
                            </div>

                            {{-- Voice Of People --}}
                            <a class="nav-link menu-link" href="#sidebarCountryDataVoice" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarCountryDataVoice">
                            <span data-key="t-country">{{ 'Voice Of People' }}</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarCountryDataVoice">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{route('admin.ati.voice-people.index')}}" class="nav-link">{{ 'List' }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('admin.ati.voice-people.create')}}" class="nav-link">{{ 'Create' }}</a>
                                    </li>
                                </ul>
                            </div>

                            {{-- Bulk Import --}}
                            <a class="nav-link menu-link" href="#sidebarCountryDataBulkImport" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarCountryDataBulkImport">
                            <span data-key="t-country">{{ 'Bulk Import' }}</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarCountryDataBulkImport">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{route('admin.ati.country-data.bulkInsert','indicator-score')}}" class="nav-link">{{ 'Bulk Import' }}</a>
                                    </li>
                                </ul>
                            </div>
                            {{-- <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{route('admin.ati.country-data.index')}}" class="nav-link">{{ 'List' }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.ati.country-data.create')}}" class="nav-link">{{ 'Create' }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.ati.country-data.bulk')}}" class="nav-link">{{ 'Bulk Import' }}</a>
                                </li>
                            </ul> --}}
                        </div>
                    </div>
                </li>
                {{-- End Country Management --}}

                
                {{-- Start Indicator Management --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarIndicators" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarCountry">
                        <i class=" ri-line-chart-line"></i>
                        <span data-key="t-country">{{ 'Domain & Indicators' }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarIndicators">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('admin.ati.indicator.index')}}" class="nav-link">{{ 'List' }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.ati.indicator.create')}}" class="nav-link">{{ 'Create' }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- End Indicator Management --}}

                {{-- Start Source Management --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarSources" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarSources">
                        <i class="mdi mdi-source-branch"></i>
                        <span data-key="t-country">{{ 'Source' }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarSources">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('admin.ati.ati-source.index')}}" class="nav-link">{{ 'List' }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.ati.ati-source.create')}}" class="nav-link">{{ 'Create' }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- End Source Management --}}

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>