<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="submit">
                    <div class="form-group row">
                        <label for="account_name" class="col-sm-3 col-form-label w-100">アカウントID</label>
                        <input type="text" class="col-sm-9 form-control" id="account-name" v-model="user.account_name">
                    </div>
                    <div class="form-group row">
                        <label for="display_name" class="col-sm-3 col-form-label w-100">アカウント名</label>
                        <input type="text" class="col-sm-9 form-control" id="display-name" v-model="user.display_name">
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label w-100">パスワード</label>
                        <input type="password" class="col-sm-9 form-control" id="password" v-model="user.password">
                    </div>
                    <!-- <div class="form-group row">
                        <label for="password-comform" class="col-sm-3 col-form-label w-100">パスワード再確認</label>
                        <input type="password" class="col-sm-9 form-control" id="password-comform" v-model="user.password_comform">
                    </div> -->
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
                user: {}
            }
        },
        methods: {
            submit() {
                axios.post('/api/users/register', this.user)
                    .then((res) => {
                        this.$router.push({name: 'home'});
                    });
            }
        }
    };
</script>
