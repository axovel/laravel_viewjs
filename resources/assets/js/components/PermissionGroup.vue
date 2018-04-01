<template>
  <div>
    Permission Group view
    <download-excel
      class   = "btn btn-secondary"
      :data   = "json_data"
      :fields = "json_fields"
      name    = "PermissionGroup.xls">
      Export
    </download-excel>
    <b-button @click="showCreatePermissionGroupModal" variant="success">Create</b-button>
    <b-modal ref="modal_create_permissionGroup" hide-footer size="lg" title="Create Permission Group">
      <b-form @submit="savePermissionGroup">
        <b-form-row>
          <b-form-group id="NameInputGroup"
                        label="Name:"
                        label-for="NameInputGroup">
              <b-form-input id="inputName"
                            type="text"
                            v-model="permissionGroup.name"
                            required>
              </b-form-input>
              <span v-if="errorsName" class="help-block">
                <strong>{{ nameError }}</strong>
              </span>
          </b-form-group>
          <b-form-group id="descriptionInputGroup"
                        label="Description:"
                        label-for="descriptionInputGroup">
              <b-form-input id="inputDescription"
                            type="text"
                            v-model="permissionGroup.description"
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
              :items="permissionGroups"
              :fields="fields"
              :filter="filter"
              :current-page="currentPage"
              :per-page="perPage"
              @filtered="onFiltered">
      <template slot="action" slot-scope="row">
        <b-button class="edit-btn" size="sm" @click.stop="editPermissionGroup(row.item)"><i class="fa fa-pencil" aria-hidden="true"></i></b-button>
        <b-button class="d-btn" size="sm" @click.stop="deletePermissionGroup(row.item, row.index)"><i class="fa fa-trash" aria-hidden="true"></i></b-button>
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
       permissionGroup: {
         id: null,
         name: '',
         description: '',
       },
       errors: [],
       permissionGroups: [],
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
          'Name': 'name',
          'Description': 'description',
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
       errorsName: false,
       errorsDisplayName: false,
       errorsDescription: false,
       nameError: null,
       displayNameError: null,
       descriptionError: null
     }
   },
   created() {
     this.totalRows = this.permissionGroups.length;
   },
   mounted() {
     this.getPermissionGroups();
   },
   methods: {
     clearForm() {
      for (var key in this.permissionGroup) {
        if(key === 'id') {
          this.permissionGroup[key] = null;
        } else {
          this.permissionGroup[key] = "";
        }
      }
     },
     getPermissionGroups() {
       axios.get('permission_group').then(res => {
         this.permissionGroups = res.data.permissionGroups;
         this.json_data = res.data.permissionGroups;
       });
     },
     savePermissionGroup() {
        let vm = this;
        if(vm.permissionGroup.id != null && vm.permissionGroup.id != "") {
          axios.put('/permission_group/'+vm.permissionGroup.id, vm.permissionGroup)
          .then(function (response) {
            vm.$refs.modal_create_permissionGroup.hide();
            this.clearForm();
            this.getPermissionGroups();
            vm.errorsName = false;
            vm.errorsDescription = false;
          }).catch(function (error) {
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
            }
          });
        } else {
          axios.post('/permission_group', vm.permissionGroup)
          .then(function (response) {
            vm.$refs.modal_create_permissionGroup.hide();
            this.clearForm();
            this.getPermissionGroups();
            vm.errorsName = false;
            vm.errorsDescription = false;
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
            }
          });
        }
     },
     showCreatePermissionGroupModal() {
       this.$refs.modal_create_permissionGroup.show()
     },
     editPermissionGroup(item) {
       this.permissionGroup = item;
       this.showCreatePermissionGroupModal();
     },
     deletePermissionGroup(item, index) {
      var app = this;
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
          axios.delete('/permission_group/'+(item.id))
          .then(function (response) {
            swal(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            );
            app.permissionGroups.splice(index, 1);
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
        console.log(res);
      }).catch(err => {
        console.log(err);
      })
    }

   }
 }
</script>
