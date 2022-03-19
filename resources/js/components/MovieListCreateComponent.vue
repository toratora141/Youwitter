<template>
<div class="mx-auto p-2" style="max-width: 600px;">
    <div class="alert alert-success" role="alert" v-show="alertSuccess">
        リストを作成しました！
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
                placeholder="https://www.youtube.com/watch?v=xxxxxxx&list=xxxxxxxxxx"
            >
            <label for="movieList-id"
                class="alert alert-danger p-2"
                v-text="errors.movieList_id"
                v-if="errors.movieList_id">
            </label>
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-5">登録</button>
    </form>
    <div class="modal" tabindex="-1" ref="showModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p>作成中です</p>
                </div>
            </div>
        </div>
    </div>
  </div>

</div>
</template>

<script>
import { Modal } from 'bootstrap';

  export default {
    data() {
      return {
        account_name: '',
        movieList_id: '',
        auth: false,
        errors: {},
        showModalObj: null,
        alertSuccess: false,
      }
    },
    mounted() {
        this.showModalObj = new Modal(this.$refs.showModal, {keyboard: true})
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
            })
    },
    methods:{
        submit() {
            var self = this;
            this.errors = {};
            self.showModalObj.show();
            self.alertSuccess = false;

            var param = {};
            param['id'] = this.movieList_id;
            param['user_id'] = this.account_name;
            axios.post('/api/user/movieList/create',param)
                .then((res) => {
                    self.showModalObj.hide();
                    self.alertSuccess = true;
                    console.log(res.data.data);
                })
                .catch((error) => {
                    self.showModalObj.hide();
                    var errors_temp = {};
                    //送信後、画面に表示させるため一度tempに格納
                    // errors_temp['movieList_id'] = error.response.data.errors['id'][0];
                    console.log(error.response.data.message);
                    self.errors = errors_temp;
                });
        }
    }
  }
</script>
