<template>
    <div>
        <div class="navbar navbar-expand-lg pb-0">
            <ul class="navbar-nav d-flex flex-row justify-content-center align-content-center w-100">
                <li class="nav-item border w-25">
                    <router-link v-bind:to="{name:'user.profile', params:{'accountName':$route.params.accountName}}" style="text-decoration: none; color: #141619;">
                        戻る
                    </router-link>
                <li class="nav-item border text-center w-50 bg-secondary">
                    <router-link class="text-white" v-bind:to="{name:'user.followed', params:{accountName:$route.params.accountName}}" style="text-decoration: none; color: #141619;">
                        フォロー
                    </router-link>
                <li class="nav-item border text-center w-50">
                    <router-link v-bind:to="{name:'user.follower', params:{accountName:$route.params.accountName}}" style="text-decoration: none; color: #141619;">
                        フォロワー
                    </router-link>
                </li>
            </ul>
        </div>
        <div v-for="followed in followedArray" :key="followed.account_name">
            <div class="card">
                <router-link style="text-decoration: none; color: #141619;"
                v-bind:to="{name:'user.profile', params:{accountName: followed.user.account_name}}">
                    <img :src="'/storage/' + followed.user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:100px; height:100px; object-fit:cover;">
                    <div class="m-3" >
                        <p class="fs-4 m-0" v-if="followed.user.display_name" v-text="followed.user.display_name"></p>
                        <p class="fs-6" v-if="followed.user.account_name" v-text="followed.user.account_name"></p>
                    </div>
                </router-link>
            </div>
        </div>
        <div v-if="!hasFollowed">
            <div class="alert alert-secondary text-center w-75 m-auto mt-3">
                ユーザーをフォローしましょう！
                <router-link v-bind:to="{name:'user.search'}">
                    <button class="btn btn-secondary">検索</button>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Modal } from 'bootstrap';

export default {
    data:function() {
        return {
            followedArray: null,
            hasFollowed: true,
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
                this.followedArray = res.data.followed;
                console.log(this.followerArray);
                if(this.followerArray === undefined){
                    this.hasFollowed = false;
                }
            });
        }
    }
}
</script>
