<template>
    <div class="ml-auto mt-0.5 relative">
        <div class="cursor-pointer focus:outline-none focus:text-blue-600 select-none" 
            aria-label="Open lead teaser" v-on:click="open=!open" 
            v-on:keyup.enter="open=!open" role="button" tabIndex="0" 
            :aria-expanded="open ? 'true' : 'false'">
            <svg
            class="w-5 h-5 transition-transform transform"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            :class="{ 'rotate-180': open }"
            >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
            ></path>
            </svg>
        </div>
        <div class="bg-card absolute bg-white text-left py-2 px-5 flex flex-col shadow-md border border-gray-200 rounded-lg mt-2 origin-top-left right-0"
            :class="{ 'hidden': !open }">
            <span class="text-gray-400 text-xs font-medium capitalize">order</span>
            <button class="mt-1.5 text-left" 
                :class="{ 'text-blue-600': sortDirection=='asc'}"
                 v-on:click="changeDirection('asc')"
            >
                Ascending
            </button>
            <button class="mt-2 text-left" 
                :class="{ 'text-blue-600': sortDirection=='desc'}"
                v-on:click="changeDirection('desc')"
            >
                Descending
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            page:'',
        },
        data() {
            return {
                sortDirection:this.getDirectionParameterValue(),
                open:false,
            };
        },
        methods: {
            getDirectionParameterValue: function () {
                let urlParams = new URLSearchParams(window.location.search);
                let sortDirection;
                if(urlParams.get(this.page+'_sort_direction'))sortDirection = urlParams.get(this.page+'_sort_direction');
                return sortDirection??"desc"
            },
            changeDirection:function(direction){
                let url = new URL(window.location);
                let search_params = url.searchParams;
                search_params.set(this.page+'_sort_direction', direction);
                url.search = search_params.toString();
                window.location.href = url.toString();
            }
        }

    }
</script>