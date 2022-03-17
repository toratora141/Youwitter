<template>

<div class="mx-auto p-2" style="max-width: 600px;">
    <div class="card">
        <!-- <div class="card-header">
            {{account_name}}}
        </div> -->
        <div class="card-body">
            <div class="card-title">
                <button class="btn btn-sedondary" v-on:click="showUpdateModal">編集</button>
                <img>
                <h3 v-if="user.display_name" v-text="user.display_name"></h3>
                <h5 v-if="user.account_name" v-text="user.account_name"></h5>
            <div>
            <div class="card-text">

            </div>
            <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
        </div>
    </div>


    <div>
        <div class="p-2">
            <label>アカウントID</label>
            <p class="mb-2">{{user.account_name}}</p>
            <label>アカウント名</label>
            <p>{{user.display_name}}</p>
        </div>
    </div>
  </div>
    <div class="modal" tabindex="-1" ref="updateModal">
        <div class="modal-dialog card">
            <div class="modal-content card-header">
                <h3>編集</h3>
                <form v-on:submit.prevent="updateMyProfile" class="card-body">
                    <div class="form-group row" >
                        <img :src="user.icon" class="img-fluid img-thumdnail rounded-circle w-25 h-25 m-auto">
                        <div class="container py-3">
                            <div class="input-group custom-file-button">
                                <label v-if="!user.icon" for="account-icon" class="input-group-text w-100 justify-content-center p-0">
                                    <div class="d-flex h-50 align-content-center">
                                        <p class="text-center">アイコン画像</p>
                                        <i class="bi bi-file-person-fill fs-5"></i>
                                    </div>
                                </label>
                                <input type="file"
                                    class="form-control"
                                    id="account-icon"
                                    ref='file'
                                    @change="upload"
                                >
                            </div>
                        </div>


                    </div>
                    <div class="form-group">
                        <label for="display_name" class="col-sm-3 col-from-lable w-100">表示アカウント名</label>
                        <input type="text"
                            class="col-sm-9 form-control"
                            id="display_name"
                            v-model="user.display_name"
                            >
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-5">完了</button>
                </form>
            </div>
        </div>
    </div>

  </div>
    <div class="modal" tabindex="-10" ref="waitModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p>変更中です</p>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<style>
.custom-file-button input[type=file] {
  margin-left: -2px !important;
}

.custom-file-button input[type=file]::-webkit-file-upload-button {
  display: none;
}

.custom-file-button input[type=file]::file-selector-button {
  display: none;
}

.custom-file-button:hover label {
  background-color: #dde0e3;
  cursor: pointer;
}

</style>
<script>
import { Modal } from 'bootstrap';
  export default {
    data() {
      return {
        movie_id: '',
        auth: false,
        error: {},
        user:{},
        updateModalObj: null,
        waitModalObj: null,
      }
    },
    mounted() {
        this.updateModalObj = new Modal(this.$refs.updateModal, {keyboard: true});
        this.waitModalObj = new Modal(this.$refs.waitModal, {keyboard: true});
    },
    created() {
        var self = this;
      axios.get('/api/user/fetch')
        .then((res) => {
            var user = {};
            if (res.data.result) {
            user['account_name'] = res.data.user.account_name;
            user['display_name'] = res.data.user.display_name;
            self.user = user;
            }
        })
        .catch((error) => {
            this.error = error.response
        })
    },
    methods: {
        showUpdateModal(){
            this.updateModalObj.show();
        },
        updateMyProfile(){
            var self = this;
            var param = this.user;
            this.waitModalObj.show();

            axios.post('/api/user/update', param)
                .then((res) => {
                    this.waitModalObj.hide();
                    var message = res.data.message;
                    self.message = message;
                    self.updateMyProfile.hide();
                })
                .catch((error) => {
                    this.waitModalObj.hide();
                    // console.log(error.response.data);
                    // var error = error.response.data.errors;
                    console.log('update error');
                })
        },
        async upload(event) {
            const files = event.target.files || event.dataTransfer.files;
            const file = files[0];
            var self = this;
            console.log('upload event');

            if(this.checkFile(file)){
                const picture = await this.getBase64(file);
                self.user['icon'] = picture;
            }
        },
        getBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        },
        checkFile(file) {
            let result =  true;
            const SIZE_LIMIT = 5000000;
            if(!file) {
                result = false;
            }
            if(file.type !== 'image/jpeg' && file.type !== 'image/png') {
                result = false;
            }
            if(file.size > SIZE_LIMIT){
                result = false;
            }
            return result;
        }
    }
  }
</script>
