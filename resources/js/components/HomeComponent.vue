<template>
<div class="mx-auto p-2 justify-content-center w-50" style="max-width: 600px;">
    <div class="alert alert-dark" role="alert" v-show="checkUser">
        ログインされていません。<br>
        <router-link v-bind:to="{name:'user.login'}">
            <button class="btn btn-secondary">ログイン</button>
        </router-link>

        <router-link v-bind:to="{name:'user.register'}">
            <button class="btn btn-secondary" href="#">アカウント作成</button>
        </router-link>
    </div>

    作成中

</div>
</template>

<script>
  export default {
    data() {
      return {
        user: {},
        auth: false,
        error: {},
        checkUser: false,
      }
    },
    created() {
        var self = this;
      axios.get('/api/user/gUser')
      .then((res) => {
          console.log(res.data);
        if (!res.data.result) {
            self.checkUser = true;
        }
      })
      .catch((err) => {
          console.log('error');
          self.checkUser = true;
      })
    },
  }
</script>
