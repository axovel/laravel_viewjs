<template>
  <div>
    User Roles
    <b-button @click="exportCsv">Export</b-button>
    <b-button @click="showCreateuserRoleModal" variant="success">Create</b-button>
    <b-modal ref="modal_create_userRole" hide-footer size="lg" title="Create User Roles">
      <b-form @submit="saveuserRole">
        <b-form-row>
          <b-form-group id="NameInputGroup"
                        label="Name:"
                        label-for="NameInputGroup">
              <b-form-input id="inputName"
                            type="text"
                            v-model="userRole.name"
                            required>
              </b-form-input>
              <span v-if="errorsName" class="help-block">
                <strong>{{ nameError }}</strong>
              </span>
          </b-form-group>
          <b-form-group id="display_nameInputGroup"
                        label="Display Name:"
                        label-for="display_nameInputGroup">
              <b-form-input id="inputDisplayName"
                            type="text"
                            v-model="userRole.display_name"
                            required>
              </b-form-input>
              <span v-if="errorsDisplayName" class="help-block">
                <strong>{{ displayNameError }}</strong>
              </span>
          </b-form-group>
        </b-form-row>
          <b-form-group id="descriptionInputGroup"
                        label="Desctiption:"
                        label-for="descriptionInputGroup">
              <b-form-input id="inputDescription"
                            type="text"
                            v-model="userRole.description"
                            required>
              </b-form-input>
              <span v-if="errorsDescription" class="help-block">
                <strong>{{ descriptionError }}</strong>
              </span>
          </b-form-group>
        </b-form-row>
        
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
              :items="userRoles"
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
        <b-button class="edit-btn" size="sm" @click.stop="edituserRole(row.item)"><i class="fa fa-pencil" aria-hidden="true"></i></b-button>
        <b-button class="d-btn" size="sm" @click.stop="deleteuserRole(row.item, row.index)"><i class="fa fa-trash" aria-hidden="true"></i></b-button>
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
       userRole: {
         id: null,
         name: '',
         display_name: '',
         description: '',
         menu: ''
       },
       errors: [],
       userRoles: [],
       fields: [{
         key: 'id',
         sortable: true
       }, {
         key: 'name',
         sortable: true
       }, {
         key: 'display_name',
         sortable: true
       }, {
         key: 'description',
         sortable: true
       }, {
         key: 'menu',
         sortable: true
       },{
         key: 'created_at',
         sortable: true
       },{
         key: 'action',
         sortable: false
       }],
       filter: null,
       currentPage: 1,
       perPage: 10,
       pageOptions: [5, 10, 15],
       totalRows: 0,
       errorsName: false,
       errorsDisplayName: false,
       errorsDescription: false,
       nameError: null,
       displayNameError: null,
       descriptionError: null
     }
   },
   created() {
     this.totalRows = this.userRoles.length;
   },
   mounted() {
     this.getuserRoles();
   },
   methods: {
     clearForm() {
      for (var key in this.userRole) {
        if(key === 'id') {
          this.userRole[key] = null;
        } else {
          this.userRole[key] = "";
        }
      }
     },
     getuserRoles() {
       axios.get('userRole').then(res => {
         this.userRoles = res.data.userRoles;
       });
     },
     saveuserRole() {
       let vm = this;
       vm.userRole
        if(vm.userRole.id != null && vm.userRole.id != "") {
          axios.put('/userRole/'+vm.userRole.id, vm.userRole)
          .then(function (response) {
            this.$refs.modal_create_userRole.hide();
            this.getuserRoles();
            this.clearForm();
            vm.errorsName = false;
            vm.errorsDisplayName = false;
            vm.errorsDescription = false;
          })
          .catch(function (error) {
            var errors = error.response;
            if (errors.statusText === 'Unprocessable Entity' || errors.status === 422) {
              if (errors.data.errors.name) {
                vm.errorsName = true;
                vm.nameError = _.isArray(errors.data.errors.name) ? errors.data.errors.name[0] : errors.data.errors.name;
              }
              if (errors.data.errors.display_name) {
                vm.errorsDisplayName = true;
                vm.displayNameError = _.isArray(errors.data.errors.display_name) ? errors.data.errors.display_name[0] : errors.data.errors.display_name;
              }
              if (errors.data.errors.description) {
                vm.errorsDescription = true;
                vm.descriptionError = _.isArray(errors.data.errors.description) ? errors.data.errors.description[0] : errors.data.errors.description;
              }
            }
          });
        } else {
          axios.post('/userRole', vm.userRole)
          .then(function (response) {
            vm.$refs.modal_create_userRole.hide();
            vm.getuserRoles();
            vm.clearForm();
            vm.errorsName = false;
            vm.errorsDisplayName = false;
            vm.errorsDescription = false;
          })
          .catch(function (error) {
            var errors = error.response;
            if (errors.statusText != null && (errors.statusText === 'Unprocessable Entity' || errors.status === 422)) {
              if (errors.data.errors.name) {
                vm.errorsName = true;
                vm.nameError = _.isArray(errors.data.errors.name) ? errors.data.errors.name[0] : errors.data.errors.name;
              }
              if (errors.data.errors.display_name) {
                vm.errorsDisplayName = true;
                vm.displayNameError = _.isArray(errors.data.errors.display_name) ? errors.data.errors.display_name[0] : errors.data.errors.display_name;
              }
              if (errors.data.errors.description) {
                vm.errorsDescription = true;
                vm.descriptionError = _.isArray(errors.data.errors.description) ? errors.data.errors.description[0] : errors.data.errors.description;
              }
            }
          });
        }
     },
     showCreateuserRoleModal() {
       this.$refs.modal_create_userRole.show()
     },
     edituserRole(item) {
       this.userRole = item;
       this.showCreateuserRoleModal();
     },
     deleteuserRole(item, index) {
       var app = this;
       axios.delete('/userRole/'+(item.id))
        .then(function (response) {
          app.userRoles.splice(index, 1);
        })
        .catch(function (error) {
          var errors = error.response;
          if (errors.data.statusText === 'Unprocessable Entity' || errors.status === 422) {
          }
        });
     },
     onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    exportCsv() {
      axios.post('export_csv', this.users).then(res => {
        //console.log(res);
      }).catch(err => {
        //console.log(err);
      })
    }

   }
 }
</script>