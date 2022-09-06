<template>
  <form @submit.prevent="submit">
    <select
      v-model="selectedCompany"
      name="cars"
      id="cars"
      v-on:change="showLeads"
      class="pl-2 pr-5 py-1 rounded border border-gray-200"
    >
      <option :value="null" disabled hidden>Choose a company</option>
      <option
        v-for="company in companies"
        :key="company.id"
        :value="company.id"
      >
        {{ company.name }}
      </option>
    </select>
  </form>
</template>

<script>
export default {
  props: {
    initialCompanies: { default: "[]" },
    page:'',
    initialSelectedCompany: { default: null },
  },
  data() {
    return {
      companies: JSON.parse(this.initialCompanies),
      selectedCompany: this.initialSelectedCompany,
    };
  },
  methods: {
    showLeads: function () {
      if (this.selectedCompany) {
        if(this.page==='leads'){
          window.location.href = `/companies/${this.selectedCompany}/leads`;
        }else if(this.page==='dashboard'){
          window.location.href = `/dashboard/company/${this.selectedCompany}`;
        }
      }
    },
  },
};
</script>
