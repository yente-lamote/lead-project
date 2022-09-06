<template>
    <div>
        <div
        :class="$parent.sidebarOpen ? 'block' : 'hidden'"
        @click="$parent.sidebarOpen = false"
        class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"
        ></div>
        <div
        :class="
            $parent.sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'
        "
        class="z-30 fixed inset-y-0 left-0 w-56 h-screen transition lg:transition-none duration-300 transform bg-card border-r border-gray-200 lg:translate-x-0 lg:static lg:inset-0"
        >
        <!--fixed z-30 inset-y-0 left-0 w-56 h-screen transition duration-300 transform bg-card border-r border-gray-200 lg:translate-x-0 lg:static lg:inset-0-->
            <a href="/">
              <img class="py-3 text-center mx-auto h-15 w-32" :src="'/assets/images/logo.png'" alt="lead-project logo">
            </a>
            <search-bar class="mt-1 py-2 ml-5 md:hidden w-5/6" />
            <nav>
                <a
                href="/"
                class="nav-bar-item"
                :class="checkPathnames(['/', '/dashboard','/dashboard/company/*']) && 'selected'"
                >
                <dashboard-icon></dashboard-icon>
                <span class="ml-4 text-md font-medium">Dashboard</span>
                </a>
                <a
                href="/companies"
                class="nav-bar-item"
                :class="checkPathnames(['/companies','/companies/*']) && 'selected'"
                >
                <companies-icon></companies-icon>
                <span class="ml-4 text-md font-medium">Companies</span>
                </a>
            </nav>
        </div>
    </div>
</template>

<script>

export default{
  methods: {
    checkPathnames: function (array) {
      let arrayContainsPathname = false;
      array.forEach((pathRule) => {
        if (this.matchRule(window.location.pathname,pathRule)) {
          arrayContainsPathname = true;
        }
      });
      return arrayContainsPathname;
    },
    matchRule:function(str, rule){
      //https://stackoverflow.com/questions/26246601/wildcard-string-comparison-in-javascript
      var escapeRegex = (str) => str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
      return new RegExp("^" + rule.split("*").map(escapeRegex).join(".*") + "$").test(str);
    }
  },
};
</script>