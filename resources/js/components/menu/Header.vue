<template>
    <header class="h-14 bg-card flex justify-between items-center py-4 px-6 border-b border-gray-200">
        <div class="flex items-center">
          <button
            aria-label="open dashboard"
            @click="$parent.sidebarOpen = true"
            class="text-gray-500 focus:outline-none lg:hidden"
          >
            <svg
              class="h-6 w-6"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M4 6H20M4 12H20M4 18H11"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></path>
            </svg>
          </button>
          <div>
            <search-bar class="md:inline hidden" />
          </div>
        </div>
        <slot name="header-right-side"></slot>
      </header>
</template>