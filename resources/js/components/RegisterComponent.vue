<template>
    <div class>
        <div class="card-header">
            <h4 class="m-auto pt-2 pb-2 text-center">登録</h4>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="submit">
                    <div class="form-group row w-75 m-auto" >
                        <label for="account-name" class="col-sm-3 col-form-label w-100">ID</label>
                        <input type="text"
                            class="col-sm-9 form-control"
                            id="account-name"
                            placeholder="例：taro"
                            v-model="user.account_name"
                        >
                        <label for="account-name"
                            class="alert alert-danger p-2"
                            v-text="errors.account_name"
                            v-if="errors.account_name">
                        </label>
                    </div>
                    <div class="form-group row w-75 m-auto" >
                        <label for="display-name" class="col-sm-3 col-form-label w-100">名前</label>
                        <input type="text"
                            class="col-sm-9 form-control"
                            id="display-name"
                            placeholder="例：太郎"
                            v-model="user.display_name"
                        >
                        <label for="display-name"
                            class="alert alert-danger p-2"
                            v-text="errors.display_name"
                            v-if="errors.display_name">
                        </label>
                    </div>
                    <div class="form-group row w-75 m-auto">
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
                    <div class="form-group row w-75 m-auto">
                        <label for="password-confirm" class="col-sm-3 col-form-label w-100">パスワード再確認</label>
                        <input type="password"
                            class="col-sm-9 form-control"
                            id="password-confirm"
                            v-model="user.password_confirm"
                        >
                        <label for="password-confirm"
                            class="alert alert-danger p-2"
                            v-text="errors.password_confirm"
                            v-if="errors.password_confirm">
                        </label>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-25 mt-5">登録</button>
                    </div>
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
        </div>
    </div>
</template>

<script>
import { Modal } from 'bootstrap';

    export default {
        data() {
            return {
                user: {},
                errors: {},
                showModalObj: null,
            }
        },
        mounted() {
            this.showModalObj = new Modal(this.$refs.showModal, {keyboard: true})
        }
        ,
        methods: {
            submit() {
                this.errors = {};
                var self = this;
                this.showModalObj.show();
                axios.post('/api/users/register', this.user)
                    .then((res) => {
                        this.login();
                        this.$store.commit('login',res.data);
                    })
                    .catch(function(error) {
                        self.showModalObj.hide();
                        var responseErrors = error.response.data.errors;
                        var errors = {};

                        for(var key in responseErrors){
                            errors[key] = responseErrors[key][0];
                        }
                        self.errors = errors;
                        self.showModalObj.hide();
                    });
                    return false;
            },
            login() {
                this.errors = {};
                var self = this;
                axios.get('/sanctum/csrf-cookie',{withCredentials: true}).then(response => {
                    axios.post('/api/user/login', this.user, {withCredentials: true})
                        .then((res) => {
                            this.showModalObj.hide();
                            if(res.data.result){
                                this.errors = res.data;
                                this.$store.commit('login',res.data);
                                this.$router.push({name: 'home'});
                            }
                            this.errors = res.data;
                        }).catch((error) => {
                            this.showModalObj.hide();
                        });
                });
            }
        }
    };
</script>
