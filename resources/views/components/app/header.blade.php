<header class="sticky top-0 bg-customPrimaryDark border-b border-customGray z-30">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 -mb-px">

            <!-- Header: Left side -->
            <div class="flex xl:hidden">
                <!-- Hamburger button -->
                <button
                    @click.stop="sidebarOpen = !sidebarOpen"
                    aria-controls="sidebar"
                    :aria-expanded="sidebarOpen"
                >
                    <span class="sr-only">Open sidebar</span>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_10_1246)">
                            <path d="M3 3H21" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            <path d="M3 9H11.8" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            <path d="M3 15H21" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            <path d="M3 21H11.8" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_10_1246">
                                <rect width="24" height="24" fill="white"/>
                            </clipPath>
                        </defs>
                    </svg>
                </button>
            </div>

            <!-- Header: Right side -->
            <div class="flex justify-between space-x-3">
                <!-- User button -->
                <x-dropdown-profile align="right" />
            </div>

        </div>
    </div>
</header>
