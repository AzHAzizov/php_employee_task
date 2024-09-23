<template>
  <nav class="p-4">
    <AlertMessage class="fixed top-0 left-0 right-0" v-if="errorMessage" :message="errorMessage" :timer="2000" />
    <button @click="showModalToCreateEmployee" type="button"
      class="display block py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
      Enter new Employee
    </button>

    <Modal :isOpen="modal.createEmployee" @close="hideModalToCreateEmployee">
      <EmployeeForm :supervisor="supervisor" :subordinates="subordinates" :others="others" @employee-created="newEmployeeCreated" :employee="clearFormTemplate" />
    </Modal>
  </nav>
  <div class="p-4">
    <div>
      <EmployeeItem @edit-employee-status="toggleEmployeeStatus" @remove-employee="removeEmployee" class="container" v-for="employee in localEmployees"
        :key="employee.id" :employeeitem="employee" @edit-employee="showEditForm" />
      <Modal :isOpen="modal.editEmployee" @close="hideModalToCreateEmployee">
        <EmployeeForm :supervisor="supervisor" :subordinates="subordinates" :others="others" :edit="true"
          :employee="employeeToEdit" :employees="localEmployees" @employee-updated="updateEmployee" />
      </Modal>
    </div>
  </div>
</template>

<script>
import EmployeeForm from "@/Components/EmployeeForm.vue";
import Modal from "@/Components/Modal.vue";
import EmployeeItem from "@/Components/EmployeeItem.vue";
import AlertMessage from "@/Components/AlertMessage.vue";
import axios from "axios";



export default {
  props: {
    employees: {
      type: Object,
      default: {}
    },
  },
  data() {
    return {
      showModal: false,
      modal: {
        createEmployee: false,
        editEmployee: false
      },
      supervisor: null,
      subordinates: null,
      others: null,
      employeeToEdit: null,
      localEmployees: null,
      errorMessage: null
    }
  },
  created() {
    this.localEmployees = this.employees
  },
  components: { EmployeeItem, Modal, EmployeeForm, AlertMessage },
  methods: {
    toggleEmployeeStatus(employee) {
      axios.put('/employees/' + employee.id, employee).then(res => {
        this.localEmployees = this.localEmployees.map(t => {
          return t.id === res.data.id ? res.data : t
        });
      }).catch(err => {
        this.errorMessage = err.response.data.message;
        setTimeout(() => {
          this.errorMessage = null;
        }, 2000);
      });
    },
    async showEditForm(employee) {
      await axios.get('/employees/' + employee.id).then(res => {
        this.supervisor = res.data.supervisors
        this.subordinates = res.data.subordinates
        this.others = res.data.others
      }).catch(err => {

      });
      this.modal.editEmployee = true;
      this.employeeToEdit = employee;
    },
    removeEmployee(employee) {
      axios.delete('/employees/' + employee.id).then(res => {
        this.localEmployees = this.localEmployees.filter(t => {
          return t.id != employee.id;
        });
      });
    },
    updateEmployee(employee) {
      this.hideModalToCreateEmployee();
      axios.put('/employees/' + employee.id, employee).then(res => {
        this.localEmployees = this.localEmployees.map(t => {
          return t.id === res.data.id ? res.data : t
        });
      })
      this.employeeToEdit = null;
    },
    async showModalToCreateEmployee() {
      await axios.get('/employees/make').then(res => {
        this.supervisors = res.data.supervisors
        this.subordinates = res.data.subordinates
        this.others = res.data.others
      }).catch(err => {

      });

      this.resetForm();
      this.modal.createEmployee = true;
    },
    hideModalToCreateEmployee() {
      this.modal.createEmployee = false;
      this.modal.editEmployee = false;
    },
    newEmployeeCreated(newEmployee) {
      this.employees.push(newEmployee);
      console.log(this.employees)
      this.hideModalToCreateEmployee();
    },
    resetForm() {
      this.formTemplate = {
        title: '',
        descriptieon: '',
        employee_day: ''
      };
    },
  }
}
</script>

<style scoped>
.container {
  padding: 5px 10px;
  border: 1px inherit #ccffab;
  border-radius: 15px;
  background: #46d698;
  margin: 15px 10px;
}
</style>
