<template>
<div>
    <alert-login></alert-login>
    <div class="mx-auto p-2" style="max-width: 600px;">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex flex-column" style="height:250px">
                    <div class="h-100" v-if="showBtn">
                        <div class="text-end" v-if="!isMyProfile">
                            <div v-if="isFollow">
                                <button class="btn btn-secondary" v-on:click="deleteFollowing" >フォローを外す</button>
                            </div>
                            <div v-else>
                                <button class="btn btn-secondary" v-on:click="doFollowing" >フォローする</button>
                            </div>
                        </div>
                        <div class="text-end" v-else>
                            <button class="btn btn-secondary" v-on:click="updateProfile">編集</button>
                        </div>
                    </div>
                    <update-profile ref="updateProfile"></update-profile>
                    <div class="text-start d-flex flex-column">
                        <img :src="user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:100px; height:100px; object-fit:cover;">
                        <div class="mt-3">
                            <p class="fs-3 m-0" v-if="user.display_name" v-text="user.display_name"></p>
                            <p class="fs-6" v-if="user.account_name" v-text="user.account_name"></p>
                        </div>
                    </div>
                    <div class="d-flex flex-row">
                        <router-link v-bind:to="{name:'user.followed', params:{accountName:$route.params.accountName}}" style="text-decoration: none; color: #141619;">
                            <p class="mr-2">
                                フォロー
                            </p>
                        </router-link>
                        <router-link v-bind:to="{name:'user.follower', params:{accountName:$route.params.accountName}}" style="text-decoration: none; color: #141619;">
                            <p>
                                フォロワー
                            </p>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-3 mb-2" v-if="isMyProfile">
            <button class="btn btn-secondary" v-on:click="updateVideoList">再生リスト更新</button>
        </div>
        <div>
            <movie-list-component ref="movieList"></movie-list-component>
        </div>
        <div>
            <p class="text-secondary mt-1">※最新の再生リストのデータ反映までに5分ほどかかります。更新されない場合は5分後に再度お願いします。</p>
        </div>
        <div class="modal" tabindex="-10" ref="waitModal" data-bs-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <p>更新中です</p>
                    </div>
                </div>
            </div>
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
            isMyProfile: false,
            isFollow: false,
            showBtn: false,
            message: null,
            videoLists: null,
            videos: null,
            waitModalObj: null,
        }
    },
    created() {
        if(this.$route.params.accountName === this.$store.state.user.accountName){
            this.user = this.$store.state.user;
            this.isMyProfile = true;
        }
        axios.get('/api/user/fetchProf', {params:{
            accountName: this.$route.params.accountName,
            isMyProfile: this.isMyProfile
        }})
            .then((res) => {
                var user = {};
                if (res.data.result) {
                    user['account_name'] = res.data.user.account_name;
                    user['display_name'] = res.data.user.display_name;
                    user['icon'] = '/storage/' + res.data.user.icon;
                    this.videoLists = res.data.videoLists[0];
                    if(this.videoLists === undefined){
                        this.videos = undefined;
                    }else{
                        this.videos = res.data.videoLists[0].videos;

                    }
                    this.isFollow = res.data.isFollow;
                    this.showBtn = true;
                    this.user = user;
                    this.$refs.movieList.fetch(this.videoLists, this.videos);
                }
            })
            .catch((error) => {
                this.errors = error.response;
            })
    },
    mounted(){
        this.waitModalObj = new Modal(this.$refs.waitModal,{keyboard: true})
    },
    methods: {
        showUpdateModal(){
            this.message = null;
            this.updateModalObj.show();
        },
        updateProfile(){
            this.$refs.updateProfile.showUpdateModal();
        },
        doFollowing(){
            axios.post('/api/user/follow', {
                'followAccountName': this.$route.params.accountName
            })
                .then((res) => {
                    console.log('followed');
                    this.isFollow = true;
                })
        },
        deleteFollowing(){
            axios.post('/api/user/follow/delete', {
                'followAccountName': this.$route.params.accountName
            })
                .then((res) => {
                    console.log('following destroy');
                    this.isFollow = false;
                })
        },
        updateVideoList(){
            this.waitModalObj.show();
            axios.post('/api/videoList/update')
                .then((res) => {
                    this.waitModalObj.hide();
                })
                .catch((error) => {
                    this.waitModalObj.hide();
                });
            axios.get('/api/videoList/fetch', {
                params: {
                    id: this.videoLists.id
                }
            })
                .then((res) => {
                    this.videoLists = res.data.videoLists;
                    console.log(res.data.videoLists);
                    this.videos = res.data.videoLists[0].videos;
                    console.log(res.data.videoLists[0].videos);
                    this.$refs.movieList.fetch(this.videoLists, this.videos);
                    this.$forceUpdate()
                })
        }
    }
  }
</script>
