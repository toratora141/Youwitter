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
                    <button type="submit" class="btn btn-primary w-100 mt-5">ログイン</button>
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
            // login:function() {
            //     this.errors = {};
            //     // var self = this;
            //     axios.post('/api/login', this.user)
            //         .then(function(response) {
            //             console.log('success');
            //             console.log(response);
            //         }).catch(function(error) {
            //             var responseErrors = error.response.data.errors;
            //             var errors = {};
            //             for(var key in responseErrors) {
            //                 errors[key] = responseErrors[key][0];
            //             }
            //             self.errors = errors;
            //         });
            // }

            login:function() {
                this.errors = {};
                var self = this;
                axios.get('/sanctum/crsf-cookie',{withCredentials: true}).then(response => {
                    axios.post('/login', this.user, {withCredentials: true})
                        .then(response => {
                            console.log('succsess!');
                            console.log(response);
                        }).catch(errors => {

                            console.log(errors);
                        });
                });
            }
        }
}


</script>
