<template>
  <div class="lead" :class="{'border-b border-gray-200':!this.last}">
    <div class="sm:flex sm:flex-1 items-center">
      <div class="sm:w-5/12 flex items-center sm:flex-none">
        <div class="ml-2 mr-4 text-center">
                <input type="checkbox" aria-label="Select" :value="this.lead.id">
        </div>
        <div>
          <a
            class="text-lg block whitespace-nowrap"
            :href="this.path"
            >{{this.fullname}}</a
          >
          <p class="text-xs text-gray-400">
            {{this.lead.email}}
          </p>
        </div>
        <div class="w-full py-3 cursor-pointer focus:outline-none focus:text-blue-600 select-none" aria-label="Open lead teaser" v-on:click="open=!open" v-on:keyup.enter="open=!open" role="button" tabIndex="0" :aria-expanded="this.open ? 'true' : 'false'">
            <svg
            class="ml-auto sm:hidden w-5 h-5 transition-transform transform"
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
      </div>
      <div
        class="pl-4 mt-2 sm:pl-0 sm:mt-0 sm:flex sm:flex-1 justify-between items-center sm:block" :class="{ 'hidden': !open }"
      >
        <div v-if="this.lead.hasOwnProperty('company')" class="sub-item">
            <a class="value hover:text-blue-400" :href="this.companyPath">{{this.lead.company.name}}</a>
            <p class="label">Company</p>
        </div>
        <div class="sub-item">
          <p class="value">{{this.formatDate()}}</p>
          <p class="label">Planned date</p>
        </div>
        <div class="sub-item w-36">
          <p class="value">{{this.lead.status.name}}</p>
          <p class="label">Status</p>
        </div>
        <div class="sm:mt-0 mt-3">
          <a class="button-primary mr-2" :href="this.path">View</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        props: {
            lead: { default:function () {
                return { }
            }},
            fullname:{default:''},
            last:{default:false},
            path:{default:''},
            companyPath:{default:''},
        },
        data() {
            return {
                open:false,
            };
        },
        methods:{
            formatDate: function(){
                let date = new Date(this.lead.planned_date);
                return date.getDate() + " "+date.toLocaleString("en-US", { month: 'short', year: 'numeric'});
            },
        }
    };
</script>