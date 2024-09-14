<template>
  <form @submit.prevent="submitForm" class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
    <div class="mb-4">
      <label for="first_name" class="block text-gray-700 font-bold mb-2">First Name</label>
      <input :required="true" type="text" id="first_name" v-model="form.first_name" required
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>
    <div class="mb-4">
      <label for="last_name" class="block text-gray-700 font-bold mb-2">Last Name</label>
      <input :required="true" type="text" id="last_name" v-model="form.last_name" required
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>
    <div class="mb-4">
      <label for="position" class="block text-gray-700 font-bold mb-2">Position</label>
      <input :required="true" type="text" id="position" v-model="form.position" required
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>
    <div class="mb-4">
      <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
      <input :required="true" type="email" id="email" v-model="form.email" required
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>
    <div class="mb-4">
      <label for="phone_home" class="block text-gray-700 font-bold mb-2">Phone Number</label>
      <input :required="true" type="text" id="phone_home" v-model="form.phone_home" required
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>
    <div class="mb-4">
      <label for="notes" class="block text-gray-700 font-bold mb-2">Notes</label>
      <input type="text" id="notes" v-model="form.notes"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>
    <div class="mb-4">
      <label class="tpo__label">Supervisor</label>
      <multiselect v-model="value.supervisor" :close-on-select="false" tag-placeholder="Add this as new tag"
        placeholder="Search or add a tag" label="first_name" track-by="id" :max="1" :options="localSupervisor" :multiple="true"
        :taggable="true" @select="addSupervisor" ></multiselect>
    </div> 
    <div class="mb-4">
      <label class="tpo__label">Subordinates</label>
      <multiselect v-model="value.subordinates" :close-on-select="false" tag-placeholder="Add this as new tag"
        placeholder="Search or add a tag" label="first_name" track-by="id" :options="localSubordinates" :multiple="true"
        :taggable="true" @select="addSubordinates"></multiselect>
    </div>
    <button type="submit"
      class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
      {{ edit ? 'Update Employee' : 'Create Employee' }}
    </button>
  </form>
</template>


<script>

import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css';



export default {
  name: "EmployeeForm",
  components: { Multiselect },
  props: {
    employee: {
      type: Object,
      default: () => null
    }, 
    supervisor: {
      type: Object,
      default: () => null
    }, 
    subordinates: {
      type: Object,
      default: () => null
    }, 
    others: {
      type: Object,
      default: () => null
    },
    employees: {
      type: Array,
      default: () => [
        'John Doe',
        'Jane Smith',
        'Mark Johnson',
      ]
    },
    edit: {
      type: Boolean,
      default: false,
    }
  },
  data() {
    return {
      form: {
        first_name: '',
        last_name: '',
        position: '',
        email: '',
        phone_home: '',
        notes: '',
        subordinates: null,
        supervisor: null
      },
      value: {
        subordinates: [],
        supervisor: [],
      },
      localSubordinates: [],
      localSupervisor: [],
      errors: {},
    };
  },
  methods: {
    submitForm() {

      this.form.subordinates = this.value.subordinates;
      this.form.supervisor = this.value.supervisor;


      if (this.edit) {
        axios.put(`/employees/${this.form.id}`, this.form)
          .then(res => {
            this.$emit('employee-updated', res.data);
            this.resetForm();
          })
          .catch(this.handleErrors);
      } else {
        axios.post('/employees', this.form)
          .then(res => {
            this.$emit('employee-created', res.data.employee);
            this.resetForm();
          })
          .catch(this.handleErrors);
      }
    },
    handleErrors(err) {
      const errors = err.response?.data?.errors || {};
      this.errors = errors;
    },
    resetForm() {
      this.form = {
        first_name: '',
        last_name: '',
        position: '',
        email: '',
        phone_home: '',
        notes: '',
        subordinates: null,
        supervisor: null
      };
      this.edit = false;
    },
    addSubordinates(newTag) {
      this.localSupervisor = this.localSupervisor.filter(item => item !== newTag);
    },
    addSupervisor(newTag) {
      this.localSubordinates = this.localSubordinates.filter(item => item !== newTag);
    }
  },
  created() {
    if(this.edit) {
      this.form = this.employee;
    }


    if(this.subordinates) {
      this.value.subordinates = this.subordinates;
      this.localSubordinates =  this.others.concat(this.subordinates);
    }else {
      this.localSubordinates =  this.others;
    }


    if(this.supervisor) {
      this.value.supervisor = this.supervisor;
      this.localSupervisor =  this.others.concat(this.supervisor);
    }else {
      this.localSupervisor =  this.others;
    }
    



    // console.table(this.value.subordinates)
    // console.table(this.value.supervisor)
    // console.table(this.localSubordinates)
    // console.table(this.localSupervisor)
  }
};

</script>
<style scoped></style>
