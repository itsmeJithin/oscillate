<template>
  <div class="card-body">
    <h5 class="card-title">Stock prices between {{ startDate }} and {{ endDate }}</h5>
    <table class="table table-sm">
      <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">date</th>
        <th scope="col">Price</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for=" (stock,index) in company.stocks" :key="stock.id">
        <th scope="row">{{ index + 1 }}</th>
        <td>{{ stock.date }}</td>
        <td>{{ stock.price }}</td>
      </tr>
      </tbody>
    </table>
    <!-- End small tables -->

  </div>
</template>

<script>
export default {
  name: "CompanyPriceStockList",
  props: {
    startDate: {
      required: true,
      default: ""
    },
    endDate: {
      required: true,
      default: ""
    },
    company: {
      default: null
    }
  }
}
</script>

<style scoped>

</style>