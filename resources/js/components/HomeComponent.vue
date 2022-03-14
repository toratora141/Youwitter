<template>
<div class="mx-auto p-2" style="max-width: 600px;">
    <div class="alert alert-dark" role="alert" v-show="checkUser">
        ログインされていません。<br>
        <router-link v-bind:to="{name:'user.login'}">
            <button class="btn btn-primary">ログイン</button>
        </router-link>

        <router-link v-bind:to="{name:'user.register'}">
            <button class="btn btn-primary" href="#">アカウント作成</button>
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
      axios.get('/api/gUser')
      .then((res) => {
          var self = this;
        if (atempt(res.data.user)) {
            console.log(res.data);
            self.checkUser = true;
        }
      })
      .catch((err) => {
          self.checkUser = true;
      })
    },
  }
</script>
