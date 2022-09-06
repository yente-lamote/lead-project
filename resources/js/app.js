/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('chart.js')
import VModal from 'vue-js-modal';

window.Vue = require('vue').default;
Vue.use(VModal);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('app-layout', require('./components/menu/AppLayout.vue').default);
Vue.component('search-bar', require('./components/menu/SearchBar.vue').default);
Vue.component('sidebar', require('./components/menu/Sidebar.vue').default);
Vue.component('custom-header', require('./components/menu/Header.vue').default);

Vue.component('lead-teaser', require('./components/leads/LeadTeaser.vue').default);
Vue.component('select-company', require('./components/leads/SelectCompany.vue').default);
Vue.component('page-wrapper', require('./components/leads/PageWrapper.vue').default);

Vue.component('companies-icon', require('./components/icons/CompaniesIcon.vue').default);
Vue.component('dashboard-icon', require('./components/icons/DashboardIcon.vue').default);

Vue.component('name-description-dropdown', require('./components/NameDescriptionDropdown.vue').default);
Vue.component('sort-by', require('./components/SortBy.vue').default);
Vue.component('select-action-button', require('./components/SelectActionButton.vue').default);


Vue.component('new-leads-per-day-chart', require('./components/charts/NewLeadsPerDayChart.vue').default);
Vue.component('leads-pie-chart', require('./components/charts/LeadsPieChart.vue').default);
Vue.component('success-rate-chart', require('./components/charts/SuccessRateChart.vue').default);

Vue.component('employee-card', require('./components/employees/Card.vue').default);

Vue.component('open-changes-button', require('./components/activities/OpenChangesButton.vue').default);


Vue.component('edit-company-modal', require('./components/companies/EditCompanyModal.vue').default);
Vue.component('edit-employee-modal', require('./components/employees/EditEmployeeModal.vue').default);
Vue.component('add-extra-attribute-modal', require('./components/attributes/AddExtraAttributeModal.vue').default);
Vue.component('add-extra-company-modal', require('./components/leads/AddExtraCompanyModal.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
