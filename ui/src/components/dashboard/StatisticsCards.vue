<template>
  <div class="row">
    <div class="col-xxl-4 col-md-6">
      <div class="card info-card sales-card">
        <div class="card-body">
          <h5 class="card-title">Mean Stock Price</h5>

          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-cash-coin"></i>
            </div>
            <div class="ps-3">
              <h6>{{mean}}</h6>
            </div>
          </div>
        </div>

      </div>
    </div>
    <div class="col-xxl-4 col-md-6">
      <div class="card info-card revenue-card">
        <div class="card-body">
          <h5 class="card-title">Standard Deviation</h5>

          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-x-square"></i>
            </div>
            <div class="ps-3">
              <h6>{{sd}}</h6>
            </div>
          </div>
        </div>

      </div>
    </div>
    <div class="col-xxl-4 col-xl-12">
      <div class="card info-card customers-card">
        <div class="card-body">
          <h5 class="card-title">Profit</h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="ps-3">
              <h6>{{profit}}</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "StatisticsCards",
  props: {
    profit: {
      default: "0.00"
    },
    sd: {
      default: "0.00"
    },
    mean: {
      default: "0.00"
    }
  }
}
</script>

<style scoped>

</style>