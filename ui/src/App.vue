<template>
  <div id="app">
    <HeaderComponent/>
    <SidebarComponent/>
    <main id="main" class="main">
      <component :is="dynamicComponent"/>
    </main>
  </div>
</template>

<script>
import Vue from "vue";
import VueRouter from 'vue-router';
import SidebarComponent from "@/components/common/SidebarComponent";
import HeaderComponent from "@/components/common/HeaderComponent";
import UploadStockPrice from "@/components/pages/UploadStockPrice";
import DashboardComponent from "@/components/pages/DashboardComponent";

Vue.use(VueRouter);
export default {
  name: 'App',
  components: {
    UploadStockPrice,
    HeaderComponent,
    SidebarComponent,
    DashboardComponent
  },
  data() {
    return {
      dynamicComponent: DashboardComponent
    }
  },
  computed: {
    routeMetaData() {
      return this.$router.currentRoute.meta
    }
  },
  mounted() {
    this.findComponent();
    this.initialiseSidebar();
  },
  watch: {
    $route(to) {
      this.findComponent(to.meta);
    }
  },
  methods: {
    findComponent(metaData) {
      const data = metaData || this.routeMetaData;
      if (data) {
        const {code: menuCode} = data;
        switch (menuCode) {
          case "DASHBOARD": {
            this.dynamicComponent = DashboardComponent;
            break;
          }
          case "IMPORT_DATA": {
            this.dynamicComponent = UploadStockPrice;
            break;
          }
        }
      }
    },
    initialiseSidebar() {
      const select = (el, all = false) => {
        el = el.trim()
        if (all) {
          return [...document.querySelectorAll(el)]
        } else {
          return document.querySelector(el)
        }
      }
      /**
       * Easy event listener function
       */
      const on = (type, el, listener, all = false) => {
        if (all) {
          select(el, all).forEach(e => e.addEventListener(type, listener))
        } else {
          select(el, all).addEventListener(type, listener)
        }
      }
      if (select('.toggle-sidebar-btn')) {
        on('click', '.toggle-sidebar-btn', ()=> {
          select('body').classList.toggle('toggle-sidebar')
        })
      }
    }
  }
}
</script>

<style>

</style>
