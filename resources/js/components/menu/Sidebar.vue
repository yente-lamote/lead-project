<template>
    <div>
        <div
            :class="$parent.sidebarOpen ? 'block' : 'hidden'"
            @click="$parent.sidebarOpen = false"
            class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"
        ></div>
        <div
            :class="
                $parent.sidebarOpen
                    ? 'translate-x-0 ease-out'
                    : '-translate-x-full ease-in'
            "
            class="z-30 fixed inset-y-0 left-0 w-56 h-screen transition lg:transition-none duration-300 transform bg-card border-r border-gray-200 lg:translate-x-0 lg:static lg:inset-0"
        >
            <!--fixed z-30 inset-y-0 left-0 w-56 h-screen transition duration-300 transform bg-card border-r border-gray-200 lg:translate-x-0 lg:static lg:inset-0-->
            <div class="pt-3.5 md:mb-5 mb-2">
                <a href="/" class="text-xl font-semibold flex ml-4">
                    <img src="/assets/images/logo.png" alt="logo" class="h-7 w-7 mr-2"/>
                    <span class="logo-font">
                        Lead-project
                    </span>
                </a>
            </div>

            <search-bar class="mt-1 py-2 md:hidden w-5/6" />
            <nav class="lg:mt-9">
                <a
                    href="/"
                    class="nav-bar-item"
                    :class="
                        checkPathnames([
                            '/',
                            '/dashboard',
                            '/dashboard/company/*'
                        ]) && 'selected'
                    "
                >
                    <dashboard-icon></dashboard-icon>
                    <span class="ml-2 text-md font-medium">Dashboard</span>
                </a>
                <a
                    href="/companies"
                    class="nav-bar-item"
                    :class="
                        checkPathnames(['/companies', '/companies/*']) &&
                            'selected'
                    "
                >
                    <companies-icon></companies-icon>
                    <span class="ml-2 text-md font-medium">My Companies</span>
                    <svg
                      class="ml-auto mr-3 w-5 mt-0.5 h-5 transition-transform transform"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      :class="{ 'rotate-180': !companiesClosed }"
                      @click="openCloseCompanies"
                      >
                      <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M19 9l-7 7-7-7"
                      ></path>
                    </svg>
                </a>
                
                <div class="ml-11 text-sm text-gray-400 overflow-hidden" :class="{'h-0':companiesClosed}">
                    <div v-for="company in companies" class="block py-1 w-4/5">
                        <a :href="'/companies/'+company.id" :class="
                        checkPathnames(['/companies/'+company.id]) &&
                            'font-medium text-gray-500'">
                            {{company.name}}
                        </a>
                    </div>
                </div>
            </nav>
            <div class="absolute bottom-8 w-full flex items-center">
                <span class="m-auto text-gray-400">Made by: <a class="text-blue-600" href="https://yentelamote.be">Yente Lamote</a></span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        initialCompanies: {
            default: function() {
                return {};
            }
        }
    },
    data() {
        return {
            companies: this.initialCompanies,
            companiesClosed:false
        };
    },
    methods: {
        checkPathnames: function(array) {
            let arrayContainsPathname = false;
            array.forEach(pathRule => {
                if (this.matchRule(window.location.pathname, pathRule)) {
                    arrayContainsPathname = true;
                }
            });
            return arrayContainsPathname;
        },
        matchRule: function(str, rule) {
            //https://stackoverflow.com/questions/26246601/wildcard-string-comparison-in-javascript
            var escapeRegex = str =>
                str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
            return new RegExp(
                "^" +
                    rule
                        .split("*")
                        .map(escapeRegex)
                        .join(".*") +
                    "$"
            ).test(str);
        },
        openCloseCompanies:function(e){
            e.preventDefault();
            this.companiesClosed=!this.companiesClosed;
            window.localStorage.setItem('sidebarMyCompaniesClosed',this.companiesClosed);
        }
    },beforeMount() {
        console.log(localStorage.getItem('sidebarMyCompaniesClosed'))

        if(localStorage.getItem('sidebarMyCompaniesClosed')!=null){
            this.companiesClosed=
                localStorage.getItem('sidebarMyCompaniesClosed')=="true"?
                    true:
                    false
        }{
            localStorage.setItem('sidebarMyCompaniesClosed',false)
        }
    }
};
</script>
