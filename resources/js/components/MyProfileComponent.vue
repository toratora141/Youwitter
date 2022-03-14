<template>
<div class="mx-auto p-2" style="max-width: 600px;">

  <!-- <div
    no-body
    header="ユーザ情報"
    header-bg-variant="primary"
    header-text-variant="white"
  > -->
  <div>
    <div class="p-2">
      <label>アカウントID</label>
      <p class="mb-2">{{account_name}}</p>
      <label>アカウント名</label>
      <p>{{display_name}}</p>
    </div>
  </div>

</div>
</template>

<script>
  export default {
    data() {
      return {
        account_name: '',
        display_name: '',
        movie_id: '',
        auth: false,
        error: {},
      }
    },
    created() {
        var self = this;
      axios.get('/api/user')
      .then((res) => {
          var user = {};
        if (res.data.result) {
          user['account_name'] = res.data.account_name;
          user['display_name'] = res.data.display_name;
          this.auth = true
        }
          self.account_name = res.data.account_name;
          self.display_name = res.data.display_name;
      })
      .catch((error) => {
        this.error = error.response
      })
    },
  }
</script>
