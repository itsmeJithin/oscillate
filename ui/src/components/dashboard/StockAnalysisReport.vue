<template>


  <table class="table table-striped table-bordered">
    <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Number of stocks</th>
      <th scope="col">Buy On</th>
      <th scope="col">Buying price</th>
      <th scope="col">Total buying price</th>
      <th scope="col">Sell On</th>
      <th scope="col">Selling Price</th>
      <th scope="col">Total selling price</th>
      <th scope="col">Profit</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="(stock,index) in tradedStocks" :key="index+1">
      <td>{{ index + 1 }}</td>
      <td>{{ stock.numberOfStocks }}</td>
      <td>{{ stock.boughtOn }}</td>
      <td>{{ stock.boughtPrice }}</td>
      <td>{{ stock.boughtPrice * stock.numberOfStocks }}</td>
      <td>{{ stock.soldOn }}</td>
      <td>{{ stock.soldPrice }}</td>
      <td>{{ stock.soldPrice * stock.numberOfStocks }}</td>
      <td class="fw-bold">{{
          (stock.soldPrice * stock.numberOfStocks) - (stock.boughtPrice * stock.numberOfStocks)
        }}
      </td>
    </tr>
    <tr>
      <td colspan="8" class="fw-bold align-right">Total Profit</td>
      <td class="fw-bold">{{ maxProfit }}</td>
    </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  name: "StockAnalysisReport",
  props: {
    startDate: {
      required: true,
      default: ""
    },
    endDate: {
      required: true,
      default: ""
    },
    tradedStocks: {
      default() {
        return []
      }
    },
    maxProfit: {
      default: "0.00"
    }
  }
}
</script>

<style scoped>

</style>