<template>
    <div>
        <div class="navbar navbar-expand-lg pb-0">
            <ul class="navbar-nav d-flex flex-row justify-content-center align-content-center w-100">
                <li class="nav-item border w-25">
                    <router-link v-bind:to="{name:'user.profile', params:{'accountName':$route.params.accountName}}" style="text-decoration: none; color: #141619;">
                        戻る
                    </router-link>
                <li class="nav-item border text-center w-50">
                    <router-link v-bind:to="{name:'user.followed', params:{accountName:$route.params.accountName}}" style="text-decoration: none; color: #141619;">
                        フォロー
                    </router-link>
                <li class="nav-item border text-center w-50 bg-secondary">
                    <router-link class="text-white" v-bind:to="{name:'user.follower', params:{accountName:$route.params.accountName}}" style="text-decoration: none; color: #141619;">
                        フォロワー
                    </router-link>
                </li>
            </ul>
        </div>
        <div class="mt-3 mb-3 text-center" v-show="!fetchEnd">
            <div class="spinner-border text-secondary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div v-for="follower in followerArray" :key="follower.key">
            <div class="card">
                <router-link style="text-decoration: none; color: #141619;"
                v-bind:to="{name:'user.profile', params:{accountName: follower.follow_user.account_name}}">
                    <img :src="'/storage/' + follower.follow_user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:100px; height:100px; object-fit:cover;">
                    <div class="m-3" >
                        <p class="fs-4 m-0" v-if="follower.follow_user.display_name" v-text="follower.follow_user.display_name"></p>
                        <p class="fs-6" v-if="follower.follow_user.account_name" v-text="follower.follow_user.account_name"></p>
                    </div>
                </router-link>
            </div>
        </div>
        <div v-if="!hasFollower">
            <div class="alert alert-secondary text-center w-75 m-auto mt-3">
                フォロワーがいません...
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Modal } from 'bootstrap';

export default {
    data:function() {
        return {
            followerArray: null,
            hasFollower: true,
            fetchEnd: false,
        }
    },
    created() {
        this.fetchFollowList();
    },
    methods:{
        fetchFollowList(){
            axios.get('/api/user/follow/list', {
                params:{
                    accountName: this.$route.params.accountName,
                }
            })
            .then((res)=>{
                this.fetchEnd = true;
                this.followerArray = res.data.follower;
                if(this.followerArray){
                    this.hasFollowed = false;
                }
            });
        }
    }
}
</script>
