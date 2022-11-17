<template>
  <div>
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div>
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Track Company</h5>

              <!-- Horizontal Form -->
              <form id="import-data">
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
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <template v-if="companyData">
                <CompanyPriceStockList :start-date="startDate" :end-date="endDate"/>
              </template>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import CompanyPriceStockList from "@/components/dashboard/CompanyPriceStockList";

const toastr = window.toastr;
export default {
  name: "DashboardComponent",
  components: {CompanyPriceStockList},
  data() {
    return {
      startDate: "",
      endDate: "",
      companies: [],
      selectedCompany: "",
      companyData: null
    }
  },
  mounted() {
    this.getAllCompany();
  },
  methods: {
    getAllCompany() {
      this.$axios.get('http://localhost/dashboard.php?action=get-basic-details&XDEBUG_SESSION_START=XDEBUG_ECLIPSE')
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

    },
    upload() {
      const data = {
        "startDate": this.startDate,
        "endDate": this.endDate,
        "companyId": this.selectedCompany
      }
      const headers = {
        'Content-Type': 'application/x-www-form-urlencoded'
      }
      this.$axios.post('http://localhost/dashboard.php?action=track-data&XDEBUG_SESSION_START=XDEBUG_ECLIPSE',
          data, {headers: headers})
          .then(response => {
            const responseData = response.data;
            console.log(responseData);
            if (responseData.success) {
              this.companyData = responseData.data;
            } else {
              toastr.error(responseData.message);
            }
          })
          .catch(() => {
            toastr.error("Error occurred while fetching the details. Try again later");
          })

    }
  }
}
</script>

<style scoped>

</style>