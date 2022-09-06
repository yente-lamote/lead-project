<template>
    <modal 
        name="add-extra-company"
        classes="px-10 py-8 bg-card rounded-lg text-default w-1/2" 
        height="auto" width="90%" 
        :maxWidth="400" 
        :adaptive="true" 
        :shiftY="0.4">

        <h3 class="text-2xl mb-3">Add extra company</h3>
        <name-description-dropdown :attributes="companies" :attribute-name="'company'" :post-url="'/leads/'+ leadId + '/extra-companies'"></name-description-dropdown>
    </modal>
</template>

<script>
export default {
    props: {
        leadId: {
            default: ""
        },
        companies: {
            default: function() {
                return {};
            },
        },
    },
};
</script>