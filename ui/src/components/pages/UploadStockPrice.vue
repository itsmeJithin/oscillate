<template>
  <div>
    <div class="pagetitle">
      <h1>Upload stock data</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
          <li class="breadcrumb-item active">Upload Data</li>
        </ol>
      </nav>
    </div>
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Upload stock price details</h5>

              <!-- Horizontal Form -->
              <form id="import-data">
                <div class="row mb-3">
                  <label for="file" class="col-form-label">Choose required file</label>
                  <span class="text-muted fs-0-8 mt--0-6 mb-1">Only CSV format is supported</span>
                  <div class="col-sm-10">
                    <input type="file" class="form-control" id="file"
                           accept=".csv,text/csv,application/csv,text/comma-separated-values"
                           @change="handleFileUpload($event)">
                  </div>
                </div>
                <div class="text-center">
                  <button type="reset" class="btn btn-secondary" @click.prevent="reset">Reset</button>
                  <button id="submit-btn" type="submit" class="btn btn-primary ml-1" @click.prevent="upload">Submit</button>
                </div>
              </form><!-- End Horizontal Form -->

            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
const $ = window.jQuery;
const toastr = window.toastr;
export default {
  name: "UploadStockPrice",
  data() {
    return {
      file: null
    }
  },
  methods: {
    handleFileUpload(event) {
      this.file = event.target.files[0];
    },
    upload() {
      if (!this.file) {
        toastr.error("Select a valid csv file");
        return;
      }
      $('#submit-btn').loading();
      let formData = new FormData();
      formData.append("file", this.file)
      formData.append("import", "true");
      this.$axios.post('http://localhost/import_stock_details.php?XDEBUG_SESSION_START=XDEBUG_ECLIPSE', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
          .then(response => {
            $('#submit-btn').resetLoading();
            let data = response.data;
            console.log(data);
            if (data.success) {
              toastr.success("Data imported successfully");
            } else {
              toastr.success(data.message);
            }
          })
          .catch(error => {
            $('#submit-btn').resetLoading();
            console.log(error)
          })
    },
    reset() {
      this.file = null;
      $("#import-data")[0].reset();
    }
  }
}
</script>

<style scoped>

</style>