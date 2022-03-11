<template>


<div class="mx-auto p-2" style="max-width: 600px;">
    <div class="card w-75" v-if="errors.unauth">
        <div class="card-body">
            <p class="card-text">ログインされていません</p>
            <a class="btn btn-primary">ログイン</a>
            <a class="btn btn-primary">新規登録</a>
        </div>
    </div>
  <div>
  account info
    <div class="p-2">
      <label>アカウントID</label>
      <p class="mb-2">{{account_name}}</p>
    </div>
    <form v-on:submit.prevent="submit">
        <div class="form-group row" >
            <label for="movieList-id" class="col-sm-3 col-form-label w-100">プレイリストURL</label>
            <input type="text"
                class="col-sm-9 form-control"
                id="movieList-id"
                v-model="movieList_id"
            >
            <label for="movieList-id"
                class="alert alert-danger p-2"
                v-text="errors.movieList_id"
                v-if="errors.movieList_id">
            </label>
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-5">登録</button>
    </form>
  </div>

</div>
</template>

<script>
  export default {
    data() {
      return {
        account_name: '',
        movieList_id: '',
        auth: false,
        errors: {},
      }
    },
    created() {
        var self = this;
      axios.get('/api/user')
        .then((res) => {
            var user = {};
            if (res.data.result) {
            user['account_name'] = res.data.account_name;
            this.auth = true
            }
            self.account_name = res.data.account_name;

        })
      .catch((error) => {
        if(error.response.data.message ==="Unauthenticated."){
            var errors = {};
            errors['unauth'] = 'ログインされていません。';
            self.errors = errors;
        }
        // this.errors = error.response
      })
    },
    methods:{
        submit() {
            var self = this;
            this.errors = {};
            var param = {};
            param['id'] = this.movieList_id;
            param['user_id'] = this.account_name;
            axios.post('/api/user/movieList/create',param)
                .then((res) => {
                    console.log(res);
                    console.log('リストを作成！')
                })
                .catch((error) => {
                    var errors_temp = {};
                    console.log("Error!!!");
                    console.log(error.response.data.errors['id'][0]);
                    //送信後、画面に表示させるため一度tempに格納
                    errors_temp['movieList_id'] = error.response.data.errors['id'][0];
                    self.errors = errors_temp;
                });
        }
    }
  }
</script>
