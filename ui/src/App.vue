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
    }
  }
}
</script>

<style>

</style>
