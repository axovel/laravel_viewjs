<template>
  <div>
    Permission view
    <download-excel
      class   = "btn btn-secondary"
      :data   = "json_data"
      :fields = "json_fields"
      name    = "Permission.xls">
      Export
    </download-excel>
    <b-button @click="showCreatePermissionModal" variant="success">Create</b-button>
    <b-modal ref="modal_create_permission" hide-footer size="lg" title="Create Permission">
      <b-form @submit="savePermission">
        <b-form-row>
          <b-form-group id="NameInputGroup"
                        label="Name:"
                        label-for="NameInputGroup">
              <b-form-input id="inputName"
                            type="text"
                            v-model="permission.name"
                            required>
              </b-form-input>
              <span v-if="errorsName" class="help-block">
                <strong>{{ nameError }}</strong>
              </span>
          </b-form-group>
          <b-form-group id="descriptionInputGroup"
                        label="Desctiption:"
                        label-for="descriptionInputGroup">
              <b-form-input id="inputDescription"
                            type="text"
                            v-model="permission.description"
                            required>
              </b-form-input>
              <span v-if="errorsDescription" class="help-block">
                <strong>{{ descriptionError }}</strong>
              </span>
          </b-form-group>
        </b-form-row>
        <b-form-row>
          <b-form-group id="typeInputGroup"
                        label="Type:"
                        label-for="typeInputGroup">
            <b-form-select id="inputType"
                          :options="types"
                          v-model="permission.type">
            </b-form-select>
          </b-form-group>
          <b-form-group id="permissionGroupInputGroup"
                        label="Permission Group:"
                        label-for="permissionGroupInputGroup">
            <b-form-select id="inputPermissionGroup"
                          :options="permisssionGroups"
                          v-model="permission.permission_group_id">
            </b-form-select>
            <span v-if="errorsPermissionGroup" class="help-block">
                <strong>{{ permissionGroupError }}</strong>
            </span>
          </b-form-group>
          <b-form-row>
          <b-form-group id="roleInputGroup"
                        label="Role:"
                        label-for="roleInputGroup">
            <b-form-select id="inputRole"
                          :options="roles"
                          v-model="permission.user_role_id">
            </b-form-select>
            <span v-if="errorsRole" class="help-block">
                <strong>{{ roleError }}</strong>
            </span>
          </b-form-group>
        </b-form-row>

        </b-form-row>
        <b-form-group id="isActiveInputGroup">
          <b-form-checkbox v-model="permission.is_blocked" id="isActive" value="1" unchecked-value="0">Is Active</b-form-checkbox>
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
              :items="permissions"
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
        <b-button class="edit-btn" size="sm" @click.stop="editPermission(row.item)"><i class="fa fa-pencil" aria-hidden="true"></i></b-button>
        <b-button class="d-btn" size="sm" @click.stop="deletePermission(row.item, row.index)"><i class="fa fa-trash" aria-hidden="true"></i></b-button>
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
       permission: {
         id: null,
         name: '',
         description: '',
         type: '',
         permission_group_id: null,
         is_blocked: 0,
         user_role_id: null
       },
       errors: [],
       permissions: [],
       fields: [{
         key: 'id',
         sortable: true
       }, {
         key: 'name',
         sortable: true
       }, {
         key: 'description',
         sortable: true
       }, {
         key: 'type',
         sortable: false
       },{
         key: 'permission_group_id',
         sortable: true
       },{
         key: 'is_blocked',
         sortable: false
       },{
         key: 'created_at',
         sortable: true
       },{
         key: 'updated_at',
         sortable: true
       },{
         key: 'action',
         sortable: false
       }],
       json_fields: {
          "Id": 'id',
          'User Role Id': 'user_role_id',
          'Permission Group Id': 'permission_group_id',
          'Name': 'name',
          'Description': 'description',
          'Type': 'type',
          'Is Blocked': 'is_blocked',
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
       permisssionGroups: [
        { value: null, text: 'Select Permission Group'}
       ],
       types: [
        { value: null, text: 'Select Type'}
       ],
       roles: [
        { value: null, text: 'Select Role'}
       ],
       filter: null,
       currentPage: 1,
       perPage: 10,
       pageOptions: [5, 10, 15],
       totalRows: 0,
       errorsName: false,
       errorsDescription: false,
       errorsPermissionGroup: false,
       errorsRole: false,
       nameError: null,
       descriptionError: null,
       permissionGroupError: null,
       roleError: null
     }
   },
   created() {
     this.totalRows = this.permisssionGroups.length;
   },
   mounted() {
     this.getPermissionGroups();
     this.getPermissions();
     this.getUserRoles();
   },
   methods: {
     clearForm() {
      for (var key in this.permission) {
        if(key === 'id' || key === 'permission_group_id' || key === 'user_role_id') {
          this.permission[key] = null;
        } else if(key === 'is_blocked') {
          this.permission[key] = 0;
        } else {
          this.permission[key] = "";
        }
      }
     },
     getPermissions() {
       axios.get('permission').then(res => {
         this.permissions = res.data.permissions;
         this.json_data = res.data.permissions;
       });
     },
     getUserRoles() {
       axios.get('userRole').then(res => {
         for(let i=0; i < res.data.userRoles.length; i++) {
           const role = { value: res.data.userRoles[i].id, text: res.data.userRoles[i].name}
           this.roles.push(role);
         }
       });
     },
     savePermission() {
        let vm = this;
        if(vm.permission.id != null && vm.permission.id != "") {
          axios.put('/permission/'+vm.permission.id, vm.permission)
          .then(function (response) {
            vm.$refs.modal_create_permission.hide();
            this.clearForm();
            this.getPermissions();
            vm.errorsName= false;
            vm.errorsDescription= false;
            vm.errorsPermissionGroup= false;
            vm.errorsRole= false;
          })
          .catch(function (error) {
            var errors = error.response;
            if (errors.statusText === 'Unprocessable Entity' || errors.status === 422) {
              if (errors.data.errors.name) {
                vm.errorsName = true;
                vm.nameError = _.isArray(errors.data.errors.name) ? errors.data.errors.name[0] : errors.data.errors.name;
              }
              if (errors.data.errors.description) {
                vm.errorsDescription = true;
                vm.descriptionError = _.isArray(errors.data.errors.description) ? errors.data.errors.description[0] : errors.data.errors.description;
              }
              if (errors.data.errors.permission_group_id) {
                vm.errorsPermissionGroup = true;
                vm.permissionGroupError = _.isArray(errors.data.errors.permission_group_id) ? errors.data.errors.permission_group_id[0] : errors.data.errors.permission_group_id;
              }
              if (errors.data.errors.user_role_id) {
                vm.errorsRole = true;
                vm.permissionGroupError = _.isArray(errors.data.errors.user_role_id) ? errors.data.errors.roleError[0] : errors.data.errors.user_role_id;
              }
            }
          });
        } else {
          axios.post('/permission', vm.permission)
          .then(function (response) {
            vm.$refs.modal_create_permission.hide();
            this.clearForm();
            this.getPermissions();
            vm.errorsName= false;
            vm.errorsDescription= false;
            vm.errorsPermissionGroup= false;
            vm.errorsRole= false;
          })
          .catch(function (error) {
            var errors = error.response;
            if (errors.statusText === 'Unprocessable Entity' || errors.status === 422) {
              if (errors.data.errors.name) {
                vm.errorsName = true;
                vm.nameError = _.isArray(errors.data.errors.name) ? errors.data.errors.name[0] : errors.data.errors.name;
              }
              if (errors.data.errors.description) {
                vm.errorsDescription = true;
                vm.descriptionError = _.isArray(errors.data.errors.description) ? errors.data.errors.description[0] : errors.data.errors.description;
              }
              if (errors.data.errors.permission_group_id) {
                vm.errorsPermissionGroup = true;
                vm.permissionGroupError = _.isArray(errors.data.errors.permission_group_id) ? errors.data.errors.permission_group_id[0] : errors.data.errors.permission_group_id;
              }
              if (errors.data.errors.user_role_id) {
                vm.errorsRole = true;
                vm.permissionGroupError = _.isArray(errors.data.errors.user_role_id) ? errors.data.errors.roleError[0] : errors.data.errors.user_role_id;
              }
            }
          });
        }
     },
     showCreatePermissionModal() {
       this.$refs.modal_create_permission.show()
     },
     getPermissionGroups() {
       axios.get('permission_group').then(res => {
         for(let i=0; i < res.data.permissionGroups.length; i++) {
           const tempPermission = { value: res.data.permissionGroups[i].id, text: res.data.permissionGroups[i].name}
           this.permisssionGroups.push(tempPermission);
         }
       });
     },
     editPermission(item) {
       this.permission = item;
       this.showCreatePermissionModal();
     },
     deletePermission(item, index) {
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
            axios.delete('/permission/'+(item.id))
            .then(function (response) {
              swal(
                'Deleted!',
                'Your file has been deleted.',
                'success'
              );
              app.getPermissions();
            })
            .catch(function (error) {
              var errors = error.response;
              swal({
                type: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
              });
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
      axios.post('export_csv', this.users).then(res => {
      }).catch(err => {
      })
    }

   }
 }
</script>
