<template>
  <div>
    <nav id="page-swapper">
        <ul>
            <li class="">
                <button v-bind:class="{'active':tab==='status-tab'}" type="button" @click="changeTab('status-tab')" aria-label="View status">Status</button>
            </li>
            <li class="">
                <button v-bind:class="{'active':tab==='attributes-tab'}" type="button" @click="changeTab('attributes-tab')" aria-label="View attributes">Attributes</button>
            </li>
            <li class="">
                <button v-bind:class="{'active':tab==='activity-logs-tab'}" type="button" @click="changeTab('activity-logs-tab')" aria-label="View activity logs">Activity logs</button>
            </li>
            <li class="">
                <button v-bind:class="{'active':tab==='companies-tab'}" type="button" @click="changeTab('companies-tab')" aria-label="View companies that have access to this lead">Companies</button>
            </li>
        </ul>
    </nav>
    <div class="bg-card rounded-md shadow p-3 md:p-6 pt-2">
      <div v-bind:class="{'hidden':tab!=='status-tab'}">
        <slot  name="status-tab"></slot>
      </div>
      <div v-bind:class="{'hidden':tab!=='attributes-tab'}">
        <slot  name="attributes-tab"></slot>
      </div>
      <div v-bind:class="{'hidden':tab!=='activity-logs-tab'}">
        <slot  name="activity-logs-tab"></slot>
      </div>
      <div v-bind:class="{'hidden':tab!=='companies-tab'}">
        <slot  name="companies-tab"></slot>
      </div>
    </div>
  </div>
</template>

<script>

export default{
  data() {
    return {
      tab: this.getTabParameterValue(),
    };
  },
  methods: {
    getTabParameterValue: function () {
      let urlParams = new URLSearchParams(window.location.search);
      let tab;
      if(urlParams.get('page'))tab="activity-logs-tab";
      if(urlParams.get('tab'))tab = urlParams.get('tab');
      return tab??"status-tab"
    },
    changeTab:function(tab){
      this.tab=tab;
      let urlParams = new URLSearchParams(window.location.search);
      urlParams.set('tab',tab)
      window.history.pushState("", 'lead-'+tab, window.location.href.substr(0, window.location.href.indexOf('?')) +'?'+urlParams);
    }
  },
}
</script>
