<template>
  <div>
    Admin Owner view
    <download-excel
      class   = "btn btn-secondary"
      :data   = "json_data"
      :fields = "json_fields"
      name    = "Owner List.xls">
      Export
    </download-excel>
    <b-button @click="showCreateUserModal" variant="success">Create</b-button>
    <b-modal ref="modal_create_user" hide-footer size="lg" title="Create User">
      <b-form @submit="saveUser" class="user">
        <b-form-row id="register">
          <b-form-group id="firstNameInputGroup"
                        label="First Name:"
                        label-for="firstNameInputGroup">
              <b-form-input id="inputFirstName"
                            type="text"
                            v-model="user.fname"
                            required>
              </b-form-input>
              <span v-if="errorsFname" class="help-block">
                <strong>{{ fnameError }}</strong>
              </span>
          </b-form-group>
          <b-form-group id="lastNameInputGroup"
                        label="Last Name:"
                        label-for="lastNameInputGroup">
              <b-form-input id="inputLastName"
                            type="text"
                            v-model="user.lname"
                            required>
              </b-form-input>
              <span v-if="errorsLname" class="help-block">
                <strong>{{ lnameError }}</strong>
              </span>
          </b-form-group>
        </b-form-row>
        <b-form-row>
          <b-form-group id="emailInputGroup"
                        label="Email:"
                        label-for="emailInputGroup">
              <b-form-input id="inputEmail"
                            type="email"
                            v-model="user.email"
                            required>
              </b-form-input>
              <span v-if="errorsEmail" class="help-block">
                <strong>{{ emailError }}</strong>
              </span>
          </b-form-group>
          <b-form-group id="phoneNumberInputGroup"
                        label="Phone Number:"
                        label-for="phoneNumberInputGroup">
              <b-form-input id="inputPhoneNumber"
                            type="text"
                            v-model="user.phone"
                            required>
              </b-form-input>
              <span v-if="errorsPhone" class="help-block">
                <strong>{{ phoneError }}</strong>
              </span>
          </b-form-group>
        </b-form-row>
        <b-form-row>
          <b-form-group id="roleInputGroup"
                        label="Role:"
                        label-for="roleInputGroup">
            <b-form-select id="inputRole"
                          :options="roles"
                          v-model="user.user_role_id"
                          required>
            </b-form-select>
            <span v-if="errorsRole" class="help-block">
              <strong>{{ roleError }}</strong>
            </span>
          </b-form-group>
          <b-form-group id="classInputGroup"
                        label="Class:"
                        label-for="classInputGroup">
              <b-form-input id="inputClass"
                            type="text"
                            v-model="user.class"
                            >
              </b-form-input>
              <span v-if="errorsClass" class="help-block">
                <strong>{{ classError }}</strong>
            </span> 
          </b-form-group>

        </b-form-row>
        <b-form-row>
          <b-form-group id="roleInputGroup"
                        label="Password:"
                        label-for="roleInputGroup">
            <b-form-input id="inputPassword"
                            type="password"
                            v-model="user.password"
                            required>
              </b-form-input>
              <span v-if="errorsPassword" class="help-block">
                <strong>{{ passwordError }}</strong>
              </span> 
          </b-form-group>
          <b-form-group id="classInputGroup"
                        label="Confirm Password:"
                        label-for="classInputGroup">
              <b-form-input id="inputConfirmPassword"
                            type="password"
                            v-model="user.cpassword"
                            required>
              </b-form-input>
              <span v-if="errorsCpassword" class="help-block">
                <strong>{{ cpasswordError }}</strong>
              </span> 
          </b-form-group>

        </b-form-row>
        <b-form-group id="isActiveInputGroup">
        <!-- <b-form-checkbox-group v-model="user.is_blocked" id="isActive"> -->
          <b-form-checkbox v-model="user.is_blocked" id="isActive" value="1" unchecked-value="0">Is Blocked</b-form-checkbox>
        <!-- </b-form-checkbox-group> -->
      </b-form-group>
      <b-btn type="submit" block>Save</b-btn>
    </b-form>
    </b-modal>
    <b-row>
      <b-col md="6" class="my-1">
        <b-form-group horizontal label="Per page" class="mb-0">
          <b-form-select :options="pageOptions" v-model="perPage" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group horizontal>
          <b-input-group>
            <b-form-input type="search" v-model="filter" placeholder="Search"/>
          </b-input-group>
        </b-form-group>
      </b-col>
    </b-row>
    <b-table striped hover
              :items="users"
              :fields="fields"
              :filter="filter"
              :current-page="currentPage"
              :per-page="perPage"
              @filtered="onFiltered">
      <template slot="is_blocked" slot-scope="row">
        <b-form-checkbox id="checkbox1"
                         :checked="row.value"
                         value="1"
                         unchecked-value="0"
                         disabled
        ></b-form-checkbox>
      </template>
      <template slot="action" slot-scope="row">
        <b-button class="edit-btn" size="sm" @click.stop="editUser(row.item)"><i class="fa fa-pencil" aria-hidden="true"></i></b-button>
        <b-button class="d-btn" size="sm" @click.stop="deleteUser(row.item, row.index)"><i class="fa fa-trash" aria-hidden="true"></i></b-button>
      </template>
    </b-table>
    <b-col md="6">
      <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage"/>
    </b-col>
  </div>
