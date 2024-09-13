<template>
    <div class="flex justify-between items-center">
        <div>
            <p> <span class="italic mr-4">FIRST_NAME: </span> {{ employee.first_name }}</p>
            <p> <span class="italic mr-4">LAST_NAME: </span> {{ employee.last_name }}</p>
            <p> <span class="italic mr-4">POSITION: </span> {{ employee.position }}</p>
            <p> <span class="italic mr-4">EMAIL: </span> {{ employee.email }}</p>
            <p> <span class="italic mr-4">PHONE_HOME: </span> {{ employee.phone_home }}</p>
            <p> <span class="italic mr-4">NOTES: </span> {{ employee.notes }}</p>
        </div>
        <div>
            <button type="button" @click="changeEmployee('created')"
                class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Remove </button>
            <button type="button" @click="editEmployee"
                class="text-white bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                Edit
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: ['employeeitem'],
    name: 'EmployeeItem',
    data() {
        return {
            employee: {
                type: Object
            }
        }
    },
    created() {
        this.employee = { ...this.employeeitem };
    },
    methods: {
        editEmployee() {
            this.$emit('edit-employee', this.employee);
        },
        changeEmployee(status){
            this.employee.status = status;
            this.$emit('change-employee-status', this.employee);
        }
    }
}
</script>

<style></style>
