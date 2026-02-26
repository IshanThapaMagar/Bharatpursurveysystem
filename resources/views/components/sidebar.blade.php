<aside class="fixed left-0 h-screen z-40" :class="miniSidebar ? 'w-16' : 'w-64'" aria-label="Sidebar"
    x-data="mainSidebarState()" @open-sidebar.window="miniSidebar = false" @close-sidebar.window="miniSidebar = true">
    <div class="h-full px-3 py-24 bg-[#03396c] dark:bg-[#03396c]">
        <!-- Toggle Button -->
        <div class="flex justify-end mb-4">
            <button type="button" @click="miniSidebar = !miniSidebar"
                class="flex justify-center items-center size-8 text-white hover:bg-blue-900 rounded-lg transition-colors">
                <svg class="size-4" :class="miniSidebar ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="3" rx="2" />
                    <path d="M15 3v18" />
                    <path d="m10 15-3-3 3-3" />
                </svg>
            </button>
        </div>

        <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-blue-900 dark:hover:bg-gray-700 group"
                    :class="miniSidebar ? 'justify-center' : ''">
                    <svg class="shrink-0 w-5 h-5 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-white dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                        <path
                            d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap" x-show="!miniSidebar">{{ __('Dashboard') }}</span>
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.survey-report') }}"
                    class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-blue-900 dark:hover:bg-gray-700 group"
                    :class="miniSidebar ? 'justify-center' : ''">
                    <svg class="shrink-0 w-5 h-5 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-white dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap" x-show="!miniSidebar">{{ __('Survey Report') }}</span>
                </a>
            </li>

            <li>
                <button type="button" @click="homedescription = !homedescription"
                    class="flex items-center w-full p-2 text-base text-white transition duration-75 rounded-lg group hover:bg-blue-900 dark:text-white dark:hover:bg-gray-700"
                    :class="miniSidebar ? 'justify-center' : ''" aria-controls="dropdown-example">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                    </svg>

                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap" x-show="!miniSidebar">
                        {{ __('House description') }}</span>
                    <svg class="w-3 h-3" x-show="!miniSidebar" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul x-show="homedescription && !miniSidebar" x-transition class="py-2 space-y-2">
                    <li>
                        <a href="{{ route('survey-responses.index') }}"
                            class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-blue-900 dark:text-white dark:hover:bg-gray-700">{{ __('Data details') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('house-description.create') }}"
                            class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-blue-900 dark:text-white dark:hover:bg-gray-700">{{ __('Data form') }}</a>
                    </li>
                    <li>
                        <a href=""
                            class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-blue-900 dark:text-white dark:hover:bg-gray-700">{{ __('Member details') }}</a>
                    </li>
                </ul>
            </li>

            @if(Auth::user()->isSuperAdmin() || Auth::user()->isWardAdmin())
            <li>
                <button type="button" @click="surveyform = !surveyform"
                    class="flex items-center w-full p-2 text-base text-white transition duration-75 rounded-lg group hover:bg-blue-900 dark:text-white dark:hover:bg-gray-700"
                    :class="miniSidebar ? 'justify-center' : ''" aria-controls="dropdown-example">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" />
                    </svg>

                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap" x-show="!miniSidebar">
                        {{ __('Survey builder') }}</span>
                    <svg class="w-3 h-3" x-show="!miniSidebar" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul x-show="surveyform && !miniSidebar" x-transition class="py-2 space-y-2">
                    <li>
                        <a href="{{ route('surveyform.index') }}"
                            class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-blue-900 dark:text-white dark:hover:bg-gray-700">{{ __('Manage sections') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('surveyform.create') }}"
                            class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-blue-900 dark:text-white dark:hover:bg-gray-700">{{ __('Add questions') }}</a>
                    </li>
                </ul>
            </li>
            @endif            @if(Auth::user()->isSuperAdmin() || Auth::user()->isWardAdmin())
            <li>
                <a href="{{ route('users.index') }}"
                    class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-blue-900 dark:hover:bg-gray-700 group"
                    :class="miniSidebar ? 'justify-center' : ''">
                    <svg class="shrink-0 w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h6a3.987 3.987 0 0 1 1.951 6.512A8.949 8.949 0 0 1 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap" x-show="!miniSidebar">{{ __('User Management') }}</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</aside>

<script>
    function mainSidebarState() {
        return {
            homedescription: false,
            surveyform: false,
            miniSidebar: false,
            init() {
                this.$watch('miniSidebar', (newVal) => {
                    window.dispatchEvent(new CustomEvent('sidebar-toggled', {
                        detail: {
                            miniSidebar: newVal
                        }
                    }));
                });
            }
        };
    }
</script>
