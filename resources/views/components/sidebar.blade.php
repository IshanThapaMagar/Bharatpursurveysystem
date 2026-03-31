<aside class="fixed left-0 h-screen z-40" :class="miniSidebar ? 'w-16' : 'w-72'" aria-label="Sidebar"
    x-data="mainSidebarState()" @open-sidebar.window="miniSidebar = false" @close-sidebar.window="miniSidebar = true">
    <div class="h-full py-24 bg-[#03396c] dark:bg-[#03396c]">
        <!-- Toggle Button -->
        <div class="flex justify-end mb-4 px-3">
            <button type="button" @click="miniSidebar = !miniSidebar"
                class="flex justify-center items-center size-8 text-white hover:bg-blue-900 transition-colors">
                <svg class="size-4" :class="miniSidebar ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="3" rx="2" />
                    <path d="M15 3v18" />
                    <path d="m10 15-3-3 3-3" />
                </svg>
            </button>
        </div>

        <ul class="space-y-2 font-medium w-full">
            <li class="w-full">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center w-full px-4 py-2 text-white dark:text-white hover:bg-blue-900 dark:hover:bg-blue-800 group"
                    :class="miniSidebar ? 'justify-center' : ''">
                    <i class="fa-solid fa-sliders"></i>
                    <span class="flex-1 ms-3 whitespace-nowrap" x-show="!miniSidebar">{{ __('Dashboard') }}</span>
                </a>
            </li>

            <li class="w-full">
                <a href="{{ route('dashboard.survey-report') }}"
                    class="flex items-center w-full px-4 py-2 text-white dark:text-white hover:bg-blue-900 dark:hover:bg-blue-800 group"
                    :class="miniSidebar ? 'justify-center' : ''">
                    <i class="fa-solid fa-chart-column"></i>
                    <span class="flex-1 ms-3 whitespace-nowrap" x-show="!miniSidebar">{{ __('Survey Report') }}</span>
                </a>
            </li>

            <li class="w-full">
                <a href="{{ route('palika.index') }}"
                    class="flex items-center w-full px-4 py-2 text-white dark:text-white hover:bg-blue-900 dark:hover:bg-blue-800 group"
                    :class="miniSidebar ? 'justify-center' : ''">
                    <i class="fa-solid fa-building-columns"></i>
                    <span class="flex-1 ms-3 whitespace-nowrap" x-show="!miniSidebar">{{ __('Municipality') }}</span>
                </a>
            </li>


            <li class="w-full">
                <button type="button" @click="homedescription = !homedescription"
                    class="flex items-center w-full px-4 py-2 text-base text-white transition duration-75 group hover:bg-blue-900 dark:text-white dark:hover:bg-blue-800"
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
                <ul x-show="homedescription && !miniSidebar" x-transition class="py-2 space-y-2 w-full">
                    <li class="w-full">
                        <a href="{{ route('survey-responses.index') }}"
                            class="flex items-center w-full px-4 py-2 text-white transition duration-75 pl-11 group hover:bg-blue-900 dark:text-white dark:hover:bg-blue-800">{{ __('Data details') }}</a>
                    </li>
                    <li class="w-full">
                        <a href="{{ route('house-description.create') }}"
                            class="flex items-center w-full px-4 py-2 text-white transition duration-75 pl-11 group hover:bg-blue-900 dark:text-white dark:hover:bg-blue-800">{{ __('Data form') }}</a>
                    </li>
                    <!-- <li class="w-full">
                        <a href=""
                            class="flex items-center w-full px-4 py-2 text-white transition duration-75 pl-11 group hover:bg-blue-900 dark:text-white dark:hover:bg-blue-800">{{ __('Member details') }}</a>
                    </li> -->
                </ul>
            </li>

            <li class="w-full">
                <button type="button" @click="imp_sites = !imp_sites"
                    class="flex items-center w-full px-4 py-2 text-base text-white transition duration-75 group hover:bg-blue-900 dark:text-white dark:hover:bg-blue-800"
                    :class="miniSidebar ? 'justify-center' : ''" aria-controls="dropdown-example">
                    <i class="fa-solid fa-crosshairs"></i>

                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap" x-show="!miniSidebar">
                        {{ __('Important Sites') }}</span>
                    <svg class="w-3 h-3" x-show="!miniSidebar" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul x-show="imp_sites && !miniSidebar" x-transition class="py-2 space-y-2 w-full">
                    <li class="w-full">
                        <a href="{{ route('important-site.index') }}"
                            class="flex items-center w-full px-4 py-2 text-white transition duration-75 pl-11 group hover:bg-blue-900 dark:text-white dark:hover:bg-blue-800">{{ __('Site details') }}</a>
                    </li>
                    <li class="w-full">
                        <a href="{{ route('important-site.create') }}"
                            class="flex items-center w-full px-4 py-2 text-white transition duration-75 pl-11 group hover:bg-blue-900 dark:text-white dark:hover:bg-blue-800">{{ __('Site form') }}</a>
                    </li>
                </ul>
            </li>

            <li class="w-full">
                <button type="button" @click="trm = !trm"
                    class="flex items-center w-full px-4 py-2 text-base text-white transition duration-75 group hover:bg-blue-900 dark:text-white dark:hover:bg-blue-800"
                    :class="miniSidebar ? 'justify-center' : ''" aria-controls="dropdown-example">
                    <i class="fa-regular fa-map"></i>

                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap" x-show="!miniSidebar">
                        {{ __('Tole Resource Mapping') }}</span>
                    <svg class="w-3 h-3" x-show="!miniSidebar" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul x-show="trm && !miniSidebar" x-transition class="py-2 space-y-2 w-full">
                    <li class="w-full">
                        <a href="{{ route('resource-mapping.index') }}"
                            class="flex items-center w-full px-4 py-2 text-white transition duration-75 pl-11 group hover:bg-blue-900 dark:text-white dark:hover:bg-blue-800">{{ __('Resource mapping details') }}</a>
                    </li>
                    <li class="w-full">
                        <a href="{{ route('resource-mapping.create') }}"
                            class="flex items-center w-full px-4 py-2 text-white transition duration-75 pl-11 group hover:bg-blue-900 dark:text-white dark:hover:bg-blue-800">{{ __('Resource mapping form') }}</a>
                    </li>
                </ul>
            </li>

            @if (Auth::user()->isSuperAdmin() || Auth::user()->isWardAdmin())
                <li class="w-full">
                    <button type="button" @click="surveyform = !surveyform"
                        class="flex items-center w-full px-4 py-2 text-base text-white transition duration-75 group hover:bg-blue-900 dark:text-white dark:hover:bg-blue-800"
                        :class="miniSidebar ? 'justify-center' : ''" aria-controls="dropdown-example">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" />
                        </svg>

                        <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap" x-show="!miniSidebar">
                            {{ __('Survey builder') }}</span>
                        <svg class="w-3 h-3" x-show="!miniSidebar" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <ul x-show="surveyform && !miniSidebar" x-transition class="py-2 space-y-2 w-full">
                        <li class="w-full">
                            <a href="{{ route('surveyform.index') }}"
                                class="flex items-center w-full px-4 py-2 text-white transition duration-75 pl-11 group hover:bg-blue-900 dark:text-white dark:hover:bg-blue-800">{{ __('Manage sections') }}</a>
                        </li>
                        <li class="w-full">
                            <a href="{{ route('surveyform.create') }}"
                                class="flex items-center w-full px-4 py-2 text-white transition duration-75 pl-11 group hover:bg-blue-900 dark:text-white dark:hover:bg-blue-800">{{ __('Add questions') }}</a>
                        </li>
                    </ul>
                </li>
                @endif @if (Auth::user()->isSuperAdmin() || Auth::user()->isWardAdmin())
                    <li class="w-full">
                        <a href="{{ route('users.index') }}"
                            class="flex items-center w-full px-4 py-2 text-white dark:text-white hover:bg-blue-900 dark:hover:bg-blue-800 group"
                            :class="miniSidebar ? 'justify-center' : ''">
                            <svg class="shrink-0 w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h6a3.987 3.987 0 0 1 1.951 6.512A8.949 8.949 0 0 1 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap"
                                x-show="!miniSidebar">{{ __('User Management') }}</span>
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
            imp_sites: false,
            trm: false,
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
