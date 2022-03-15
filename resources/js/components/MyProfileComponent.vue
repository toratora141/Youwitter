<template>
<div class="mx-auto p-2" style="max-width: 600px;">
  <div>
    <div class="p-2">
      <label>アカウントID</label>
      <p class="mb-2">{{account_name}}</p>
      <label>アカウント名</label>
      <p>{{display_name}}</p>
    </div>
  </div>
  <form v-on:submit.prevent="submit">
        <div class="form-group row" >
            <label v-if="!value" for="account-icon" class="default col-sm-3 col-form-label w-100">
                <input type="file"
                    class="col-sm-9 form-control"
                    id="account-icon"
                    ref='file'
                    @change="upload"
                >
            </label>
            <img :src="value">
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-5">登録</button>
    </form>
    <div class="modal" tabindex="-1" ref="showModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p>アカウント作成中です</p>
                </div>
            </div>
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
        value: null,
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
    methods: {
        async upload(event) {
            const files = event.target.files || event.dataTransfer.files;
            const file = files[0];
            var self = this;
            console.log('upload event');

            if(this.checkFile(file)){
                const picture = await this.getBase64(file);
                self.value = picture;
                console.log('set picture');
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
