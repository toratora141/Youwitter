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
    <div class="card" v-for="follow in follows" :key="follow.account_name">
        <div class="card-body d-flex flex-column">
                <div class="text-start d-flex flex-row">
                    <img :src="'/storage/' + follow.user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:100px; height:100px; object-fit:cover;">
                    <div class="m-3">
                        <p class="fs-4 m-0" v-if="follow.user.display_name" v-text="follow.user.display_name"></p>
                        <p class="fs-6" v-if="follow.user.account_name" v-text="follow.user.account_name"></p>
                    </div>
                </div>
                    <movie-list-component ref="movieList"></movie-list-component>

            <div class="card-text">
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
                    this.follows.forEach(follow => {
                        this.$nextTick(()=>{
                            var user = follow.user_id;
                            if(follow.video_list === null){
                                this.$refs.movieList[count].fetch(null, null);
                            }else{
                                this.$refs.movieList[count].fetch(follow.video_list, follow.video_list.videos);
                            }
                            count++;
                        });
                    });
            })
            .catch((error) => {
                console.log(error);
            })
    },
    created() {
        // console.log(this.$store.state.isLoggedIn);
        // console.log(this.$store.state.user);
        // console.log(this.$store.state.videoLists);
        // console.log(this.$store.state.videos);
    },
  }
</script>
