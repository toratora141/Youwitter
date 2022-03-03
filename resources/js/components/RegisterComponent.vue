<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="submit">
                    <div class="form-group row" v-bind:class="{ 'is-invalid': errors.account_name }">
                        <label for="account-name" class="col-sm-3 col-form-label w-100">アカウントID</label>
                        <input type="text"
                            class="col-sm-9 form-control is-invalid"
                            id="account-name"
                            v-model="user.account_name"
                            v-bind:class="{'is-invalid': errors.password_confirm}"
                        >
                        <label class="invalid-feedback"
                                role="alert"
                                for="account-name"
                                v-if="errors.account_name"
                                v-html="errors.account_name">
                        </label>
                    </div>
                    <div class="form-group row" v-bind:class="{ 'is-invalid': errors.display_name }">
                        <label for="display-name" class="col-sm-3 col-form-label w-100">アカウント名</label>
                        <input type="text"
                            class="col-sm-9 form-control is-invalid"
                            id="display-name"
                            v-model="user.display_name"
                            v-bind:class="{'is-invalid': errors.password_confirm}">
                        <label class="invalid-feedback"
                                role="alert"
                                for="display-name"
                                v-if="errors.display_name"
                                v-html="errors.display_name">
                        </label>
                    </div>
                    <div class="form-group row" v-bind:class="{ 'is-invalid': errors.password }">
                        <label for="password" class="col-sm-3 col-form-label w-100">パスワード</label>
                        <input type="password"
                            class="col-sm-9 form-control is-invalid"
                            id="password" v-model="user.password"
                            v-bind:class="{'is-invalid': errors.password_confirm}">
                        <label class="invalid-feedback"
                                role="alert"
                                for="password"
                                v-if="errors.password"
                                v-html="errors.password">
                        </label>
                    </div>
                    <div class="form-group row" v-bind:class="{ 'is-invalid': errors.password_confirm }">
                        <label for="password-conform" class="col-sm-3 col-form-label w-100">パスワード再確認</label>
                        <input type="password"
                            class="col-sm-9 form-control is-invalid"
                            id="password-conform"
                            v-model="user.password_conform"
                            v-bind:class="{'is-invalid': errors.password_confirm}">
                        <label class="invalid-feedback"
                                role="alert"
                                for="password-conform"
                                v-if="errors.password_conform"
                                v-html="errors.password_conform">
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-5">登録</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                user: {},
                errors: {
                    account_name: "",
                    display_name: "",
                    password: "",
                    password_confirm: "",
                }
            }
        },
        methods: {
            submit() {
                axios.post('/api/users/register', this.user)
                    .then((res) => {
                        this.$router.push({name: 'home'});
                    })
                    .catch(response => {
                        var errors = response.response.data.errors;
                         this.errors.account_name = ( errors.account_name ? errors.account_name[0] : "" );
                         this.errors.display_name = ( errors.display_name ? errors.display_name[0] : "" );
                         this.errors.password = ( errors.password ? errors.password[0] : "" );
                         this.errors.password_confirm = ( errors.password_confirm ? errors.password_confirm[0] : "" );
                    });
                    return false;
            },
        }
    };
</script>