</template>

<script>

 export default {
   data() {
     return {
       user: {
         id: null,
         fname: '',
         lname: '',
         email: '',
         phone: '',
         class: '',
         password: '',
         password: '',
         is_blocked: 0,
         role_id: null
       },
       errors: [],
       users: [],
       fields: [{
         key: 'id',
         sortable: true
       }, {
         key: 'fname',
         sortable: true
       }, {
         key: 'lname',
         sortable: true
       }, {
         key: 'email',
         sortable: true
       },{
         key: 'is_blocked',
         sortable: false
       },{
         key: 'action',
         sortable: false
       }],
       roles: [
        { value: null, text: 'Select Role'}
       ],
       json_fields: {
          'First Name': 'fname',
          'Last Name': 'lname',
          'Email': 'email',
          'Phone': 'phone',
          'Created At': 'created_at',
      },
      json_meta: [
          [
              {
                  'key': 'charset',
                  'value': 'utf-8'
              }
          ]
      ],
      json_data: [],
       filter: null,
       currentPage: 1,
       perPage: 10,
       pageOptions: [5, 10, 15],
       totalRows: 0,
       errorsFname: false,
       errorsLname: false,
       errorsEmail: false,
       errorsPhone: false,
       errorsRole: false,
       errorsClass: false,
       errorsPassword: false,
       errorsCpassword: false,
       fnameError: null,
       lnameError: null,
       emailError: null,
       phoneError: null,
       roleError: null,
       classError: null,
       passwordError: null,
       cpasswordError: null
     }
   },
   created() {
     this.totalRows = this.users.length;
   },
   mounted() {
     this.getUsers();
     this.getUserRoles();
   },
   methods: {
     clearForm() {
      for (var key in this.permission) {
        if(key === 'id' || key === 'user_role_id') {
          this.permission[key] = null;
        } else if(key === 'is_blocked') {
          this.permission[key] = 0;
        } else {
          this.permission[key] = "";
        }
      }
     },
     getUsers() {
       axios.get('user').then(res => {
         this.users = res.data.users;
         this.json_data = res.data.users;
       });
     },
     saveUser() {
        let vm = this;
        if(vm.user.id != null && vm.user.id != "") {
          axios.put('/user/'+vm.user.id, vm.user)
          .then(function (response) {
            vm.$refs.modal_create_user.hide();
            this.clearForm();
            this.getUsers();
            errorsFname= false;
            errorsLname= false;
            errorsEmail= false;
            errorsPhone= false;
            errorsRole= false;
            errorsClass= false;
            errorsPassword= false;
            errorsCpassword= false;
          })
          .catch(function (error) {
            var errors = error.response;
            if (errors.statusText === 'Unprocessable Entity' || errors.status === 422) {
              if (errors.data) {
                if (errors.data.errors.email) {
                  vm.errorsEmail = true;
                  vm.emailError = _.isArray(errors.data.errors.email) ? errors.data.errors.email[0] : errors.data.errors.email;
                }
                if (errors.data.errors.password) {
                  vm.errorsPassword = true;
                  vm.passwordError = _.isArray(errors.data.errors.password) ? errors.data.errors.password[0] : errors.data.errors.password;
                }
                if (errors.data.errors.user_role_id) {
                  vm.errorsRole = true;
                  vm.roleError = _.isArray(errors.data.errors.user_role_id) ? errors.data.errors.user_role_id[0] : errors.data.errors.user_role_id;
                }
                if (errors.data.errors.fname) {
                  vm.errorsFname = true;
                  vm.fnameError = _.isArray(errors.data.errors.fname) ? errors.data.errors.fname[0] : errors.data.errors.fname;
                }
                if (errors.data.errors.lname) {
                  vm.errorsLname = true;
                  vm.lnameError = _.isArray(errors.data.errors.lname) ? errors.data.errors.lname[0] : errors.data.errors.lname;
                }
                if (errors.data.errors.cpassword) {
                  vm.errorsCpassword = true;
                  vm.cpasswordError = _.isArray(errors.data.errors.cpassword) ? errors.data.errors.cpassword[0] : errors.data.errors.cpassword;
                }
                if (errors.data.errors.phone) {
                  vm.errorsPhone = true;
                  vm.phoneError = _.isArray(errors.data.errors.phone) ? errors.data.errors.phone[0] : errors.data.errors.phone;
                }
              }
            }
          });
        } else {
          axios.post('/user', vm.user)
          .then(function (response) {
            vm.$refs.modal_create_user.hide();
            this.clearForm();
            this.getUsers();
            errorsFname= false;
            errorsLname= false;
            errorsEmail= false;
            errorsPhone= false;
            errorsRole= false;
            errorsClass= false;
            errorsPassword= false;
            errorsCpassword= false;
          })
          .catch(function (error) {
            var errors = error.response;
            if (errors.statusText === 'Unprocessable Entity' || errors.status === 422) {
              if (errors.data) {
                if (errors.data.errors.email) {
                  vm.errorsEmail = true;
                  vm.emailError = _.isArray(errors.data.errors.email) ? errors.data.errors.email[0] : errors.data.errors.email;
                }
                if (errors.data.errors.password) {
                  vm.errorsPassword = true;
                  vm.passwordError = _.isArray(errors.data.errors.password) ? errors.data.errors.password[0] : errors.data.errors.password;
                }
                if (errors.data.errors.user_role_id) {
                  vm.errorsRole = true;
                  vm.roleError = _.isArray(errors.data.errors.user_role_id) ? errors.data.errors.user_role_id[0] : errors.data.errors.user_role_id;
                }
                if (errors.data.errors.fname) {
                  vm.errorsFname = true;
                  vm.fnameError = _.isArray(errors.data.errors.fname) ? errors.data.errors.fname[0] : errors.data.errors.fname;
                }
                if (errors.data.errors.lname) {
                  vm.errorsLname = true;
                  vm.lnameError = _.isArray(errors.data.errors.lname) ? errors.data.errors.lname[0] : errors.data.errors.lname;
                }
                if (errors.data.errors.cpassword) {
                  vm.errorsCpassword = true;
                  vm.cpasswordError = _.isArray(errors.data.errors.cpassword) ? errors.data.errors.cpassword[0] : errors.data.errors.cpassword;
                }
                if (errors.data.errors.phone) {
                  vm.errorsPhone = true;
                  vm.phoneError = _.isArray(errors.data.errors.phone) ? errors.data.errors.phone[0] : errors.data.errors.phone;
                }
              }
            }
          });
        }
     },
     showCreateUserModal() {
       this.$refs.modal_create_user.show()
     },
     getUserRoles() {
       axios.get('userRole').then(res => {//console.log(res);
         for(let i=0; i < res.data.userRoles.length; i++) {
           const role = { value: res.data.userRoles[i].id, text: res.data.userRoles[i].name}
           this.roles.push(role);
         }
       });
     },
     editUser(item) {
       this.user = item;
       this.showCreateUserModal();
     },
     deleteUser(item, index) {
      let app = this;  
      swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          axios.delete('/user/'+(item.id))
          .then(function (response) {
            swal(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            );
            //app.users.splice(index, 1);
            app.getUsers();
          })
          .catch(function (error) {
            var errors = error.response;
            swal({
              type: 'error',
              title: 'Oops...',
              text: 'Something went wrong!',
            });
            //if (errors.statusText === 'Unprocessable Entity' || errors.status === 422) {
            //}
          });
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
          swal(
            'Cancelled',
            'Your imaginary file is safe :)',
            'error'
          )
        }
      });
     },
     onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    exportCsv() {
    }
   }
 }
</script>
