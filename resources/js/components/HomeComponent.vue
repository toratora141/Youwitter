<template>
<!-- <div class="mx-auto p-2 justify-content-center w-50" style="max-width: 600px;"> -->
<div>
    <div class="alert alert-dark" role="alert" v-show="!isLoggedIn">
        ログインされていません。<br>
        <router-link v-bind:to="{name:'user.login'}">
            <button class="btn btn-secondary">ログイン</button>
        </router-link>

        <router-link v-bind:to="{name:'user.register'}">
            <button class="btn btn-secondary" href="#">アカウント作成</button>
        </router-link>
    </div>
    <h4 class="m-auto pt-3 pb-1 text-center">タイムライン</h4>
    <div class="card" v-for="follow in follows" :key="follow.account_name">
        <div class="card-body d-flex flex-column">
                <router-link class="text-start d-flex flex-row" style="text-decoration: none; color: #141619;" v-bind:to="{name:'user.profile', params:{accountName: follow.user.account_name}}">
                <img :src="'/storage/' + follow.user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:60px; height:60px; object-fit:cover;">
                    <div class="m-3" >
                        <p class="fs-4 m-0" v-if="follow.user.display_name" v-text="follow.user.display_name"></p>
                        <p class="fs-6 m-0" v-if="follow.user.account_name" v-text="follow.user.account_name"></p>
                    </div>
                </router-link>
                <movie-list-component ref="movieList"></movie-list-component>
            <div class="card-text">
            </div>
        </div>
    </div>
    <div class="mt-3" v-if="!hasFollowed">
        <div class="alert alert-secondary w-75 m-auto mb-3">
            ユーザーをフォローしましょう！
            <router-link v-bind:to="{name:'user.search'}">
                <button class="btn btn-secondary">検索</button>
            </router-link>
        </div>
        <div class="mt-4">
            <h3>おすすめのユーザー</h3>
        </div>
         <div class="card" v-for="suggestUser in suggestUsers" :key="suggestUser.account_name">
                <div class="card-body d-flex flex-column">
                    <router-link class="text-start d-flex flex-row" style="text-decoration: none; color: #141619;" v-bind:to="{name:'user.profile', params:{accountName: suggestUser.account_name}}">
                    <img :src="'/storage/' + suggestUser.icon" class="img-fluid img-thumbnail rounded-circle" style="width:60px; height:60px; object-fit:cover;">
                        <div class="m-3" >
                            <p class="fs-4 m-0" v-if="suggestUser.display_name" v-text="suggestUser.display_name"></p>
                            <p class="fs-6 m-0" v-if="suggestUser.account_name" v-text="suggestUser.account_name"></p>
                        </div>
                    </router-link>
                    <movie-list-component ref="movieList"></movie-list-component>
                <div class="card-text">
            </div>
        </div>
    </div>
    </div>
</div>
</template>

<script>
  export default {
    data() {
      return {
        user: this.$store.state.user,
        isLoggedIn: this.$store.state.isLoggedIn,
        error: {},
        follows: {},
        movieList: {},
        hasFollowed: true,
        suggestUsers: null,
      }
    },
    mounted(){
        if(!this.isLoggedIn){
            return;
        }
        axios.get('/api/user/follow/fetch')
            .then((res) => {
                this.follows = res.data.follows;
                    var count = 0;
                    if(!this.follows.length){
                        this.fetchSuggestUser();
                        this.follows = this.suggestUsers;
                    }
                    this.follows.forEach(follow => {
                        this.$nextTick(()=>{
                            var user = follow.user_id;
                            if(follow.video_lists === null || follow.video_lists === undefined){
                                this.$refs.movieList[count].fetch(null, null);
                            }else{
                                this.$refs.movieList[count].fetch(follow.video_lists, follow.video_lists.videos);
                            }
                            count++;
                        });
                    });
                            console.log('de');
            })
            .catch((error) => {
            })
    },
    created() {
        console.log(this.$store.state.isLoggedIn);
        console.log(this.$store.state.user);
        console.log(this.$store.state.videoLists);
        console.log(this.$store.state.videos);
    },
    methods: {
        fetchSuggestUser(){
            axios.get('/api/suggestUsers')
                .then((res) => {
                    this.suggestUsers = res.data.suggestUsers;
                    // console.log(this.suggestUsers);
                    var count = 0;
                    this.hasFollowed = false;
                     this.suggestUsers.forEach(follow => {
                        this.$nextTick(()=>{
                            var user = follow.user_id;
                            if(follow.video_lists === null || follow.video_lists === undefined){
                                this.$refs.movieList[count].fetch(null, null);
                            }else{
                                this.$refs.movieList[count].fetch(follow.video_lists[0], follow.video_lists.videos);
                            }
                            count++;
                        });
                    });
                })
                .catch((error) => {

                });
        }
    }
  }
</script>
