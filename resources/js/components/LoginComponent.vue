<template>
    <div class>
        <div class="card-header">
            <h4 class="m-auto pt-2 pb-2 text-center">ログイン</h4>
        </div>
        <div class="alert alert-primary text-center" role="alert" v-if="noLoggedInAlert">
            <p>この機能を使うにはログインか、アカウントの作成をしてください。</p>
            <router-link v-bind:to="{name:'user.register'}">
                <button class="btn btn-secondary">アカウント作成</button>
            </router-link>
        </div>
        <div class="justify-content-center">
            <div class="col-sm-6 p-2 m-auto w-75">
                <form v-on:submit.prevent="login">
                    <div class="row form-group m-auto" >
                        <label for="account-name" class="col-sm-3 col-form-label w-100">ID</label>
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
                    <div class="row form-group m-auto">
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
                    <div class="row form-group m-auto">
                        <label class="alert alert-danger p-2"
                            v-text="errors.login_message"
                            v-if="errors.login_message"></label>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mt-5">ログイン</button>
                    </div>
                </form>
                <div class="modal" tabindex="-1" ref="showModal" data-bs-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <p class="m-0">ログイン中です</p>
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
                noLoggedInAlert: this.$store.state.noLoggedInAlert,
            }
        },
        mounted() {
            this.showModalObj = new Modal(this.$refs.showModal, {keyboard: true});
        },
        methods: {
            login() {
                this.errors = {};
                var self = this;
                this.showModalObj.show();
                axios.get('/sanctum/csrf-cookie',{withCredentials: true}).then(response => {
                    axios.post('/api/user/login', this.user, {withCredentials: true})
                        .then((res) => {
                            self.showModalObj.hide();
                            if(res.data.result){
                                self.errors = res.data;
                                this.$store.commit('login',res.data);
                                this.$router.push({name: 'home'});
                            }
                            this.errors = res.data;
                        }).catch((error) => {
                            self.showModalObj.hide();
                        });
                });
            }
        }
}


</script>
