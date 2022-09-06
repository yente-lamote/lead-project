<template>
    <modal 
        name="edit-employee" 
        @before-open="beforeOpen" 
        classes="px-10 py-8 bg-card rounded-lg text-default w-1/2"
         height="auto" width="90%" 
         :maxWidth="400" 
         :adaptive="true" 
         :shiftY="0.4">
         
        <h3 class="text-2xl mb-3">Edit employee</h3>
        <p class="employee-label">
            Name
        </p>
        <p>{{this.employee.name}}</p>
        <p class="employee-label">
            Email
        </p>
        <p>{{this.employee.email}}</p>
        <name-description-dropdown :attributes="roles" :attribute-name="'role'" :initial-attribute="initialRole" :post-url="'/companies/'+ this.company.id + '/employees/' + this.employee.id"></name-description-dropdown>
    </modal>
</template>

<script>
export default {
    props: {
        initialCompany: {
            default: function() {
                return {};
            },
        },
        roles: {
            default: function() {
                return {};
            },
        },
    },
    data() {
        return {
            company: this.initialCompany,
            employee: {},
            initialRole: {},
        }
    },
    methods: {
        beforeOpen(event) {
            this.employee = event.params.employee;
            this.initialRole = event.params.employee.pivot.role;
        },
    },
};
</script>