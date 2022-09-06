<template>
  <form action="/search">
    <div
      class="relative text-gray-600 rounded-2xl px-2 focus-within:ring focus-within:border-blue-300 focus-within:text-blue-400 focus-within:border"
    >
      <span class="absolute inset-y-0 left-0 flex items-center pl-2">
        <button type="submit" aria-label="Search" class="p-1 focus:outline-none" tabindex="-1">
          <svg
            fill="none"
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            viewBox="0 0 24 24"
            class="w-6 h-6"
          >
            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </button>
      </span>
      <input
        type="search"
        name="query"
        class="py-2 text-sm rounded-md pl-10 focus:outline-none focus:border text-black input w-full"
        placeholder="Search..."
        autocomplete="off"
        :value="getQuery()"
      />
    </div>
  </form>
</template>
<script>
export default ({
  methods:{
    getQuery:function(){
      let queryString = window.location.search;
      let urlParams = new URLSearchParams(queryString);
      return urlParams.get('query')
    }
  }
})
</script>
