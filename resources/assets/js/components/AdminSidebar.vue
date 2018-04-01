<template>
  <div>
    <nav id="sidebar">
    <div class="sidebar-header">
      <h3>Main Menu</h3>
    </div>
    <ul class="list-unstyled components" v-if="userRole === 1">
      <li class="active"><router-link to='/'>Dashboard</router-link></li>
      <li>
        <a href="#adminSubmenu" data-toggle="collapse" aria-expanded="false">
          Administration
        </a>
        <ul class="collapse list-unstyled" id="adminSubmenu">
          <li><router-link to='/owners'>Owners</router-link></li>
          <li><router-link to="/users">Users</router-link></li>
          <li><router-link to="/contacts">Contacts</router-link></li>
        </ul>
      </li>
      <li>
          <a href="#systemSubmenu" data-toggle="collapse" aria-expanded="false">
            System
          </a>
          <ul class="collapse list-unstyled" id="systemSubmenu">
            <li><router-link to="/permissionGroup">Permission Groups</router-link></li>
            <li><router-link to="/permissions">Permissions</router-link></li>
            <li><router-link to="/roles">Roles</router-link></li>
            <li>Logs</li>
            <li>Localisation</li>
            <li>Tutorials</li>
          </ul>
      </li>
      <li>Data Import</li>
    </ul>
    <ul class="list-unstyled components" v-else-if="userRole === 4">
      <li class="active"><router-link to='/'>Dashboard</router-link></li>
      <li>
        <a href="#adminSubmenu" data-toggle="collapse" aria-expanded="false">
          Administration
        </a>
        <ul class="collapse list-unstyled" id="adminSubmenu">
          <li><router-link to='/students'>Students</router-link></li>
        </ul>
      </li>
    </ul>
    </nav>
  </div>
</template>

<script>
  
  export default {
    data() {
      return {
        userRole: Laravel.userRole
      }
    },
    mounted() {
    }
  }
</script>

