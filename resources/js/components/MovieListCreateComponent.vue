<template>
<div class="mx-auto p-2" style="max-width: 600px;">
    <div class="alert alert-success" role="alert" v-show="alertSuccess">
        リストを作成しました！
    </div>
  <div>
    <h3>マイリストの作成</h3>
    <form v-on:submit.prevent="submit">
        <div class="form-group row w-75 m-auto" >
            <label for="movieList-id" class="col-sm-3 col-form-label w-100">プレイリストURL</label>
            <input type="text"
                class="col-sm-9 form-control"
                id="movieList-id"
                v-model="videoList.id"
                placeholder="https://www.youtube.com/watch?v=xxxxxxx&list=xxxxxxxxxx"
            >
            <label for="movieList-id"
                class="alert alert-danger p-2"
                v-text="errors.id"
                v-if="errors.id">
            </label>
            <label for="movieList-id"
                class="alert alert-danger p-2"
                v-text="errors.message"
                v-if="errors.message">
            </label>
        </div>
        <button type="submit" class="btn btn-primary  w-25 m-auto mt-5">作成</button>
    </form>
    <div class="modal" tabindex="-1" ref="showModal" data-bs-backdrop="static">
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
        videoList: {},
        errors: {},
        showModalObj: null,
        alertSuccess: false,
      }
    },
    mounted() {
        this.showModalObj = new Modal(this.$refs.showModal, {keyboard: true});
    },
    created() {
    },
    methods:{
        submit() {
            var self = this;
            this.errors = {};
            self.showModalObj.show();
            self.alertSuccess = false;

            var param = {};
            param['id'] = this.videoList.id;
            axios.post('/api/user/videoList/create',param)
                .then((res) => {
                    self.showModalObj.hide();
                    self.alertSuccess = true;
                })
                .catch((error) => {
                    self.showModalObj.hide();
                    var insertError = {};
                    if(error.response.data.message === undefined){
                        insertError.id = error.response.data.errors.id[0];
                    }else{
                        insertError.message = error.response.data.message;
                    }
                    this.errors = insertError;
                });
        }
    }
  }
</script>
