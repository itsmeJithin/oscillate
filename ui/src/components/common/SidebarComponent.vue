<template>
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <router-link to="/dashboard" active-class="" class="nav-link "
        exact-active-class="collapsed">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </router-link>
      </li>
      <li class="nav-item">
        <router-link to="/import-data" active-class="" class="nav-link" exact-active-class="collapsed">
          <i class="bi bi-layout-text-window-reverse"></i>
          <span>Upload Data</span>
        </router-link>
        <a class="" href="">

        </a>
      </li>
    </ul>
  </aside>
</template>

<script>
export default {
  name: "SidebarComponent"
}
</script>

<style scoped>

</style>