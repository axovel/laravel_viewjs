import VueRouter from 'vue-router';
import Dashboard from './components/Dashboard.vue';
import ExampleComponent from './components/ExampleComponent.vue';
import Contacts from './components/Contacts.vue';
import Owners from './components/Owners.vue';
import Users from './components/Users.vue';
import Role from './components/Role.vue';
import PermissionGroup from './components/PermissionGroup.vue';
import Permissions from './components/Permissions';
import Students from './components/Students';


let routes = [
  {
    path: '/',
    component: Dashboard
  },
  {
    path: '/example',
    component: ExampleComponent
  },
  {
    path: '/contacts',
    component: Contacts
  },
  {
    path: '/owners',
    component: Owners
  },
  {
    path: '/users',
    component: Users
  },
  {
    path: '/roles',
    component: Role
  },
  {
    path: '/permissions',
    component: Permissions
  },
  {
    path: '/permissionGroup',
    component: PermissionGroup
  },
  {
    path: '/students',
    component: Students
  }
];

export default new VueRouter({
  routes
})
