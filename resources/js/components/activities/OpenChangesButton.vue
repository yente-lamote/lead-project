<template>
  <div
    class="py-2 cursor-pointer focus:outline-none focus:text-blue-600 select-none"
    aria-label="Open lead teaser"
    v-on:click="openCloseChanges"
    v-on:keyup.enter="openCloseChanges"
    role="button"
    tabIndex="0"
    :aria-expanded="this.open ? 'true' : 'false'"
  >
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
</template>

<script>
export default {
  props: ['activityId'],
  data() {
    return {
        open:false,
    };
  },
  methods: {
    openCloseChanges: function () {
      this.open = !this.open;
    document.querySelector("#activity-"+this.activityId).classList.toggle("changes-open");
      document.querySelector("#changes-"+this.activityId).classList.toggle("hidden");
    },
  },
};
</script>