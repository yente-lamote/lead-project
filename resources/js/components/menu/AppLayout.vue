<template>
  <div class="flex h-screen">
    <sidebar :initial-companies="companies"></sidebar>
    <div class="flex-1 h-full overflow-x-hidden overflow-y-auto">
      <custom-header>
        <template slot="header-right-side">
          <slot name="header-right-side"></slot>
        </template>
      </custom-header>
      <slot name="main"></slot>
    </div>
  </div>
</template>

<script>

export default{
  props: {
    initialCompanies: {
      default: function () {
        return {};
      },
    },
  },
  data() {
    return {
      sidebarOpen: false,
      companies: this.initialCompanies,
    };
  }
}
</script>
