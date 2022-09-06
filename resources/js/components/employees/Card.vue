<template>
<div class="flip-card" v-bind:class="{turned: isActive}">
    <div class="front">
        <div class="flex-1">
            <img alt="image user" src="/assets/images/companies/default.png" class="shadow object-cover rounded-full mx-auto w-24 h-24"/>
            <p class="text-center mt-2 text-xl">{{this.employee.name}}</p>
            <p class="text-center text-gray-400 text-sm mb-4">{{this.role.name}}</p>
        </div>
        <div class="border-t border-gray-400 mb-0 pt-2 text-center">
            <button @click="turnCard" class="button-primary">View</button>
        </div>
    </div>
    <div class="back">
        <div class="flex-1 text-sm">
            <span class="text-gray-400">Email</span>
            <p class="">{{this.employee.email}}</p>
            <span class="text-gray-400">Created at</span>
            <p class="">{{this.getCreatedAt()}}</p>
        </div>
        <div>
            <div class="flex justify-between mb-2 px-1">
                <button @click="openModal" class="button-primary">Edit</button>
                    <form method="POST" class="inline" v-bind:action="`/companies/${this.companyId}/employees/${this.employee.id}`">
                        <input type="hidden" name="_token" :value="csrf">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" class="button-danger ml-3" value="Remove">
                    </form>            </div>
            <div class="border-t border-gray-400 mb-0 pt-2 text-center">
                <button @click="turnCard" class="button-primary">Go back</button>
            </div>    
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        initialEmployee: {
            default: function() {
                return {};
            },
        },
        initialRole: {
            default: function() {
                return {};
            },
        },
        companyId:""
    },
    data() {
        return {
            employee: JSON.parse(this.initialEmployee),
            role: JSON.parse(this.initialRole),
            isActive:false,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    },
    methods: {
        turnCard:function(){
            this.isActive=!this.isActive;
        },
        getCreatedAt: function(){
            var options = { year: 'numeric', month: 'short', day: 'numeric' };
            var day  = new Date(this.employee.created_at);
            return day.toLocaleDateString("en-GB", options);
        },
        openModal: function(){
            let employee = this.employee;
            employee.pivot.role = this.role
            this.$modal.show('edit-employee',{ 'employee': employee });
        },
    },
};
</script>
