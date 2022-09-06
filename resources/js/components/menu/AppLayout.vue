<template>
  <div class="flex h-screen">
    <sidebar></sidebar>
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
  data() {
    return {
      sidebarOpen: false,
    };
  }
}
</script>
