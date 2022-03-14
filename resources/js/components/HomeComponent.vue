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
  <div
    no-body
    header="ユーザ情報"
    header-bg-variant="primary"
    header-text-variant="white"
  >
  account info
    <div v-if="auth" class="p-2">
      <label>Name</label>
      <p class="mb-2">{{user.name}}</p>
      <label>Email</label>
      <p>{{user.email}}</p>
    </div>
    <div v-else class="p-2 text-danger">
      {{error.status}} {{error.statusText}}
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
