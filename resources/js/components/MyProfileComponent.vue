<template>
<div class="mx-auto p-2" style="max-width: 600px;">

  <!-- <div
    no-body
    header="ユーザ情報"
    header-bg-variant="primary"
    header-text-variant="white"
  > -->
  <div>
  account info
    <div class="p-2">
      <label>Name</label>
      <p class="mb-2">{{user.account_name}}</p>
      <label>Email</label>
      <p>{{user.display_name}}</p>
    </div>
    <div class="p-2 text-danger">
      {{error}}
    </div>
  </div>

</div>
</template>

<script>
  export default {
    data() {
      return {
        user: {},
        auth: false,
        error: {},
      }
    },
    created() {
      axios.get('/api/user')
      .then((res) => {
        if (res.data.result) {
          this.user = res.data.user
          this.auth = true
        }
          console.log(res);
      })
      .catch((err) => {
          console.log(err);
        this.error = err.response
      })
    },
  }
</script>
