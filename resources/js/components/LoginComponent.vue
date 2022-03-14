<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="login">
                    <div class="form-group row" >
                        <label for="account-name" class="col-sm-3 col-form-label w-100">アカウントID</label>
                        <input type="text"
                            class="col-sm-9 form-control"
                            id="account-name"
                            v-model="user.account_name"
                        >
                        <label for="account-name"
                            class="alert alert-danger p-2"
                            v-text="errors.account_name"
                            v-if="errors.account_name">
                        </label>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label w-100">パスワード</label>
                        <input type="password"
                            class="col-sm-9 form-control"
                            id="password"
                            v-model="user.password"
                        >
                        <label for="password"
                            class="alert alert-danger p-2"
                            v-text="errors.password"
                            v-if="errors.password">
                        </label>
                    </div>
                    <div class="form-group row">
                        <label class="alert alert-danger p-2"
                            v-text="errors.login_message"
                            v-if="errors.login_message"></label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-5">ログイン</button>
                </form>
                <div class="modal" tabindex="-1" ref="showModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p>ログイン中です</p>
                            </div>
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
        data: function() {
            return {
                user: {},
                errors: {},
                showModalObj: null,

            }
        },
        mounted() {
            this.showModalObj = new Modal(this.$refs.showModal, {keyboard: true})
        },
        methods: {
            login:function() {
                this.errors = {};
                var self = this;
                this.showModalObj.show();
                axios.get('/sanctum/csrf-cookie',{withCredentials: true}).then(response => {
                    axios.post('/api/user/login', this.user, {withCredentials: true})
                        .then(function(res) {
                            self.showModalObj.hide();
                            self.errors = res.data;
                            this.$router.push({name: 'home'});
                        }).catch(function(error){
                            self.showModalObj.hide();
                            var responseErrors = error.response.data.errors;
                            var errors = {};
                            for(var key in responseErrors){
                                errors[key] = responseErrors[key][0];
                            }
                            self.errors = errors;
                        });
                        return false;
                });
            }
        }
}


</script>
