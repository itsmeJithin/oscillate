<template>
  <div>
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div>
    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Track Company</h5>
              <form id="filter-data">
                <div class="row mb-3">
                  <div class="col-4">
                    <label for="company-name" class="col-form-label">Company</label>
                    <select class="form-control" name="company-name" id="company-name" v-model="selectedCompany">
                      <option value="" disabled selected>Select company</option>
                      <option :value="company.id" v-for="company in companies" :key="company.id">
                        {{ company.name }}
                      </option>
                    </select>
                  </div>
                  <div class="col-4">
                    <label for="start-date" class="col-form-label">Start Date</label>
                    <input class="form-control" type="date" name="company-name" id="start-date"
                           placeholder="Select start date" v-model="startDate"/>
                  </div>
                  <div class="col-4">
                    <label for="end-date" class="col-form-label">End Date</label>
                    <input class="form-control" type="date" name="company-name" id="end-date"
                           placeholder="Select end date" v-model="endDate"/>
                  </div>
                </div>
                <div class="text-center">
                  <button type="reset" class="btn btn-secondary" @click.prevent="reset">Reset</button>
                  <button id="submit-btn" type="submit" class="btn btn-primary ml-1" @click.prevent="upload">Submit
                  </button>
                </div>
              </form><!-- End Horizontal Form -->

            </div>
          </div>
        </div>
      </div>
      <StatisticsCards v-if="companyData" :mean="mean" :sd="sd" :profit="maxProfit"/>
      <div class="row" v-if="companyData">
        <div class="col-12">
          <div class="card top-selling overflow-auto">
            <div class="card-body pb-0">
              <h5 class="card-title">Top Trading <span>| between {{ startDate }} and {{ endDate }}</span></h5>
              <StockAnalysisReport :start-date="startDate" :end-date="endDate" :traded-stocks="tradedStocks"
                                   :max-profit="maxProfit"/>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="card">
            <CompanyPriceStockList :start-date="startDate" :end-date="endDate" :company="companyData"/>
          </div>
        </div>
      </div>
      <div class="row hidden" v-if="!companyData" id="no-data-view">
        <div class="col-12">
          <div class="card top-selling overflow-auto">
            <div class="card-body p-3 text-center">
              <h5 class="text-muted">No data available</h5>
              <span class="text-muted">
                No details available for the given input. Try again with different values.
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import CompanyPriceStockList from "@/components/dashboard/CompanyPriceStockList";
import StockAnalysisReport from "@/components/dashboard/StockAnalysisReport";
import StatisticsCards from "@/components/dashboard/StatisticsCards";

const toastr = window.toastr;
const $ = window.jQuery;
export default {
  name: "DashboardComponent",
  components: {StatisticsCards, CompanyPriceStockList, StockAnalysisReport},
  data() {
    return {
      startDate: "",
      endDate: "",
      companies: [],
      selectedCompany: "",
      companyData: null,
      tradedStocks: [],
      maxProfit: 0,
      mean: 0,
      sd: 0
    }
  },
  mounted() {
    this.getAllCompany();
  },
  methods: {
    getAllCompany() {
      this.$axios.get('dashboard.php?action=get-basic-details&XDEBUG_SESSION_START=XDEBUG_ECLIPSE')
          .then(response => {
            const responseData = response.data;
            if (responseData.success) {
              this.companies = responseData.data;
            } else {
              toastr.error(responseData.message);
            }
          })
          .catch(() => {
            toastr.error("Error occurred while fetching the companies details. Try again later");
          })
    },
    reset() {
      this.tradedStocks = [];
      this.maxProfit = 0;
      this.mean = 0;
      this.sd = 0;
      this.companyData = null
      this.selectedCompany = "";
      this.startDate = "";
      this.endDate = "";
      $('#filter-data')[0].reset()
    },
    upload() {
      if (!this.selectedCompany) {
        toastr.error("Select a company");
        return;
      }
      if (this.startDate === "" || this.endDate === "") {
        toastr.error("Select a valid date range");
        return;
      }
      $('#submit-btn').loading();
      $('#no-data-view').addClass("hidden");
      const data = {
        "startDate": this.startDate,
        "endDate": this.endDate,
        "companyId": this.selectedCompany
      }
      const headers = {
        'Content-Type': 'application/x-www-form-urlencoded'
      }
      this.$axios.post('dashboard.php?action=track-data&XDEBUG_SESSION_START=XDEBUG_ECLIPSE',
          data, {headers: headers})
          .then(response => {
            $('#submit-btn').resetLoading();
            const responseData = response.data;
            console.log(responseData);
            if (responseData.success) {
              this.companyData = responseData.data.company;
              this.tradedStocks = responseData.data.tradedStocks;
              this.maxProfit = responseData.data.maxProfit;
              this.mean = responseData.data.mean;
              this.sd = responseData.data.sd;
              $('#no-data-view').removeClass("hidden");
            } else {
              toastr.error(responseData.message);
            }
          })
          .catch(() => {
            $('#no-data-view').removeClass("hidden");
            $('#submit-btn').resetLoading();
            toastr.error("Error occurred while fetching the details. Try again later");
          })

    }
  }
}
</script>

<style scoped>

</style>