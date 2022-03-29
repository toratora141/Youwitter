<template>
<div>
    <alert-login></alert-login>
    <div class="mx-auto p-2" style="max-width: 600px;">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex flex-column" style="height:250px">
                    <div class="text-end" v-if="!myPage">
                        <button class="btn btn-secondary" v-on:click="follow" >フォロー</button>
                    </div>
                    <div class="text-end" v-else>
                        <button class="btn btn-secondary" v-on:click="updateProfile">編集</button>
                    </div>
                    <update-profile ref="updateProfile"></update-profile>
                    <div class="text-start d-flex flex-column">
                        <img :src="user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:100px; height:100px; object-fit:cover;">
                        <div class="mt-3">
                            <h3 v-if="user.display_name" v-text="user.display_name"></h3>
                            <h6 v-if="user.account_name" v-text="user.account_name"></h6>
                        </div>
                    </div>
                </div>
                <div class="card-text">

                </div>
                <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
            </div>
        </div>
        <div>
            <movie-list-component ref="movieList"></movie-list-component>
        </div>
    </div>
</div>

</template>
<script>
import { Modal } from 'bootstrap';
import MovieListComponent from './MovieListComponent';
import AlertLogin from './AlertLogin.vue';
import UpdateProfile from './UpdateProfile.vue';
  export default {
    components: {
        'update-profile': UpdateProfile,
        'alert-login':AlertLogin,
    },
    data: function() {
      return{
            errors: {},
            user:{},
            myPage: false,
            message: null,
        }
    },
    created() {
        if(this.$route.params.account_name === this.$store.state.user.account_name){
            this.user = this.$store.state.user;
            this.myPage = true;
            // return;
        }
        axios.get('/api/user/fetchUser', {params:{
            account_name: this.$route.params.account_name
        }})
            .then((res) => {
                var user = {};
                if (res.data.result) {
                    user['who'] = 'other';
                    user['account_name'] = res.data.user.account_name;
                    user['display_name'] = res.data.user.display_name;
                    user['icon'] = '/storage/' + res.data.user.icon;
                    this.user = user;
                    this.$refs.movieList.fetch(res.data.videoLists[0], user);
                }
            })
            .catch((error) => {
                this.errors = error.response
            })
    },
    methods: {
        showUpdateModal(){
            this.message = null;
            this.updateModalObj.show();
        },
        follow() {
            axios.post('/api/user/follow')
                .then((res) => {
                    this.message = 'フォローしました！';
                })
        },
        updateProfile(){
            this.$refs.updateProfile.showUpdateModal();
        },
        follow(){
            axios.post('/api/user/follow', {
                'followAccountName': this.$route.params.account_name
            })
                .then((res) => {
                    console.log('followed');
                })
        }
    }
  }
</script>
