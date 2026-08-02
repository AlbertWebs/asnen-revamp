<div class="flex h-full flex-col">
    <div class="flex h-16 items-center border-b border-charcoal-800 px-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-lg font-bold tracking-tight text-white">
            <span class="inline-flex rounded bg-white px-1.5 py-1">
                <img src="{{ asset('brand/logo.png') }}" alt="" class="h-8 w-auto" aria-hidden="true">
            </span>
            <span class="font-normal text-brand-300">Admin</span>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4" aria-label="Modules">
        <ul class="space-y-1 text-sm">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">
                    Dashboard
                </a>
            </li>

            @can('pages.view')
            <li><a href="{{ route('admin.pages.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.pages.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Pages</a></li>
            @endcan

            @can('programs.view')
            <li><a href="{{ route('admin.programs.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.programs.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Programs</a></li>
            @endcan

            @can('impact_stories.view')
            <li><a href="{{ route('admin.impact-stories.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.impact-stories.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Impact Stories</a></li>
            @endcan

            @can('impact_metrics.view')
            <li><a href="{{ route('admin.impact-metrics.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.impact-metrics.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Impact Metrics</a></li>
            @endcan

            @can('regions.view')
            <li><a href="{{ route('admin.regions.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.regions.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Regions</a></li>
            @endcan

            @can('events.view')
            <li><a href="{{ route('admin.events.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.events.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Events</a></li>
            @endcan

            @can('webinars.view')
            <li><a href="{{ route('admin.webinars.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.webinars.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Webinars</a></li>
            @endcan

            @can('publications.view')
            <li><a href="{{ route('admin.publications.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.publications.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Publications</a></li>
            @endcan

            @can('articles.view')
            <li><a href="{{ route('admin.articles.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.articles.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Articles</a></li>
            @endcan

            @can('partners.view')
            <li><a href="{{ route('admin.partners.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.partners.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Partners</a></li>
            @endcan

            @can('team_members.view')
            <li><a href="{{ route('admin.team-members.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.team-members.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Team</a></li>
            @endcan

            @can('media.view')
            <li><a href="{{ route('admin.media.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.media.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Media</a></li>
            @endcan

            @can('galleries.view')
            <li><a href="{{ route('admin.galleries.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.galleries.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Galleries</a></li>
            @endcan

            @can('faqs.view')
            <li><a href="{{ route('admin.faqs.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.faqs.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">FAQs</a></li>
            @endcan

            @can('donations.view')
            <li><a href="{{ route('admin.donation-campaigns.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.donation-campaigns.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Donations</a></li>
            @endcan

            @can('form_submissions.view')
            <li><a href="{{ route('admin.form-submissions.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.form-submissions.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Form Submissions</a></li>
            @endcan

            @can('newsletter.view')
            <li><a href="{{ route('admin.newsletter-subscribers.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.newsletter-subscribers.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Newsletter</a></li>
            @endcan

            @can('navigation.view')
            <li><a href="{{ route('admin.navigation.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.navigation.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Navigation</a></li>
            @endcan

            @can('announcements.view')
            <li><a href="{{ route('admin.announcements.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.announcements.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Announcements</a></li>
            @endcan

            @can('redirects.view')
            <li><a href="{{ route('admin.redirects.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.redirects.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Redirects</a></li>
            @endcan

            @can('settings.update')
            <li><a href="{{ route('admin.settings.edit', 'features') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.settings.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Settings</a></li>
            @endcan

            @can('users.view')
            <li><a href="{{ route('admin.users.index') }}" class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('admin.users.*') ? 'bg-forest-800 text-white' : 'text-charcoal-200 hover:bg-charcoal-800 hover:text-white' }}">Users</a></li>
            @endcan
        </ul>
    </nav>
</div>
