<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="submit">
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
                    <div class="form-group row" >
                        <label for="display-name" class="col-sm-3 col-form-label w-100">アカウント名</label>
                        <input type="text"
                            class="col-sm-9 form-control"
                            id="display-name"
                            v-model="user.display_name"
                        >
                        <label for="display-name"
                            class="alert alert-danger p-2"
                            v-text="errors.display_name"
                            v-if="errors.display_name">
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
                errors: {}
            }
        },
        methods: {
            submit() {
                this.errors = {};
                var self = this;
                axios.post('/api/users/register', this.user)
                    .then((res) => {
                        this.$router.push({name: 'home'});
                    })
                    .catch(function(error) {
                        var responseErrors = error.response.data.errors;
                        var errors = {};

                        for(var key in responseErrors){
                            errors[key] = responseErrors[key][0];
                        }
                        self.errors = errors;
                    });
                    return false;
            },
        }
    };
</script>
